<?php

namespace App\Http\Controllers;

use App\Models\Nguoidung;
use App\Models\DonHang;
use App\Models\DonHang_ChiTiet;
use App\Models\TinhTrang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PhuongThucThanhToan;

class DonHangController extends Controller
{
    public function getDanhSach()
    {
        $donhang = DonHang::with(['nguoidung', 'donhang_chitiet.sanpham', 'tinhtrang'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.donhang.danhsach', compact('donhang'));
    }

    public function getThem()
    {
        // Đặt hàng bên Front-end
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'tinhtrang_id' => ['required'],
            'dienthoaigiaohang' => ['required', 'string', 'max:20'],
            'diachigiaohang' => ['required', 'string', 'max:255'],
            'phuongthucthanhtoan_id' => ['required'],  // Thêm validate cho phương thức thanh toán
        ]);

        $dh = new DonHang();
        $dh->nguoidung_id = Auth::user()->id;
        $dh->tinhtrang_id = $request->tinhtrang_id; // Ensure this ID exists in the tinhtrang table
        $dh->diachigiaohang = $request->diachigiaohang;
        $dh->dienthoaigiaohang = $request->dienthoaigiaohang;
        $dh->phuongthucthanhtoan_id = $request->phuongthucthanhtoan_id; // Lưu phương thức thanh toán
        $dh->ma_don = 'DH-' . Str::upper(Str::random(8)); // Generate a random order code
        $dh->save();

        foreach (Cart::content() as $value) {
            $ct = new DonHang_ChiTiet();
            $ct->donhang_id = $dh->id;
            $ct->sanpham_id = $value->id;
            $ct->soluongban = $value->qty;
            $ct->dongiaban = $value->price;
            $ct->save();

            // Giảm số lượng sản phẩm trong kho
            $sanpham = \App\Models\SanPham::find($value->id);
            if ($sanpham) {
                $sanpham->soluong -= $value->qty;
                if ($sanpham->soluong < 0) {
                    $sanpham->soluong = 0;
                }
                $sanpham->save();
            }
        }

        // Recalculate total price after saving order details
        $dh->recalculateTotalPrice();

        Cart::destroy();

        return redirect()->route('frontend.dathangthanhcong');
    }


    public function getSua($id)
    {
        $donhang = DonHang::find($id);
        $tinhtrang = TinhTrang::all();
        $phuongthucThanhToan = PhuongThucThanhToan::all();
        return view('admin.donhang.sua', compact('donhang', 'tinhtrang'));
    }

    public function postSua(Request $request, $id)
    {
        // Kiểm tra
        $request->validate([
            'tinhtrang_id' => ['required'],
            'dienthoaigiaohang' => ['required', 'string', 'max:20'],
            'diachigiaohang' => ['required', 'string', 'max:255'],
        ]);

        $orm = DonHang::find($id);
        $orm->tinhtrang_id = $request->tinhtrang_id;
        $orm->dienthoaigiaohang = $request->dienthoaigiaohang;
        $orm->diachigiaohang = $request->diachigiaohang;
        $orm->save();

        // Sau khi sửa thành công thì tự động chuyển về trang danh sách
        return redirect()->route('admin.donhang');
    }

    public function getXoa($id)
    {
        // Xóa đơn hàng chi tiết
        DonHang_ChiTiet::where('donhang_id', $id)->delete();

        $orm = DonHang::find($id);
        $orm->delete();

        // Sau khi xóa thành công thì tự động chuyển về trang danh sách
        return redirect()->route('admin.donhang');
    }

    public function nhanHang($id)
    {
        $donhang = DonHang::find($id);
        if ($donhang && $donhang->tinhtrang_id == 2) { // Đang vận chuyển
            $donhang->tinhtrang_id = 3; // Đã hoàn thành
            $donhang->save();
            return redirect()->route('user.donhang')->with('success', 'Đơn hàng đã được cập nhật thành Đã hoàn thành.');
        }
        return redirect()->route('user.donhang')->with('warning', 'Không thể cập nhật đơn hàng.');
    }

    public function huyDon(Request $request, $id)
    {
        $donhang = DonHang::find($id);
        if ($donhang && $donhang->tinhtrang_id != 3) { // Không phải Đã hoàn thành
            // Lấy chi tiết đơn hàng
            $orderDetails = DonHang_ChiTiet::where('donhang_id', $donhang->id)->get();

            foreach ($orderDetails as $ct) {
                $sanpham = \App\Models\SanPham::find($ct->sanpham_id);
                if ($sanpham) {
                    $sanpham->soluong += $ct->soluongban;
                    $sanpham->save();
                }
            }

            $donhang->tinhtrang_id = 4; // Đã hủy
            $donhang->save();
        }

        return redirect()->route('user.donhang')->with('success', 'Đơn hàng đã được cập nhật thành Đã hủy và số lượng sản phẩm đã được hoàn trả.');
    }


    public function exportPDF($id)
    {
        // Lấy đơn hàng cùng với các quan hệ liên quan để hiển thị chi tiết
        $donhang = DonHang::with([
            'nguoidung',
            'phuongthucthanhtoan',
            'donhang_chitiet.sanpham',
            'tinhtrang'
        ])->findOrFail($id);

        // Tạo PDF từ view export (tạo file resources/views/admin/donhang/export.blade.php)
        $pdf = Pdf::loadView('admin.donhang.export_pdf', compact('donhang'));

        // Xuất file PDF, tên file dựa trên mã đơn
        return $pdf->download('donhang_' . $donhang->ma_don . '.pdf');
    }
}
