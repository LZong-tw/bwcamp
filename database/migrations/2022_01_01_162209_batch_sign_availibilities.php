<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BatchSignAvailibilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(!Schema::hasTable("batch_sign_availibilities")) {
            Schema::create("batch_sign_availibilities", function(Blueprint $table) {
                $table->id();
                $table->integer('batch_id');
                $table->timestamp('start');
                $table->timestamp('end');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists("batch_sign_availibilities");
    }
}
