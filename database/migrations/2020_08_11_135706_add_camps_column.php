<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCampsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('camps', function (Blueprint $table) {
            $table->text('site_url')->nullable()->after('fullName');    // 營隊網站
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('camps', function (Blueprint $table) {
            $table->dropColumn('site_url');
        });
    }
}
