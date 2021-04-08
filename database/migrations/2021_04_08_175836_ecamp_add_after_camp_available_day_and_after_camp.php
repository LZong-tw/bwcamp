<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EcampAddAfterCampAvailableDayAndAfterCamp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecamp', function (Blueprint $table) {
            //
            $table->string('after_camp_available_day', 40)->nullable()->after('industry');
            $table->string('favored_event', 60)->nullable()->after('after_camp_available_day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecamp', function (Blueprint $table) {
            //
            $table->dropColumn('after_camp_available_day');
            $table->dropColumn('favored_event');
        });
    }
}
