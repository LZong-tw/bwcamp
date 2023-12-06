<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('icamp', function (Blueprint $table) {
            //
            $table->string('participation_dates')->nullable()->after('participation_mode'); //參加日期(單日)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('icamp', function (Blueprint $table) {
            //
            $table->dropColumn('participation_dates');
        });
    }
};
