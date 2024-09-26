<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnotherOptimizationIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('camp_org', function (Blueprint $table) {
            $table->index(['id', 'camp_id']);
        });

        Schema::table('applicants', function (Blueprint $table) {
            $table->index(['id', 'batch_id']);
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
            $table->dropIndex(['id', 'camp_id']);
        });

        Schema::table('applicants', function (Blueprint $table) {
            $table->dropIndex(['id', 'batch_id']);
        });
    }
}
