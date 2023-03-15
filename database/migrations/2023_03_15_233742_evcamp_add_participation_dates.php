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
        Schema::table('evcamp', function (Blueprint $table) {
            //
            $table->string('participation_dates')->nullable()->after('language_other'); //可護持日期
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evcamp', function (Blueprint $table) {
            //
            $table->dropColumn('participation_dates');
        });
    }
};
