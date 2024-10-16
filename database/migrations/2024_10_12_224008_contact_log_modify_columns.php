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
        Schema::table('contact_log', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('takenby_id')->change();
            $table->renameColumn('takenby_id', 'user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_log', function (Blueprint $table) {
            //
        });
    }
};
