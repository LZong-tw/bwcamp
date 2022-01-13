<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SignAvalibilityAddType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch_sign_availibilities', function (Blueprint $table) {
            //
            $table->string('type')->after('end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batch_sign_availibilities', function (Blueprint $table) {
            //
            $table->dropColumn('type');
        });
    }
}
