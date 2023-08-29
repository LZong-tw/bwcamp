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
        Schema::create('actvcamp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->string('lrclass_level')->nullable();    //廣論研討班別(選單)
            $table->string('lrclass')->nullable();          //廣論研討班別
            $table->string('transportation')->nullable();   //交通方式
            $table->text('self_intro')->nullable();         //自我介紹
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
        Schema::dropIfExists('actvcamp');
    }
};
