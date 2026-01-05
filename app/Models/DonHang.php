<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonHang extends Model
{
    protected $table = 'donhang';

    protected $fillable = [
        'ma_don',
        'nguoidung_id',
        'dienthoaigiaohang',
        'diachigiaohang',
        'tinhtrang_id',
        'tongtien',
    ];

    public function nguoidung(): BelongsTo
    {
        return $this->belongsTo(Nguoidung::class, 'nguoidung_id', 'id');
    }

    public function tinhtrang(): BelongsTo
    {
        return $this->belongsTo(TinhTrang::class, 'tinhtrang_id', 'id');
    }

    public function phuongthucthanhtoan()
    {
        return $this->belongsTo(PhuongThucThanhToan::class, 'phuongthucthanhtoan_id');
    }

    public function donhang_chitiet(): HasMany
    {
        return $this->hasMany(DonHang_ChiTiet::class, 'donhang_id', 'id');
    }

    /**
     * Recalculate the total price of the order by summing the product of quantity and price of each order detail.
     * Update the tongtien field and save the model.
     */
    public function recalculateTotalPrice()
    {
        $total = $this->donhang_chitiet->sum(function ($item) {
            return $item->soluongban * $item->dongiaban;
        });

        // Add VAT (10%)
        $vatRate = 0.10;
        $totalWithVat = $total + ($total * $vatRate);

        // Add shipping fee if any
        $totalWithVatAndShipping = $totalWithVat + ($this->phivanchuyen ?? 0);

        $this->tongtien = $totalWithVatAndShipping;
        $this->save();
    }
}
