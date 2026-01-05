<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneToNguoidungTable extends Migration
{
    public function up(): void
    {
        Schema::table('nguoidung', function (Blueprint $table) {
            if (!Schema::hasColumn('nguoidung', 'phone')) {
                $table->string('phone')->nullable()->after('diachi'); // điều chỉnh vị trí sau cột phù hợp
            }
        });
    }

    public function down(): void
    {
        Schema::table('nguoidung', function (Blueprint $table) {
            if (Schema::hasColumn('nguoidung', 'phone')) {
                $table->dropColumn('phone');
            }
        });
    }
}