<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('org_user', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('org_id');
            $table->timestamps();
        });

        Schema::create('org_permission', function (Blueprint $table) {
            $table->id();
            $table->integer('org_id');
            $table->integer('permission_id');
            $table->timestamps();
        });
    }
};
