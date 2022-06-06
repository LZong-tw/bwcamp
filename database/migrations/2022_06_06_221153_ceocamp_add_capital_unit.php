<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CeocampAddCapitalUnit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ceocamp', function (Blueprint $table) {
            //
            $table->string('capital_unit')->nullable()->after('capital');   //unit for capital
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ceocamp', function (Blueprint $table) {
            //
            $table->dropColumn('capital_unit');
        });
    }
}
