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
        Schema::table('icamp', function (Blueprint $table) {
            //
            $table->dropColumn('special_needs');    //特殊需求
            $table->integer('passport_expiry_year')->nullable()->after('lrclass');  //護照到期年
            $table->integer('passport_expiry_month')->nullable()->after('passport_expiry_year');    //護照到期月
            $table->integer('passport_expiry_day')->nullable()->after('passport_expiry_month'); //護照到期日
            $table->string('transportation_back_location')->nullable()->after('transportation_back'); //住宿需求
            $table->text('acommodation_needs')->nullable()->after('transportation_back_location'); //住宿需求
            $table->text('dietary_needs')->nullable()->after('acommodation_needs'); //飲食需求
            $table->text('other_needs')->nullable()->after('diatery_needs');      //其它需求
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('icamp', function (Blueprint $table) {
            //
            $table->string('special_needs')->nullable()->after('other_needs');      //特殊需求
            $table->dropColumn('passport_expiry_year');
            $table->dropColumn('passport_expiry_month');
            $table->dropColumn('passport_expiry_day');
            $table->dropColumn('transportation_back_location');
            $table->dropColumn('acommodation_needs');
            $table->dropColumn('dietary_needs');
            $table->dropColumn('other_needs');
        });
    }
};
