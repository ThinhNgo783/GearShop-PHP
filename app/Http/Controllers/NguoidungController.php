<?php

namespace App\Http\Controllers;

use App\Models\Nguoidung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Models\DiaChi;


class NguoidungController extends Controller
{
    public function getDanhSach()
    {
        $nguoidung = Nguoidung::all();
        return view('admin.nguoidung.danhsach', compact('nguoidung'));
    }

    public function getThem()
    {
        return view('admin.nguoidung.them');
    }

    public function postThem(Request $request)
    {
        // Kiểm tra
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:nguoidung'],
            'role' => ['required'],
            'password' => ['required', 'min:4', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:15'], // Thêm kiểm tra cho số điện thoại
        ]);

        $orm = new Nguoidung();
        $orm->name = $request->name;
        $orm->username = Str::before($request->email, '@');
        $orm->email = $request->email;
        $orm->password = Hash::make($request->password);
        $orm->role = $request->role;
        $orm->phone = $request->phone; // Lưu số điện thoại
        $orm->save();

        // Sau khi thêm thành công thì tự động chuyển về trang danh sách
        return redirect()->route('admin.nguoidung');
    }

    public function getSua($id)
    {
        $nguoidung = Nguoidung::find($id);
        return view('admin.nguoidung.sua', compact('nguoidung'));
    }

    public function postSua(Request $request, $id)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'phone'       => 'nullable|string|max:15',
            'diachi' => 'nullable|string|max:255',
            'role'        => 'required',
            'password'    => 'nullable|min:6|confirmed'
        ]);

        $nguoidung = Nguoidung::findOrFail($id);
        $nguoidung->name = $validated['name'];
        $nguoidung->email = $validated['email'];
        $nguoidung->phone = $validated['phone'];
        $nguoidung->role = $validated['role'];
        if (!empty($validated['password'])) {
            $nguoidung->password = Hash::make($validated['password']);
        }
        $nguoidung->save();


        return redirect()->route('admin.nguoidung')
            ->with('success', 'Thông tin người dùng đã được cập nhật.');
    }

    public function getXoa($id)
    {
        $orm = Nguoidung::find($id);
        $orm->delete();

        // Sau khi xóa thành công thì tự động chuyển về trang danh sách
        return redirect()->route('admin.nguoidung');
    }


}
