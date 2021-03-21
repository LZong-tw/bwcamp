<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CampsAddModifyingDeadline extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('camps', function (Blueprint $table) {
            //
            $table->date('modifying_deadline')->nullable()->after('fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('camps', function (Blueprint $table) {
            //
            $table->dropColumn('modifying_deadline');
        });
    }
}
