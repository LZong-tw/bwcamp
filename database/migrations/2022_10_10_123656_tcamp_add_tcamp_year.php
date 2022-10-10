<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TcampAddTcampYear extends Migration
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
            $table->boolean('is_attend_tcamp')->nullable()->after('never_attend_any_stay_over_tcamps'); //是否參加過tcamp            
            $table->integer('tcamp_year')->nullable()->after('is_attend_tcamp'); //參加tcamp年度(西元)
            $table->string('after_camp_available_day')->nullable()->after('interesting_complement'); //參加tcamp年度(西元)
            $table->boolean('never_attend_any_stay_over_tcamps')->nullable()->change(); //改成boolean
            $table->string('blisswisdom_type')->nullable()->change();
            $table->string('blisswisdom_type_complement')->nullable()->change();
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
            $table->dropColumn('is_attend_tcamp');
            $table->dropColumn('tcamp_year');
            $table->dropColumn('after_camp_available_day');
            $table->string('never_attend_any_stay_over_tcamps')->nullable()->change();
            $table->text('blisswisdom_type')->nullable()->change();
            $table->string('blisswisdom_type_complement',40)->nullable()->change();
        });
    }
}
