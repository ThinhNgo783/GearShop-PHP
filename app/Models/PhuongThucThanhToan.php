<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhuongThucThanhToan extends Model
{
    protected $table = 'phuongthucthanhtoan';

    protected $fillable = [
        'tenphuongthucthanhtoan',
        'hoatdong',
        'hinhanh',
    ];

    const hoatdong = [
        '1' => 'Hoạt động',
        '0' => 'Ngừng hoạt động',
    ];
}
