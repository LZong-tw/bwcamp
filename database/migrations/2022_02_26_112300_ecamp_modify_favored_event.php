<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EcampModifyFavoredEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecamp', function (Blueprint $table) {
            //
            $table->string('favored_event', 120)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecamp', function (Blueprint $table) {
            //
            $table->string('favored_event', 60)->nullable()->change();
        });
    }
}
