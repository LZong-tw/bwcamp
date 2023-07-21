<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('english_name', 255)->change();
            $table->string('nationality', 255)->change();
            $table->string('mobile', 255)->change();
            $table->string('phone_home', 255)->change();
            $table->string('phone_work', 255)->change();
            $table->string('fax', 255)->change();
            $table->string('line', 255)->change();
            $table->string('wechat', 255)->change();
            $table->string('email', 255)->change();
            $table->string('emergency_name', 255)->change();
            $table->string('emergency_fax', 255)->change();
            $table->string('emergency_mobile', 255)->change();
            $table->string('emergency_relationship', 255)->change();
            $table->string('emergency_phone_home', 255)->change();
            $table->string('emergency_phone_work', 255)->change();
            $table->string('introducer_email', 255)->change();
            $table->string('introducer_name', 255)->change();
            $table->string('introducer_phone', 255)->change();
            $table->string('introducer_relationship', 255)->change();
        });
    }
};
