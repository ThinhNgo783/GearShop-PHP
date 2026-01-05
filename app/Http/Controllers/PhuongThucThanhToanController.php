<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PhuongThucThanhToan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PhuongThucThanhToanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getDanhSach()
    {
        $phuongthucThanhToan = PhuongThucThanhToan::all();
        return view("admin.thanhtoan.danhsach", compact('phuongthucThanhToan'));
    }

        public function getThem()
    {
        return view("admin.thanhtoan.them");
    }
    public function postThem(Request $request)
    {
        $request->validate([
            'tenphuongthucthanhtoan' => ['required', 'string', 'max:255'],
            'hoatdong' => ['required'],
            'hinhanh' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg|max:2048'],
        ]);
        
        $path = '';
        if ($request->hasFile('hinhanh')) {
            $extension = $request->file('hinhanh')->extension();
            $filename = Str::slug($request->tenphuongthucthanhtoan, '-') . '.' . $extension;
            $path = Storage::disk('storage')->putFileAs('phuongthucthanhtoan', $request->file('hinhanh'), $filename);
        }

        $orm = new PhuongThucThanhToan();
        $orm->tenphuongthucthanhtoan = $request->tenphuongthucthanhtoan;
        $orm->hoatdong = $request->hoatdong;
        if (!empty($path)) $orm->hinhanh = $path;
        $orm->save();

        return redirect()->route('admin.thanhtoan')->with('success', 'Thêm phương thức thanh toán thành công.');
    }
    public function getSua($id)
    {
        $phuongthucthanhtoan = PhuongThucThanhToan::find($id);
        return view("admin.thanhtoan.sua", compact('phuongthucthanhtoan'));
    }
    public function postSua(Request $request, $id)
    {
        $request->validate([
            'tenphuongthucthanhtoan' => ['required', 'string', 'max:255'],
            'hoatdong' => ['required'],
            'hinhanh' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg|max:2048'],
        ]);

        $orm = PhuongThucThanhToan::find($id);
        $orm->tenphuongthucthanhtoan = $request->tenphuongthucthanhtoan;
        $orm->hoatdong = $request->hoatdong;
        if ($request->hasFile('hinhanh')) {
            $file = $request->file('hinhanh');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/storage/app'), $filename);
            $orm->hinhanh = '/storage/app/' . $filename;
        }
        $orm->save();

        return redirect()->route('admin.thanhtoan.danhsach')->with('success', 'Cập nhật phương thức thanh toán thành công.');
    }
    public function getXoa($id)
    {
        $orm = PhuongThucThanhToan::find($id);
        $orm->delete();
        return redirect()->route('admin.thanhtoan.danhsach')->with('success', 'Xóa phương thức thanh toán thành công.');
    }
    public function getHoatDong($id)
    {
        $orm = PhuongThucThanhToan::find($id);
        $orm->hoatdong = !$orm->hoatdong;
        $orm->save();
        return redirect()->route('admin.thanhtoan.danhsach')->with('success', 'Cập nhật trạng thái phương thức thanh toán thành công.');
    }

}