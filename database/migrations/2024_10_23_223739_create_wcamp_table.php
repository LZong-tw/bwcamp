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
        Schema::create('wcamp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->string('lrclass', 30)->nullable();  //廣論研討班別
            $table->string('unit')->nullable();         //服務單位
            $table->string('title')->nullable();        //職稱
            $table->text('learning_experiences')->nullable();  //學習狀況
            $table->text('volunteer_experiences')->nullable(); //護持狀況
            $table->text('speak_experiences')->nullable(); //講說經驗
            $table->text('character')->nullable();      //講說特質
            $table->text('potential')->nullable();      //潛力特質
            $table->text('comments')->nullable();      //備註說明
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
        Schema::dropIfExists('wcamp');
    }
};
