<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('phuongthucthanhtoan', function (Blueprint $table) {
            $table->id();
            $table->string('tenphuongthucthanhtoan')->unique();
            $table->boolean('hoatdong')->default(1); // Thêm trường trạng thái
            $table->string('hinhanh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phuongthucthanhtoan');
    }
};
