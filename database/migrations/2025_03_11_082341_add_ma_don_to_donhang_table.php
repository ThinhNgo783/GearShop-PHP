<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaDonToDonhangTable extends Migration
{
    public function up()
    {
        Schema::table('donhang', function (Blueprint $table) {
            if (!Schema::hasColumn('donhang', 'ma_don')) {
                $table->string('ma_don')->unique()->after('id');
            }
        });
    }

    public function down()
    {
        Schema::table('donhang', function (Blueprint $table) {
            if (Schema::hasColumn('donhang', 'ma_don')) {
                $table->dropColumn('ma_don');
            }
        });
    }
}