<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CheckinAddCompositeKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('check_in', function (Blueprint $table) {
            //
            $table->unique(['applicant_id', 'check_in_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('check_in', function (Blueprint $table) {
            //
            $table->dropUnique(['applicant_id', 'check_in_date']);
        });
    }
}
