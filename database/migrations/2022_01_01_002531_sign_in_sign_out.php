<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SignInSignOut extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('sign_in_sign_out')) {
            Schema::create('sign_in_sign_out', function (Blueprint $table) {
                $table->id();
                $table->integer('applicant_id');
                $table->enum('type', ['', 'in', 'out'])->default('');
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
        Schema::dropIfExists('sign_in_sign_out');
    }
}
