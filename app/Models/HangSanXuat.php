<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HangSanXuat extends Model
{
    protected $table = 'hangsanxuat';

    protected $fillable = [
        'tenhang',
        'tenhang_slug',
        'hinhanh',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->tenhang_slug = Str::slug($model->tenhang);
        });
    }
}