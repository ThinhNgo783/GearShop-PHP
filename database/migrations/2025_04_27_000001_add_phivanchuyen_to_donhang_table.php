<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhivanchuyenToDonhangTable extends Migration
{
    public function up()
    {
        Schema::table('donhang', function (Blueprint $table) {
            if (!Schema::hasColumn('donhang', 'phivanchuyen')) {
                $table->integer('phivanchuyen')->nullable()->comment('Phí vận chuyển');
            }
        });
    }

    public function down()
    {
        Schema::table('donhang', function (Blueprint $table) {
            if (Schema::hasColumn('donhang', 'phivanchuyen')) {
                $table->dropColumn('phivanchuyen');
            }
        });
    }
}
