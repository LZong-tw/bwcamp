<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BatchAddIsAppliableSwitch extends Migration
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
            $table->boolean('is_appliable')->default(true)->after('batch_end');
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
            $table->dropColumn('is_appliable');
        });
    }
}
