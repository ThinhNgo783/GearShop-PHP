<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSanphamFavoritesTable extends Migration
{
    public function up()
    {
        Schema::create('sanpham_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sanpham_id')->constrained('sanpham')->onDelete('cascade');
            $table->foreignId('nguoidung_id')->constrained('nguoidung')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['sanpham_id', 'nguoidung_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sanpham_favorites');
    }
}
