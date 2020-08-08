<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePhoneColumns extends Migration
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
            $table->string('phone_home', 25)->change();
            $table->string('phone_work', 25)->change();
            $table->string('emergency_phone_home', 25)->change();
            $table->string('emergency_phone_work', 25)->change();
            $table->string('introducer_phone', 25)->change();
        });
        Schema::table('ycamp', function (Blueprint $table) {
            $table->string('father_phone', 25)->change();
            $table->string('mother_phone', 25)->change();
            $table->string('agent_phone', 25)->change();
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
            $table->dropColumn('phone_home');
            $table->dropColumn('phone_work');
            $table->dropColumn('emergency_phone_home');
            $table->dropColumn('emergency_phone_work');
            $table->dropColumn('introducer_phone');
        });
        Schema::table('ycamp', function (Blueprint $table) {
            $table->dropColumn('father_phone');
            $table->dropColumn('mother_phone');
            $table->dropColumn('agent_phone');
        });
    }
}
