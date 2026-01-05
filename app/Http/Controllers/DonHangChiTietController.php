<?php

namespace App\Http\Controllers;

use App\Models\DonHang_ChiTiet;
use App\Models\DonHang;
use Illuminate\Http\Request;
class DonHangChiTietController extends Controller
{
    public function getChiTiet($id)
    {
        $donhang = DonHang::with(['donhang_chitiet.sanpham'])->find($id);
        if (!$donhang) {
            return response()->json(['error' => 'Đơn hàng không tồn tại'], 404);
        }
        return view('user.chitietdonhang', compact('donhang'));
    }

}