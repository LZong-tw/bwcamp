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
        Schema::table('actcamp', function (Blueprint $table) {
            //
            $table->string('lrclass_year')->nullable()->after('category'); //廣論班年份
            $table->string('lrclass_number')->nullable()->after('lrclass_year'); //廣論班號
            $table->integer('participants')->nullable()->after('transportation'); //參加人數
            $table->text('children_ages')->nullable()->after('participants'); //小孩年紀
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('actcamp', function (Blueprint $table) {
            //
            $table->dropColumn('lrclass_year');
            $table->dropColumn('lrclass_number');
            $table->dropColumn('participants');
            $table->dropColumn('children_ages');
        });
    }
};
