<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use App\Models\LoaiSanPham;
use App\Models\HangSanXuat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use App\Imports\SanPhamImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SanPhamExport;
use Illuminate\Support\Facades\Auth;
use App\Models\SanPhamReview;
use App\Models\SanPhamFavorite;

class SanPhamController extends Controller
{
    // Nhập từ Excel
    public function postNhap(Request $request)
    {
        Excel::import(new SanPhamImport, $request->file('file_excel'));
        return redirect()->route('admin.sanpham');
    }

    // POST: submit product review (requires auth)
    public function postReview(Request $request, $tenloai_slug, $tensanpham_slug)
    {
        $sanpham = SanPham::where('tensanpham_slug', $tensanpham_slug)->firstOrFail();
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000'
        ]);

        $review = new SanPhamReview();
        $review->sanpham_id = $sanpham->id;
        $review->nguoidung_id = Auth::id();
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();

        return redirect()->route('frontend.sanpham.chitiet', ['tenloai_slug' => $tenloai_slug, 'tensanpham_slug' => $tensanpham_slug])->with('success', 'Cảm ơn bạn đã đánh giá.');
    }

    // POST: toggle favorite (heart) — returns JSON for AJAX
    public function toggleFavorite(Request $request, $tenloai_slug, $tensanpham_slug)
    {
        $sanpham = SanPham::where('tensanpham_slug', $tensanpham_slug)->firstOrFail();
        $userId = Auth::id();
        $exists = SanPhamFavorite::where('sanpham_id', $sanpham->id)->where('nguoidung_id', $userId)->first();
        if ($exists) {
            $exists->delete();
            $msg = 'Đã bỏ yêu thích.';
            $favorited = false;
        } else {
            SanPhamFavorite::create(['sanpham_id' => $sanpham->id, 'nguoidung_id' => $userId]);
            $msg = 'Đã thêm vào yêu thích.';
            $favorited = true;
        }

        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            return response()->json(['message' => $msg, 'favorited' => $favorited]);
        }

        return redirect()->route('frontend.sanpham.chitiet', ['tenloai_slug' => $tenloai_slug, 'tensanpham_slug' => $tensanpham_slug])->with('success', $msg);
    }

    // Xuất ra Excel
    public function getXuat()
    {
        return Excel::download(new SanPhamExport, 'danh-sach-san-pham.xlsx');
    }

    public function getDanhSach(Request $request)
    {
        $query = SanPham::query();

        if ($request->has('search')) {
            $query->where('tensanpham', 'like', '%' . $request->search . '%');
        }

        if ($request->has('hangsanxuat_id') && $request->hangsanxuat_id != '') {
            $query->where('hangsanxuat_id', $request->hangsanxuat_id);
        }

        $sanpham = $query->paginate(10);
        $hangsanxuat = HangSanXuat::all();

        return view('admin.sanpham.danhsach', compact('sanpham', 'hangsanxuat'));
    }

    public function getThem()
    {
        $loaisanpham = LoaiSanPham::all();
        $hangsanxuat = HangSanXuat::all();
        return view('admin.sanpham.them', compact('loaisanpham', 'hangsanxuat'));
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'loaisanpham_id' => ['required'],
            'hangsanxuat_id' => ['required'],
            'tensanpham' => ['required', 'string', 'max:255', 'unique:sanpham'],
            'soluong' => ['required', 'numeric'],
            'gianhap' => ['required', 'numeric'],
            'dongia' => ['required', 'numeric'],
            'motasanpham' => ['nullable', 'string'],
            'hinhanh' => ['nullable', 'image', 'max:2048'],
        ]);

        $path = '';
if ($request->hasFile('hinhanh')) {
    $extension = $request->file('hinhanh')->extension();
    $filename = Str::slug($request->tensanpham, '-') . '.' . $extension;
    $path = Storage::putFileAs('may-tinh-xach-tay', $request->file('hinhanh'), $filename);
}

        $orm = new SanPham();
        $orm->loaisanpham_id = $request->loaisanpham_id;
        $orm->hangsanxuat_id = $request->hangsanxuat_id;
        $orm->tensanpham = $request->tensanpham;
        $orm->tensanpham_slug = Str::slug($request->tensanpham, '-');
        $orm->soluong = $request->soluong;
        $orm->gianhap = $request->gianhap;
        $orm->dongia = $request->dongia;
        if (!empty($path)) $orm->hinhanh = $path;
        $orm->motasanpham = $request->motasanpham;
        $orm->save();

        return redirect()->route('admin.sanpham')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function getSua($id)
    {
        $sanpham = SanPham::findOrFail($id); // Sử dụng findOrFail
        $loaisanpham = LoaiSanPham::all();
        $hangsanxuat = HangSanXuat::all();
        return view('admin.sanpham.sua', compact('sanpham', 'loaisanpham', 'hangsanxuat'));
    }


    public function postSua(Request $request, $id)
    {
        $request->validate([
            'loaisanpham_id' => ['required'],
            'hangsanxuat_id' => ['required'],
            'tensanpham' => ['required', 'string', 'max:255', 'unique:sanpham,tensanpham,' . $id],
            'soluong' => ['required', 'numeric'],
            'dongia' => ['required', 'numeric'],
            'motasanpham' => ['nullable', 'string'],
            'hinhanh' => ['nullable', 'image', 'max:2048'],
        ]);

        $orm = SanPham::findOrFail($id);

        $path = $orm->hinhanh; // Giữ lại hình ảnh cũ nếu không upload hình mới
if ($request->hasFile('hinhanh')) {
    $extension = $request->file('hinhanh')->extension();
    $filename = Str::slug($request->tensanpham, '-') . '.' . $extension;
    $path = Storage::putFileAs('may-tinh-xach-tay', $request->file('hinhanh'), $filename);
}

        $orm->loaisanpham_id = $request->loaisanpham_id;
        $orm->hangsanxuat_id = $request->hangsanxuat_id;
        $orm->tensanpham = $request->tensanpham;
        $orm->tensanpham_slug = Str::slug($request->tensanpham, '-');
        $orm->soluong = $request->soluong;
        $orm->dongia = $request->dongia;
        $orm->hinhanh = $path;
        $orm->motasanpham = $request->motasanpham;
        $orm->save();

        return redirect()->route('admin.sanpham')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function getXoa($id)
    {
        $orm = SanPham::findOrFail($id); // Sử dụng findOrFail
        $orm->delete();

        // Xóa tập tin khi xóa sản phẩm
        if (!empty($orm->hinhanh)) Storage::delete($orm->hinhanh);

        return redirect()->route('admin.sanpham')->with('success', 'Xóa sản phẩm thành công!');
    }
}
