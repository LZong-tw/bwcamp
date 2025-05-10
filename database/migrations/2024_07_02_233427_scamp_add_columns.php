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
        Schema::table('scamp', function (Blueprint $table) {
            //
            $table->string('participation_mode')->nullable()->after('is_allow_informed'); //得知管道
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scamp', function (Blueprint $table) {
            //
            $table->dropColumn('participation_mode');
        });
    }
};
