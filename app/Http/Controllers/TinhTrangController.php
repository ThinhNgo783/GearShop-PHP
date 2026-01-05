<?php

namespace App\Http\Controllers;

use App\Models\TinhTrang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TinhTrangController extends Controller
{
    public function getDanhSach()
    {
        $tinhtrangs = TinhTrang::all();
        return view('admin.tinhtrang.danhsach', compact('tinhtrangs'));
    }

    public function getThem()
    {
        return view('admin.tinhtrang.them');
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'tinhtrang' => ['required', 'string', 'max:255', 'unique:tinhtrang'],
        ]);
        $orm = new TinhTrang();
        $orm->tinhtrang = $request->tinhtrang;
        $orm->save();

        return redirect()->route('admin.tinhtrang.danhsach')->with('success', 'Thêm tình trạng thành công.');
    }

    public function getSua($id)
    {
        $tinhtrang = TinhTrang::find($id);
        return view('admin.tinhtrang.sua', compact('tinhtrang'));
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'tinhtrang' => 'required|string|max:255',
        ]);

        $tinhtrang = TinhTrang::find($id);
        $tinhtrang->tinhtrang = $request->input('tinhtrang');
        $tinhtrang->save();

        return redirect()->route('admin.tinhtrang.danhsach')->with('success', 'Cập nhật tình trạng thành công.');
    }

    public function getXoa($id)
    {
        $orm = TinhTrang::find($id);
        $orm->delete();
        return redirect()->route('admin.tinhtrang.danhsach')->with('success', 'Xóa tình trạng thành công.');
    }
}