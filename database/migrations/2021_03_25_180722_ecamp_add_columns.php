<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EcampAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecamp', function (Blueprint $table) {
            //
            $table->string('level', 20)->nullable()->after('title');
            $table->string('industry', 16)->nullable()->after('direct_managed_employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecamp', function (Blueprint $table) {
            //
            $table->dropColumn('level');
            $table->dropColumn('industry');
        });
    }
}
