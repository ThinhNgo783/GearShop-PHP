<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TinhTrang extends Model
{
    protected $table = 'tinhtrang';

    public function DonHang(): HasMany
        {
            return $this->hasMany(DonHang::class, 'tinhtrang_id', 'id');
        }
}
