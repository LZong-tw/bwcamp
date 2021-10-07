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
            $table->string('workshop_credit_type')->after('has_license')->nullable();
            $table->string('never_attend_any_stay_over_tcamps')->after('workshop_credit_type')->nullable();
            $table->string('info_source')->after('never_attend_any_stay_over_tcamps')->nullable();
            $table->string('interesting')->after('info_source')->nullable();
            
        });
        Schema::table('applicants', function (Blueprint $table) {
            //
            $table->string('age_range')->after('birthday')->nullable();
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
            $table->dropColumn('never_attend_any_stay_over_tcamps');            
            $table->dropColumn('info_source');            
            $table->dropColumn('interesting');            
        });
        Schema::table('applicants', function (Blueprint $table) {
            //
            $table->dropColumn('age_range');
        });
    }
}
