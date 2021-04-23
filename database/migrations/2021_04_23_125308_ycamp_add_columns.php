<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class YcampAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ycamp', function (Blueprint $table) {
            //
            $table->string('blisswisdom_type_other', 60)->nullable()->after('blisswisdom_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ycamp', function (Blueprint $table) {
            //
            $table->dropColumn('blisswisdom_type_other', 60);
        });
    }
}
