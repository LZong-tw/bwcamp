<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('camp_vcamp', function (Blueprint $table) {
            $table->id();
            $table->integer('camp_id')->unsigned();
            $table->integer('vcamp_id')->unsigned();
            $table->timestamps();
        });
    }
};
