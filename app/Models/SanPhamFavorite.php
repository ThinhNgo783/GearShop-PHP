<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanPhamFavorite extends Model
{
    protected $table = 'sanpham_favorites';

    protected $fillable = [
        'sanpham_id', 'nguoidung_id'
    ];

    public function sanpham()
    {
        return $this->belongsTo(SanPham::class, 'sanpham_id', 'id');
    }

    public function nguoidung()
    {
        return $this->belongsTo(Nguoidung::class, 'nguoidung_id', 'id');
    }
}
