<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BatchesColumnsAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batchs', function (Blueprint $table) {
            //
            $table->string('locationName', 40)->nullable()->after('batch_end');
            $table->string('location', 100)->nullable()->after('locationName');
            $table->date('check_in_day')->nullable()->after('location');
            $table->string('tel', 20)->nullable()->after('check_in_day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batchs', function (Blueprint $table) {
            //
            $table->dropColumn('locationName');
            $table->dropColumn('location');
            $table->dropColumn('check_in_day');
            $table->dropColumn('tel');
        });
    }
}
