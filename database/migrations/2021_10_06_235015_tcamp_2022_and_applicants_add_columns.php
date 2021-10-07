<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tcamp2022AndApplicantsAddColumns extends Migration
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
            $table->addColumn('workshop_credit_type')->after('has_license')->nullable();
        });
        Schema::table('applicants', function (Blueprint $table) {
            //
            $table->addColumn('age_range')->after('birthday')->nullable();
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
            $table->dropColumn('workshop_credit_type');
        });
        Schema::table('applicants', function (Blueprint $table) {
            //
            $table->dropColumn('age_range');
        });
    }
}
