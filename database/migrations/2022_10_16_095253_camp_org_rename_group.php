<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CamporgRenameGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('camp_org', function (Blueprint $table) {
            //
            $table->renameColumn('group', 'section');
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
            $table->renameColumn('section', 'group');
        });
    }
}
