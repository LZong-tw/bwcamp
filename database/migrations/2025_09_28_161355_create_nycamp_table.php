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
        Schema::create('nycamp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->string('chinese_first_name')->nullable();   //applicant_name = chinese_last_name + chinese_first_name
            $table->string('chinese_last_name')->nullable();
            $table->string('english_last_name')->nullable();    //english_first_name => applicant->english_name
            $table->string('language')->nullable();
            $table->string('addr_city')->nullable();
            $table->string('addr_state')->nullable();
            $table->string('addr_country')->nullable();
            $table->string('school')->nullable();
            $table->string('department')->nullable();
            $table->string('grade')->nullable();
            $table->string('unit')->nullable();
            $table->string('title')->nullable();
            $table->text('dietary_needs')->nullable();
            $table->text('other_needs')->nullable();
            $table->text('accommodation_needs')->nullable();
            $table->text('motivation')->nullable();
            $table->string('info_source')->nullable();
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
        Schema::dropIfExists('nycamp');
    }
};
