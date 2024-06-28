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
        Schema::table('scamp', function (Blueprint $table) {
            //
            $table->string('way')->nullable()->after('seniority'); //得知管道
            $table->string('way_other')->nullable()->after('way'); //得知管道：自填
            $table->integer('last5')->nullable()->after('exam_format'); //帳號後5碼
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scamp', function (Blueprint $table) {
            //
            $table->dropColumn('way');
            $table->dropColumn('way_other');
            $table->dropColumn('last5');
        });
    }
};
