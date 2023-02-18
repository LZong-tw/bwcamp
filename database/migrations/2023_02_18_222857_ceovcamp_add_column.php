<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('ceovcamp', function (Blueprint $table) {
            $table->string('capital_unit')->nullable()->after('capital');         //資本額單位
        });
    }
};
