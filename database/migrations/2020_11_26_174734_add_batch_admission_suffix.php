<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatchAdmissionSuffix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batchs', function (Blueprint $table) {
            //
            $table->string('admission_suffix', 2)->nullable()->after('name'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batchs', function (Blueprint $table) {
            //
            $table->dropColumn('admission_suffix');
        });
    }
}
