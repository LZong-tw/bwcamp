<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('english_name')->change();
            $table->string('nationality')->change();
            $table->string('mobile')->change();
            $table->string('phone_home')->change();
            $table->string('phone_work')->change();
            $table->string('fax')->change();
            $table->string('line')->change();
            $table->string('wechat')->change();
            $table->string('email')->change();
            $table->string('emergency_name')->change();
            $table->string('emergency_fax')->change();
            $table->string('emergency_mobile')->change();
            $table->string('emergency_relationship')->change();
            $table->string('emergency_phone_home')->change();
            $table->string('emergency_phone_work')->change();
            $table->string('introducer_email')->change();
            $table->string('introducer_name')->change();
            $table->string('introducer_phone')->change();
            $table->string('introducer_relationship')->change();
        });
    }
};
