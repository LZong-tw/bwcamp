<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AcampAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acamp', function (Blueprint $table) {
            //
            $table->string('motivation', 40)->nullable()->change();
            $table->string('motivation_other', 30)->nullable()->after('motivation');
            $table->string('blisswisdom_type_other', 30)->nullable()->after('blisswisdom_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acamp', function (Blueprint $table) {
            //
            $table->string('motivation', 20)->nullable()->change();
            $table->dropColumn('motivation_other', 30);
            $table->dropColumn('blisswisdom_type_other', 30);
        });
    }
}
