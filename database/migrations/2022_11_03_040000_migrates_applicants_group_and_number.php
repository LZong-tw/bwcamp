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
        });
        Schema::table('applicants', function (Blueprint $table) {
            $table->renameColumn('number', 'number_legacy');
        });
        Schema::table('applicants', function (Blueprint $table) {
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
        });
        Schema::table('applicants', function (Blueprint $table) {
            $table->renameColumn('number_legacy', 'number');
        });
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn('group_id');
        });
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn('number_id');
        });
    }
};
