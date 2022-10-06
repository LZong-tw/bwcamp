<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CampsAddAccessDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('camps', function (Blueprint $table) {
            //
            $table->string('year')->nullable()->after('table'); //年度
            $table->string('mode')->nullable()->after('variant'); //型態：實體、線上
            $table->date('access_start')->nullable()->after('mode');   //權限開放日
            $table->date('access_end')->nullable()->after('access_start');  //權限關閉日
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('camps', function (Blueprint $table) {
            //
            $table->dropColumn('year');
            $table->dropColumn('mode');
            $table->dropColumn('access_start');
            $table->dropColumn('access_end');
        });
    }
}
