<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SignInSignOutAddReference extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sign_in_sign_out', function (Blueprint $table) {
            //
            $table->integer('availability_id')->nullable()->after('applicant_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sign_in_sign_out', function (Blueprint $table) {
            //
            $table->dropColumn('availability_id');
        });
    }
}
