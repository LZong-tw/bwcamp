<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameApplicantsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('applicants', function (Blueprint $table) {
            $table->renameColumn('participated', 'introducer_participated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('applicants', function (Blueprint $table) {
            $table->renameColumn('introducer_participated', 'participated');
        });
    }
}
