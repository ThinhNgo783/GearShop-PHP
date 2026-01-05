<?php

namespace App\Http\Controllers;

use App\Models\Hangsanxuat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Imports\HangSanXuatImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HangSanPhamExport;
use App\Exports\HangSanXuatExport;
use App\Imports\readdir;
class HangsanxuatController extends Controller
{
    //Nhap tu Excel
    public function postNhap(Request $request)
    {
        Excel::import(new HangsanxuatImport, $request->file('file_excel'));
        return redirect()->route('admin.hangsanxuat');
    }

    // Xuất ra Excel
    public function getXuat()
    {
        return Excel::download(new HangSanXuatExport, 'danh-sach-san-pham.xlsx');
    }

    public function getDanhSach()
    {
        $hangsanxuat = Hangsanxuat::all();
        return view('admin.hangsanxuat.danhsach', compact('hangsanxuat'));
    }

    public function getThem()
    {
        return view('admin.hangsanxuat.them');
    }

    public function postThem(Request $request)
    {
        //Kiểm tra
        $request->validate([
            'tenhang' => ['required', 'string', 'max:255', 'unique:hangsanxuat'],
            'hinhanh' => ['nullable', 'image', 'max:1024']
        ]);

        //Upload Hình ảnh
        $path = '';
        if ($request->hasFile('hinhanh')) {
            $extension = $request->file('hinhanh')->extension();
            $filename = Str::slug($request->tenhang, '-') . '.' . $extension;
            $path = Storage::putFileAs('hang-san-xuat', $request->file('hinhanh'), $filename);
        }

        //Thêm
        $orm = new Hangsanxuat();
        $orm->tenhang = $request->tenhang;
        $orm->tenhang_slug = Str::slug($request->tenhang, '-');
        if (!empty($path)) $orm->hinhanh = $path;
        $orm->save();

        return redirect()->route('admin.hangsanxuat');
    }

    public function getSua($id)
    {
        $hangsanxuat = Hangsanxuat::find($id);
        return view('admin.hangsanxuat.sua', compact('hangsanxuat'));
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'tenhang' => ['required', 'string', 'max:255', 'unique:hangsanxuat,tenhang,' . $id],
        ]);

        // Upload hình ảnh
        $path = '';
        if ($request->hasFile('hinhanh')) {
            // Xóa file cũ
            $orm = HangSanXuat::find($id);
            if (!empty($orm->hinhanh)) Storage::delete($orm->hinhanh);

            // Upload file mới
            $extension = $request->file('hinhanh')->extension();
            $filename = Str::slug($request->tenhang, '-') . '.' . $extension;
            $path = Storage::putFileAs('hang-san-xuat', $request->file('hinhanh'), $filename);
        }

        //Sửa
        $orm = Hangsanxuat::find($id);
        $orm->tenhang = $request->tenhang;
        $orm->tenhang_slug = Str::slug($request->tenhang, '-');
        if (!empty($path)) $orm->hinhanh = $path;
        $orm->save();
        return redirect()->route('admin.hangsanxuat');
    }

    public function getXoa($id)
    {
        $orm = Hangsanxuat::find($id);
        $orm->delete();

        //Xóa hình ảnh khi xóa dữ liệu
        if (!empty($orm->hinhanh)) Storage::delete($orm->hinhanh);

        //sau khi xóa thành công sẽ chuyển về trang chủ
        return redirect()->route('admin.hangsanxuat');
    }
}
