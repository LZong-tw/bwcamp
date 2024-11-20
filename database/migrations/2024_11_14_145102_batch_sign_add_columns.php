<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch_sign_availibilities', function (Blueprint $table) {
            //
            $table->string('timeslot_name')->nullable()->after('batch_id'); //時段名稱
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batch_sign_availibilities', function (Blueprint $table) {
            //
            $table->dropColumn('timeslot_name');
        });
    }
};
