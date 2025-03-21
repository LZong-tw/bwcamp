<?php

use Google\Service\AIPlatformNotebooks\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acamp', function (Blueprint $table) {
            //
            $table->boolean('is_student')->nullable()->after('is_technical_staff'); //身分
            $table->string('class_county')->nullable()->after('class_location'); //上課地點
            $table->string('class_subarea')->nullable()->after('class_county'); //上課地點
            $table->string('county')->nullable()->after('class_subarea');   //居住地點
            $table->string('subarea')->nullable()->after('county');         //居住地點
            $table->string('participation_mode', 255)->change();
            $table->string('unit', 255)->change();
            $table->string('unit_county', 255)->change();
            $table->string('unit_subarea', 255)->change();
            $table->string('industry', 255)->change();
            $table->string('title', 255)->change();
            $table->string('education', 255)->change();
            $table->string('job_property', 255)->change();
            $table->string('class_location', 255)->change();
            $table->string('way', 255)->change();
            $table->string('belief', 255)->change();
            $table->string('motivation', 255)->change();
            $table->string('motivation_other', 255)->change();
            $table->string('blisswisdom_type_other', 255)->change();
            $table->string('agent_name', 255)->change();
            $table->string('agent_phone', 255)->change();
            $table->string('agent_relationship', 255)->change();
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
            $table->dropColumn('is_student');
            $table->dropColumn('class_county');
            $table->dropColumn('class_subarea');
            $table->dropColumn('county');
            $table->dropColumn('subarea');
        });
    }
};
