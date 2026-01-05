<?php

namespace App\Http\Controllers;

use App\Models\Nguoidung;
use App\Models\DonHang;
use App\Models\DonHang_ChiTiet;
use App\Models\DiaChi;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\PhuongThucThanhToan;
use Illuminate\Support\Facades\Http;

class KhachHangController extends Controller
{
    // ETH price functionality removed

    public function getHome()
    {
        $nguoidung = Auth::check() ? Nguoidung::find(Auth::user()->id) : null;

        if ($nguoidung) {
            return view('user.home', compact('nguoidung'));
        } else {
            return redirect()->route('user.dangnhap');
        }
    }

    public function getDatHang()
    {
        if (Auth::check()) {
            $nguoidung = Auth::user();
            // Lấy danh sách các phương thức thanh toán (có thể thêm điều kiện where hoatdong = 1 nếu cần)
            $phuongthucthanhtoan = PhuongThucThanhToan::all();
            return view('user.dathang', compact('nguoidung', 'phuongthucthanhtoan'));
        } else {
            return redirect()->route('user.dangnhap');
        }
    }

    public function postDatHang(Request $request)
    {
        $this->validate($request, [
            'diachigiaohang'          => ['required', 'string', 'max:255'],
            'dienthoaigiaohang'        => ['required', 'string', 'max:255'],
            'phuongthucthanhtoan_id'   => ['required'], // Bắt buộc chọn phương thức thanh toán
        ]);

        $dh = new DonHang();
        $dh->nguoidung_id = Auth::user()->id;
        $dh->tinhtrang_id = 1; // Đơn hàng mới
        $dh->diachigiaohang = $request->diachigiaohang;
        $dh->dienthoaigiaohang = $request->dienthoaigiaohang;
        $dh->phuongthucthanhtoan_id = $request->phuongthucthanhtoan_id; // Lưu phương thức thanh toán

        // Xác định phí vận chuyển dựa trên phương thức thanh toán
        if ($request->phuongthucthanhtoan_id == 1) { // Giả sử ID 1 là thanh toán khi nhận hàng
            $dh->phivanchuyen = 1000;
        } else {
            $dh->phivanchuyen = 0;
        }

        $dh->ma_don = 'DH-' . Str::upper(Str::random(8));
        $dh->save();

        foreach (Cart::content() as $value) {
            $ct = new DonHang_ChiTiet();
            $ct->donhang_id = $dh->id;
            $ct->sanpham_id = $value->id;
            $ct->soluongban = $value->qty;
            $ct->dongiaban = $value->price;
            $ct->save();

            // Giảm số lượng sản phẩm trong kho
            $sanpham = SanPham::find($value->id);
            if ($sanpham) {
                $sanpham->soluong -= $value->qty;
                if ($sanpham->soluong < 0) {
                    $sanpham->soluong = 0;
                }
                $sanpham->save();
            }
        }

        Cart::destroy(); // Xóa giỏ hàng sau khi đặt hàng thành công

        return redirect()->route('frontend.dathangthanhcong');
    }

    public function getDatHangThanhCong()
    {
        return view('user.dathangthanhcong');
    }

public function getDonHang(Request $request)
{
    $nguoidung = Nguoidung::find(Auth::user()->id);
    $tinhtrang = $request->input('tinhtrang', 'Tất cả');

    $query = DonHang::with(['donhang_chitiet.sanpham', 'tinhtrang'])
        ->where('nguoidung_id', $nguoidung->id);

    if ($tinhtrang != 'Tất cả') {
        $query->whereHas('tinhtrang', function ($q) use ($tinhtrang) {
            $q->where('tinhtrang', $tinhtrang);
        });
    }

    $donhangs = $query->get();

    // Recalculate total price for each order
    foreach ($donhangs as $donhang) {
        $donhang->recalculateTotalPrice();
    }

    $donhangs = $donhangs->sortBy(function ($dh) {
        $order = 0;
        if ($dh->tinhtrang->tinhtrang == 'Đơn hàng mới') {
            $order = 1;
        } elseif ($dh->tinhtrang->tinhtrang == 'Đang vận chuyển') {
            $order = 2;
        } elseif ($dh->tinhtrang->tinhtrang == 'Đã hoàn thành') {
            $order = 3;
        } elseif ($dh->tinhtrang->tinhtrang == 'Đã hủy') {
            $order = 4;
        }
        return [$order, $dh->created_at];
    });

    return view('user.donhang', compact('nguoidung', 'donhangs', 'tinhtrang'));
}

