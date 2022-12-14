<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('camp_org', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('position');
            $table->string('description')->nullable()->after('display_name');
        });
    }
};
