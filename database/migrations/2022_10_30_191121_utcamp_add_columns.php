<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UtcampAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('utcamp', function (Blueprint $table) {
            //
            $table->string('workshop_credit_type')->nullable()->after('department');
            $table->dropColumn('position_complement');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('utcamp', function (Blueprint $table) {
            //
            $table->dropColumn('workshop_credit_type');
            $table->string('position_complement')->nullable()->after('position');
        });
    }
}
