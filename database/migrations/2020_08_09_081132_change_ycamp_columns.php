<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeYcampColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('ycamp', function (Blueprint $table) {
            $table->string('way', 30)->change();
            $table->string('blisswisdom_type', 60)->change();
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
        Schema::table('ycamp', function (Blueprint $table) {
            $table->string('way', 10)->change();
            $table->string('blisswisdom_type', 20)->change();
        });
    }
}
