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
        Schema::table('camp_org', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('region_id')->nullable()->after('batch_id');
            $table->unsignedBigInteger('order')->nullable()->after('prev_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('camp_org', function (Blueprint $table) {
            //
            $table->dropColumn('region_id');
            $table->dropColumn('order');
        });
    }
};
