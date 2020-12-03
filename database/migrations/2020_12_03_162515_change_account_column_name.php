<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAccountColumnName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounting_scsb', function (Blueprint $table) {
            //
            $table->renameColumn('creddited_at', 'creditted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting_scsb', function (Blueprint $table) {
            //
            $table->renameColumn('creditted_at', 'creddited_at');
        });
    }
}
