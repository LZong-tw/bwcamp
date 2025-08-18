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
        Schema::create('check_out', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->foreignId('checker_id')->nullable()->constrained('users');
            $table->dateTime('check_out_datetime'); //提前離營時間
            $table->text('notes')->nullable();  //說明提前離營理由，if any
            $table->timestamps();
            $table->unique(['applicant_id', 'check_out_datetime']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('check_out');
    }
};
