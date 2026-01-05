<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SanPham extends Model
{
    protected $table = 'sanpham';

    protected $fillable = [
        'loaisanpham_id',
        'hangsanxuat_id',
        'tensanpham',
        'tensanpham_slug',
        'soluong',
        'gianhap',
        'dongia',
        'hinhanh',
        'motasanpham',
        'gioithieusanpham',
    ];

    public function loaiSanPham(): BelongsTo
    {
        return $this->belongsTo(LoaiSanPham::class, 'loaisanpham_id', 'id');
    }

    public function hangSanXuat(): BelongsTo
    {
        return $this->belongsTo(HangSanXuat::class, 'hangsanxuat_id', 'id');
    }

    public function donHangChiTiet(): HasMany
    {
        return $this->hasMany(DonHang_ChiTiet::class, 'sanpham_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(SanPhamReview::class, 'sanpham_id', 'id');
    }

    public function favorites()
    {
        return $this->hasMany(SanPhamFavorite::class, 'sanpham_id', 'id');
    }
}