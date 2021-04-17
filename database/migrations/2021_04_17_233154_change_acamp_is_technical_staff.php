<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAcampIsTechnicalStaff extends Migration
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
            $table->renameColumn('is_technicalstaff', 'is_technical_staff');            
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
            $table->renameColumn('is_technical_staff', 'is_technicalstaff');
        });
    }
}
