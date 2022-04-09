<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AcampAddParticipationMode extends Migration
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
            $table->string('participation_mode', 16)->nullable()->after('applicant_id');    //參加地點
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
            $table->dropColumn('participation_mode');
        });
    }
}
