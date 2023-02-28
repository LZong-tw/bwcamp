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
        Schema::table('ceovcamp', function (Blueprint $table) {
            //
            $table->string('lrclass_level')->nullable()->after('group_priority3'); //廣論研討班別(選單)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ceovcamp', function (Blueprint $table) {
            //
            $table->dropColumn('lrclass_level');
        });
    }
};
