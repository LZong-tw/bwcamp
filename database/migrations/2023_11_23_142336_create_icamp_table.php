<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icamp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->string('lrclass')->nullable();      //廣論研討班別
            $table->string('participation_mode')->nullable();  //正行報名
            $table->string('transportation_depart')->nullable();    //去程交通方式
            $table->string('transportation_back')->nullable();      //回程交通方式
            $table->string('special_needs')->nullable();      //特殊需求
            $table->string('questions')->nullable();        //問題
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('icamp');
    }
};
