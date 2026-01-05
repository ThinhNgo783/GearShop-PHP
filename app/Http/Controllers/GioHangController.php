<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Http;

class GioHangController extends Controller
{
    private function fetchEthPrice()
    {
        try {
            // Call microservice or external API to get ETH price in VND
            // Example using CoinGecko API
            $response = Http::get('https://api.coingecko.com/api/v3/simple/price', [
                'ids' => 'ethereum',
                'vs_currencies' => 'vnd',
            ]);
            if ($response->successful()) {
                return $response->json()['ethereum']['vnd'] ?? null;
            }
        } catch (\Exception $e) {
            // Log error or handle exception
        }
        return null;
    }

    public function themVaoGioHang(Request $request, $tensanpham_slug)
    {
        $sanpham = SanPham::where('tensanpham_slug', $tensanpham_slug)->first();

        // Validate request quantity
        $requestedQty = intval($request->input('quantity', 0));
        if ($requestedQty < 1) {
            return redirect()->route('frontend.sanpham.chitiet', ['tenloai_slug' => $sanpham->loaiSanPham->tenloai_slug, 'tensanpham_slug' => $tensanpham_slug])
                ->with('error', 'Vui lòng cung cấp số lượng hợp lệ.');
        }

        if (!$sanpham) {
            return redirect()->route('frontend.sanpham')->with('error', 'Sản phẩm không tồn tại.');
        }

        // Lấy số lượng cùng sản phẩm đã có trong giỏ (nếu có)
        $existingItem = Cart::content()->where('id', $sanpham->id)->first();
        $existingQty = $existingItem ? intval($existingItem->qty) : 0;

        // Kiểm tra tồn kho: tổng (đã có trong giỏ + yêu cầu mới) không vượt quá tồn kho
        if ($existingQty + $requestedQty > intval($sanpham->soluong)) {
            $available = intval($sanpham->soluong) - $existingQty;
            if ($available <= 0) {
                return redirect()->route('frontend.sanpham.chitiet', ['tenloai_slug' => $sanpham->loaiSanPham->tenloai_slug, 'tensanpham_slug' => $tensanpham_slug])
                    ->with('error', 'Sản phẩm đã hết hàng.');
            }
            return redirect()->route('frontend.sanpham.chitiet', ['tenloai_slug' => $sanpham->loaiSanPham->tenloai_slug, 'tensanpham_slug' => $tensanpham_slug])
                ->with('error', 'Chỉ còn ' . $available . ' sản phẩm trong kho.');
        }

        // Thêm sản phẩm vào giỏ hàng (không giảm tồn kho ở bước này)
        Cart::add($sanpham->id, $sanpham->tensanpham, $requestedQty, $sanpham->dongia, 0, ['image' => $sanpham->hinhanh]);

        return redirect()->route('frontend.giohang.hienthi')->with('success', 'Thêm sản phẩm vào giỏ hàng thành công.');
    }

    public function hienThiGioHang()
    {
        // Đếm số lượng sản phẩm trong giỏ
        $count = Cart::count();
        if ($count == 0) {
            // Nếu giỏ hàng trống, trả về view giỏ hàng rỗng
            return view('frontend.giohangrong');
        } else {
            // Ngược lại, trả về view giỏ hàng thông thường, truyền biến $giohang và ethPrice
            $giohang = Cart::content();
            $ethPrice = $this->fetchEthPrice();
            return view('frontend.giohang', compact('giohang', 'ethPrice'));
        }
    }

    public function capNhatGioHang(Request $request)
    {
        foreach ($request->qty as $rowId => $qty) {
            Cart::update($rowId, $qty);
        }
        return redirect()->route('frontend.giohang.hienthi')->with('success', 'Cập nhật giỏ hàng thành công.');
    }

    public function xoaKhoiGioHang($rowId)
    {
        Cart::remove($rowId);
        return redirect()->route('frontend.giohang.hienthi')->with('success', 'Xóa sản phẩm khỏi giỏ hàng thành công.');
    }

    public function tangSoLuong($rowId)
    {
        $item = Cart::get($rowId);
        Cart::update($rowId, $item->qty + 1);
        return redirect()->route('frontend.giohang.hienthi');
    }

    public function giamSoLuong($rowId)
    {
        $item = Cart::get($rowId);
        if ($item->qty > 1) {
            Cart::update($rowId, $item->qty - 1);
        }
        return redirect()->route('frontend.giohang.hienthi');
    }
}
