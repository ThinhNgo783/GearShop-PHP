<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonHang_ChiTiet extends Model
{
    protected $table = 'donhang_chitiet';

    protected $fillable = [
        'donhang_id',
        'sanpham_id',
        'soluongban',
        'dongiaban',
    ];

    public function sanpham(): BelongsTo
    {
        return $this->belongsTo(SanPham::class, 'sanpham_id', 'id');
    }

    public function donhang(): BelongsTo
    {
        return $this->belongsTo(DonHang::class, 'donhang_id', 'id');
    }
}