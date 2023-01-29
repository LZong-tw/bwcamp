<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('camp_org', function (Blueprint $table) {
            $table->string('name')->nullable()->comment('與套件相容性有關');
        });
    }
};
