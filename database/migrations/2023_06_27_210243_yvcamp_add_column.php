<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yvcamp', function (Blueprint $table) {
            //
            $table->text('self_intro')->nullable()->after('applicant_id');;      //自我介紹
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yvcamp', function (Blueprint $table) {
            //
            $table->dropColumn('self_intro');
        });
    }
};
