<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApplicantAddRegionYcampRemoveRegion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('region', 8)->nullable()->after('is_admitted');
        });
        Schema::table('ycamp', function (Blueprint $table) {
            $table->dropColumn('region');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn('region');
        });
        Schema::table('ycamp', function (Blueprint $table) {
            $table->string('region', 8)->nullable()->after('school_location');
        });
    }
}
