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
        Schema::create('evcamp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->string('group_priority1', 16)->nullable();  //報名組別志願1           
            $table->string('group_priority2', 16)->nullable();  //報名組別志願2           
            $table->string('group_priority3', 16)->nullable();  //報名組別志願3          
            $table->string('lrclass', 30)->nullable();          //廣論研討班別   
            $table->text('cadre_experiences')->nullable();      //班級護持記錄
            $table->text('volunteer_experiences')->nullable();  //義工護持記錄
            $table->string('transport', 40)->nullable();        //交通方式
            $table->string('transport_other', 30)->nullable();  //交通方式:自填         
            $table->string('expertise', 60)->nullable();        //專長
            $table->string('expertise_other', 30)->nullable();  //專長：自填
            $table->string('language', 40)->nullable();         //語言  
            $table->string('language_other', 30)->nullable();   //語言:自填        
            $table->string('unit', 40)->nullable();             //公司名稱
            $table->string('industry', 30)->nullable();         //產業別
            $table->string('title', 40)->nullable();            //職稱
            $table->string('job_property', 30)->nullable();     //工作性質
            $table->integer('employees')->nullable();           //員工總數
            $table->integer('direct_managed_employees')->nullable();    //直屬員工人數
            $table->integer('capital')->nullable();             //資本額
            $table->string('capital_unit')->nullable();         //資本額單位
            $table->string('org_type', 30)->nullable();         //公司性質
            $table->string('years_operation', 16)->nullable();  //公司年限
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
        Schema::dropIfExists('evcamp');
    }
};
