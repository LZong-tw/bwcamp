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
        //
        Schema::table('applicants', function (Blueprint $table) {
            $table->renameColumn('group', 'group_legacy');
            $table->renameColumn('number', 'number_legacy');
            $table->integer('group_id')->nullable()->after('avatar');
            $table->integer('number_id')->nullable()->after('group_id');
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
            $table->renameColumn('group_legacy', 'group');
            $table->renameColumn('number_legacy', 'number');
            $table->dropColumn('group_id');
            $table->dropColumn('number_id');
        });
    }
};
