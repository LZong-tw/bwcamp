<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HcampRenameColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hcamp', function (Blueprint $table) {
            //
            $table->renameColumn('traffic', 'traffic_depart');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hcamp', function (Blueprint $table) {
            //
            $table->renameColumn('traffic_depart', 'traffic');
        });
    }
}