    public function postDonHang(Request $request, $id)
    {
        // Bổ sung code tại đây nếu cần thiết
    }

    public function getHoSoCaNhan()
    {
        $nguoidung = Auth::user();
        // Lấy record đầu tiên từ quan hệ diaChi (nếu có) hoặc null nếu không có
        $diachi = $nguoidung->diaChi->first();
        return view('user.home', compact('nguoidung', 'diachi'));
    }

    public function postHoSoCaNhan(Request $request)
    {
        $id = Auth::user()->id;
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:nguoidung,email,' . $id],
            'password' => ['confirmed'],
            'phone' => ['required', 'string', 'max:15'],
            'diachi'  => 'required|string|max:255',
        ]);

        $orm = Nguoidung::find($id);
        $orm->name = $request->name;
        $orm->username = Str::before($request->email, '@');
        $orm->email = $request->email;
        $orm->phone = $request->phone;
        if (!empty($request->password)) {
            $orm->password = Hash::make($request->password);
        }
        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            // Lưu file vào thư mục public/uploads/avatars (tùy chỉnh theo ý bạn)
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/avatars'), $filename);
        }
        $orm->diachi = $request->diachi;
        $orm->save();

        return redirect()->route('user.home')->with('success', 'Đã cập nhật thông tin thành công.');
    }

    public function huyDonHang($donhang_id)
    {
        $donhang = DonHang::findOrFail($donhang_id);

        // (Có thể thêm kiểm tra trạng thái để cho phép hủy đơn)

        // Lấy chi tiết đơn hàng
        $orderDetails = DonHang_ChiTiet::where('donhang_id', $donhang->id)->get();

        foreach ($orderDetails as $ct) {
            $sanpham = SanPham::find($ct->sanpham_id);
            if ($sanpham) {
                Log::info("Trước khi hoàn trả: Sản phẩm ID {$sanpham->id} có số lượng {$sanpham->soluong}");
                // Cộng số lượng bán (soluongban) về lại kho
                $sanpham->soluong += $ct->soluongban;
                $sanpham->save();
                Log::info("Sau khi hoàn trả: Sản phẩm ID {$sanpham->id} có số lượng {$sanpham->soluong}");
            }
        }

        // Cập nhật trạng thái đơn hàng (giả sử 4 là trạng thái hủy)
        $donhang->tinhtrang_id = 4;

        // Cập nhật trạng thái hoàn trả (nếu có cột refund_status trong bảng donhang)
        $donhang->refund_status = 'pending';
        $donhang->save();

        return redirect()->route('user.donhang')->with('success', 'Đơn hàng đã được hủy, số lượng đã được hoàn trả.');
    }

    public function updateRefundStatus($donhang_id, Request $request)
    {
        $donhang = DonHang::findOrFail($donhang_id);

        // Kiểm tra quyền sở hữu đơn hàng
        if ($donhang->nguoidung_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'refund_status' => 'required|in:pending,completed,failed',
        ]);

        $donhang->refund_status = $request->refund_status;
        $donhang->save();

        return response()->json(['message' => 'Refund status updated successfully']);
    }

    public function getThanhtoan()
    {
        $nguoidung = Auth::user();
        $phuongthucthanhtoan = PhuongThucThanhToan::all();
        return view('user.thanhtoan', compact('nguoidung', 'phuongthucthanhtoan'));
    }

    public function chonThanhtoan($id)
    {
        // Lưu phương thức thanh toán đã chọn vào session
        session(['phuongthucthanhtoan_id' => $id]);
        // Redirect về trang đặt hàng
        return redirect()->route('user.dathang')->with('success', 'Phương thức thanh toán đã được chọn.');
    }
}
