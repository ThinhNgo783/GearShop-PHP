<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanPhamReview extends Model
{
    protected $table = 'sanpham_reviews';

    protected $fillable = [
        'sanpham_id', 'nguoidung_id', 'rating', 'comment'
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
