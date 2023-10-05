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
        Schema::table('tcamp', function (Blueprint $table) {
            //
            $table->string('transportation_depart')->nullable()->after('blisswisdom_type_complement');;      //交通方式
            $table->string('transportation_back')->nullable()->after('transportation_depart');;      //交通方式
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tcamp', function (Blueprint $table) {
            //
            $table->dropColumn('transportation_depart');
            $table->dropColumn('transportation_back');
        });
    }
};
