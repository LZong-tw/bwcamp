<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('camp_org', function (Blueprint $table) {
            $table->unsignedBigInteger('prev_id')->nullable()->after('position');
            $table->unsignedBigInteger('next_id')->nullable()->after('position');
        });
    }
};
