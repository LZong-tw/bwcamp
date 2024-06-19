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
        Schema::create('scamp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->string('unit')->nullable();         //服務單位(全名)
            $table->string('address_work')->nullable(); //服務單位地址
            $table->string('department')->nullable();   //服務部門
            $table->string('title')->nullable();        //職稱
            $table->integer('seniority')->nullable();    //服務年資
            $table->text('expectation')->nullable();  //對本次課程的期待
            $table->string('is_allow_informed')->nullable();    //是否願意收到後續相關課程資訊
            $table->string('exam_format')->nullable();  //考證方式
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
        Schema::dropIfExists('scamp');
    }
};
