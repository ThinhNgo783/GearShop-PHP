<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiachiToNguoidungTable extends Migration
{
    public function up(): void
    {
        Schema::table('nguoidung', function (Blueprint $table) {
            if (!Schema::hasColumn('nguoidung', 'diachi')) {
                $table->string('diachi')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('nguoidung', function (Blueprint $table) {
            if (Schema::hasColumn('nguoidung', 'diachi')) {
                $table->dropColumn('diachi');
            }
        });
    }
}
