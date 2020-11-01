<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BlisswisdomTypeComplementAdded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tcamp', function (Blueprint $table) {
            //
            $table->string('blisswisdom_type_complement', 40)->nullable()->after('blisswisdom_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tcamp', function (Blueprint $table) {
            //
            $table->dropColumn('blisswisdom_type_complement');
        });
    }
}
