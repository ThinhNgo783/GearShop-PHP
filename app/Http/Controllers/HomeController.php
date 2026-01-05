<?php

namespace App\Http\Controllers;

use App\Models\Nguoidung;
use Illuminate\Database\Eloquent\Model;
use App\Models\LoaiSanPham;
use App\Models\SanPham;
use App\Models\SanPhamReview;
use App\Models\SanPhamFavorite;
use Illuminate\Support\Facades\Auth;
use App\Models\DonHang_ChiTiet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\ChuDe;
use App\Models\BaiViet;
use App\Models\DonHang;
use App\Mail\DatHangThanhCongEmail;
use Illuminate\Support\Facades\Mail;
use Exception;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    public function getHome()
    {
        $loaisanpham = LoaiSanPham::with('sanphams')->get();

        // Lấy giá ETH từ CoinGecko API
        $ethPrice = null;
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get('https://api.coingecko.com/api/v3/simple/price?ids=ethereum&vs_currencies=vnd');
            $data = json_decode($response->getBody(), true);
            if (isset($data['ethereum']['vnd'])) {
                $ethPrice = $data['ethereum']['vnd'];
            }
        } catch (\Exception $e) {
            $ethPrice = null;
        }

        return view('frontend.home', compact('loaisanpham', 'ethPrice'));
    }

    public function getAdminHome()
    {
        // Kiểm tra quyền truy cập: Nếu không phải admin thì báo lỗi 403.
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        $sanphamCount = SanPham::count();
        $sanphamConLaiCount = SanPham::sum('soluong');
        $donhangCount = DonHang::count();
        $nguoidungCount = Nguoidung::count();
        $banchay = DonHang_ChiTiet::selectRaw('sanpham_id, SUM(soluongban) as total')
            ->groupBy('sanpham_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        return view('admin.home', compact('sanphamCount', 'sanphamConLaiCount', 'donhangCount', 'nguoidungCount', 'banchay'));
    }

    public function getGoogleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

    public function getGoogleCallback()
    {
        try {
            // If you need to disable SSL verification, set it in config/services.php or use Guzzle's global handler.
            $user = Socialite::driver('google')
                ->user();
        } catch (Exception $e) {
            return redirect()->route('user.dangnhap')->with('warning', 'Lỗi xác thực. Xin vui lòng thử lại!');
        }
        $existingUser = Nguoidung::where('email', $user->getEmail())->first();
        if ($existingUser) {
            // Nếu người dùng đã tồn tại thì đăng nhập
            Auth::login($existingUser, true);
            return redirect()->route('user.home');
        } else {
            // Nếu chưa tồn tại người dùng thì thêm mới
            $newUser = Nguoidung::create([
                'name' => $user->name,
                'email' => $user->email,
                'username' => Str::before($user->email, '@'),
                'password' => Hash::make('larashop@2024'), // Gán mật khẩu tự do
            ]);
            // Sau đó đăng nhập
            Auth::login($newUser, true);
            return redirect()->route('user.home');
        }
    }

    public function postHoSoCaNhan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $nguoidung = Nguoidung::find(Auth::id());
        if ($nguoidung) {
            $nguoidung->name = $request->name;
            $nguoidung->email = $request->email;
            $nguoidung->phone = $request->phone;

            if ($request->filled('password')) {
                $nguoidung->password = Hash::make($request->password);
            }

            $nguoidung->save();
        } else {
            return redirect()->route('user.hosocanhan')->with('error', 'Người dùng không tồn tại.');
        }
        return redirect()->route('user.hosocanhan')->with('success', 'Cập nhật hồ sơ thành công.');
    }

    public function getSanPham($tenloai_slug = '')
    {
        $query = SanPham::query();

        if ($tenloai_slug) {
            $loai = LoaiSanPham::where('tenloai_slug', $tenloai_slug)->first();
            if ($loai) {
                $currentLoai = $loai->tenloai;
                // Lọc sản phẩm theo loại nếu có slug
                $query->where('loaisanpham_id', $loai->id);
            } else {
                abort(404, 'Loại sản phẩm không tồn tại.');
            }
        } else {
            $currentLoai = 'Tất cả sản phẩm';
        }

        // Lấy danh sách sản phẩm
        $sanpham = $query->latest()->paginate(8);

        $loaisanpham = LoaiSanPham::all();

        return view('frontend.sanpham', compact('loaisanpham', 'sanpham', 'currentLoai'));
    }

    public function getSanPham_ChiTiet($tenloai_slug = '', $tensanpham_slug = '')
    {
        $loai = LoaiSanPham::where('tenloai_slug', $tenloai_slug)->first();

        if (!$loai) {
            abort(404, 'Loại sản phẩm không tồn tại.');
        }

        $sanpham = SanPham::whereHas('loaisanpham', function ($query) use ($tenloai_slug) {
            $query->where('tenloai_slug', $tenloai_slug);
        })
            ->where('tensanpham_slug', $tensanpham_slug)
            ->first();

        if (!$sanpham) {
            abort(404, 'Sản phẩm không tồn tại.');
        }

        $loaisanpham = LoaiSanPham::all();

        // Lấy sản phẩm cùng loại (không bao gồm sản phẩm hiện tại)
        $sanphamcungloai = SanPham::where('loaisanpham_id', $sanpham->loaisanpham_id)
            ->where('id', '<>', $sanpham->id)
            ->take(4)
            ->get();

        // Lấy đánh giá và trạng thái yêu thích của user
        $reviews = $sanpham->reviews()->with('nguoidung')->orderBy('created_at', 'desc')->get();
        $isFavorited = false;
        if (Auth::check()) {
            $isFavorited = SanPhamFavorite::where('sanpham_id', $sanpham->id)->where('nguoidung_id', Auth::id())->exists();
        }

        return view('frontend.sanpham_chitiet', compact('sanpham', 'loai', 'loaisanpham', 'sanphamcungloai', 'reviews', 'isFavorited'));
    }

    public function getBaiViet($tenchude_slug = '')
    {
        // Bổ sung code tại đây
        if (empty($tenchude_slug)) {
            $title = 'Tin tức';
            $baiviet = BaiViet::where('kichhoat', 1)
                ->where('kiemduyet', 1)
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            $chude = ChuDe::where('tenchude_slug', $tenchude_slug)
                ->firstOrFail();
            $title = $chude->tenchude;
            $baiviet = BaiViet::where('kichhoat', 1)
                ->where('kiemduyet', 1)
                ->where('chude_id', $chude->id)
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }
        return view('frontend.baiviet', compact('title', 'baiviet'));
    }

    public function getBaiViet_ChiTiet($tenchude_slug = '', $tieude_slug = '')
    {
        // Bổ sung code tại đây
        $tieude_id = explode('.', $tieude_slug);
        $tieude = explode('-', $tieude_id[0]);
        $baiviet_id = $tieude[count($tieude) - 1];
        $baiviet = BaiViet::where('kichhoat', 1)
            ->where('kiemduyet', 1)
            ->where('id', $baiviet_id)
            ->firstOrFail();
        if (!$baiviet) abort(404);
        // Cập nhật lượt xem
        $daxem = 'BV' . $baiviet_id;
        if (!session()->has($daxem)) {
            $orm = BaiViet::find($baiviet_id);
            $orm->luotxem = $baiviet->luotxem + 1;
            $orm->save();
            session()->put($daxem, 1);
        }

        $baivietcungchuyemuc = BaiViet::where('kichhoat', 1)
            ->where('kiemduyet', 1)
            ->where('chude_id', $baiviet->chude_id)->where('id', '!=', $baiviet_id)
            ->orderBy('created_at', 'desc')
            ->take(4)->get();

        return view('frontend.baiviet_chitiet', compact('baiviet', 'baivietcungchuyemuc'));
    }

    public function getGioHang()
    {
        if (Cart::count() > 0)
            return view('frontend.giohang');
        else
            return view('frontend.giohangrong');
    }

    public function getGioHang_Them($tensanpham_slug = '')
    {
        $sanpham = SanPham::where('tensanpham_slug', $tensanpham_slug)->first();
        Cart::add($sanpham->id, $sanpham->tensanpham, 1, $sanpham->dongia, 0, [
            'id' => $sanpham->id,
            'name' => $sanpham->tensanpham,
            'price' => $sanpham->dongia,
            'qty' => 1,
            'weight' => 0,
            'options' => [
                'image' => $sanpham->hinhanh
            ]
        ]);
        return redirect()->route('frontend.home');
    }

    public function getGioHang_Xoa($row_id)
    {
        // Bổ sung code tại đây
        Cart::remove($row_id);
        return redirect()->route('frontend.giohang');
    }

    public function getGioHang_Giam($row_id)
    {
        // Bổ sung code tại đây
        $row = Cart::get($row_id);
        // Nếu số lượng là 1 thì không giảm được nữa
        if ($row->qty > 1) {
            Cart::update($row_id, $row->qty - 1);
        }
        return redirect()->route('frontend.giohang');
    }

    public function getGioHang_Tang($row_id)
    {
        // Bổ sung code tại đây
        $row = Cart::get($row_id);
        // Không được tăng vượt quá 10 sản phẩm
        if ($row->qty < 10) {
            Cart::update($row_id, $row->qty + 1);
        }
        return redirect()->route('frontend.giohang');
    }

    public function postGioHang_CapNhat(Request $request)
    {
        // Bổ sung code tại đây
        foreach ($request->qty as $row_id => $quantity) {
            if ($quantity <= 0)
                Cart::update($row_id, 1);
            else if ($quantity > 10)
                Cart::update($row_id, 10);
            else
                Cart::update($row_id, $quantity);
        }
        return redirect()->route('frontend.giohang');
    }

    public function getTuyenDung()
    {
        return view('frontend.tuyendung');
    }
    public function getLienHe()
    {
        return view('frontend.lienhe');
    }
    // Trang đăng ký dành cho khách hàng
    public function getDangKy()
    {
        return view('user.dangky');
    }

    // Trang đăng nhập dành cho khách hàng
    public function getDangNhap()
    {
        return view('user.dangnhap');
    }

    public function getDatHang()
    {
        // Kiểm tra giỏ hàng có sản phẩm hay không
        if (Cart::count() == 0) {
            return redirect()->route('frontend.giohang')->with('warning', 'Giỏ hàng của bạn đang trống.');
        }

        // Lấy thông tin người dùng hiện tại
        $nguoidung = Auth::user();

        return view('user.dathang', compact('nguoidung'));
    }

    public function postDatHang(Request $request)
    {
        // Kiểm tra giỏ hàng có sản phẩm hay không
        if (Cart::count() == 0) {
            return redirect()->route('frontend.giohang')->with('warning', 'Giỏ hàng của bạn đang trống.');
        }

        // Lấy thông tin từ giỏ hàng
        $cartItems = Cart::content();

        // Tạo đơn hàng mới
        $donhang = new DonHang();
        $donhang->user_id = Auth::user()->id;
        $donhang->tinhtrang_id = 1; // Lưu ý: Bảng tinhtrang phải có dữ liệu có id = 1
        $donhang->dienthoaigiaohang = $request->dienthoaigiaohang; // Lấy số điện thoại từ form
        $donhang->diachigiaohang = $request->diachigiaohang; // Lấy địa chỉ từ form
        $donhang->ma_don = 'DH-' . Str::upper(Str::random(8)); // Sinh mã đơn hàng ngẫu nhiên
        $donhang->save();

        // Lưu thông tin chi tiết đơn hàng
        foreach ($cartItems as $item) {
            $donhang_chitiet = new DonHang_ChiTiet();
            $donhang_chitiet->donhang_id = $donhang->id;
            $donhang_chitiet->sanpham_id = $item->id;
            $donhang_chitiet->soluongban = $item->qty;
            $donhang_chitiet->dongiaban = $item->price;
            $donhang_chitiet->save();
        }

        // Gửi email xác nhận đơn hàng
        Mail::to(Auth::user()->email)->send(new DatHangThanhCongEmail($donhang));

        // Xóa giỏ hàng sau khi đặt hàng thành công
        Cart::destroy();

        return redirect()->route('frontend.dathangthanhcong')->with('success', 'Đặt hàng thành công.');
    }
}
