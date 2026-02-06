<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('utcamp', function (Blueprint $table) {
            //
            $table->string('invoice_type')->nullable()->after('workshop_credit_type');;      //發票類型
            $table->string('invoice_title')->nullable()->after('invoice_type');  //發票抬頭
            $table->string('taxid')->nullable()->after('invoice_title');;      //統編
            $table->renameColumn('workshop_credit_type', 'certificate_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('utcamp', function (Blueprint $table) {
            //
            $table->dropColumn('invoice_type');
            $table->dropColumn('invoice_title');
            $table->dropColumn('taxid');
            $table->renameColumn('certificate_type', 'workshop_credit_type');
        });
    }
};
