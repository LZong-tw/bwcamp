<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\SmallIntType;

return new class extends Migration {
    public function up()
    {
        if (!Type::hasType('integer')) {
            Type::addType('integer', SmallIntType::class);
        }
        Schema::table('applicants', function (Blueprint $table) {
            $table->smallInteger('is_attend')->change();
        });
    }
};
