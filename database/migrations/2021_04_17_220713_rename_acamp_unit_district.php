<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameAcampUnitDistrict extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acamp', function (Blueprint $table) {
            //
            $table->renameColumn('unit_district', 'unit_subarea');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acamp', function (Blueprint $table) {
            //
            $table->renameColumn('unit_subarea', 'unit_district');
        });
    }
}
