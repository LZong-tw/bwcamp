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
        if (!Schema::hasTable('dates')) {
            Schema::create('dates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('applicant_id')->constrained('applicants');
                $table->foreignId('datetype_id')->constrained('datetypes');
                ;
                $table->date('date_time');
                $table->timestamps();
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
        Schema::dropIfExists('dates');
    }
};
