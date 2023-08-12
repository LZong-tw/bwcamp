<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('traffic', function (Blueprint $table) {
            //
            $table->integer('cash')->after('deposit')->default(0);  //現金繳納
            $table->integer('sum')->after('cash')->default(0);      //deposit+cash
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('traffic', function (Blueprint $table) {
            //
            $table->dropColumn('cash');
            $table->dropColumn('sum');
        });
    }
};
