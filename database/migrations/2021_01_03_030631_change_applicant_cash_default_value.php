<?php

use App\Models\Applicant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeApplicantCashDefaultValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Applicant::whereNull('fee')->update(["fee" => 0]);
        Applicant::whereNull('deposit')->update(["deposit" => 0]);
        Schema::table('applicants', function (Blueprint $table) {
            //
            $table->integer('fee')->nullable(false)->default(0)->change();
            $table->integer('deposit')->nullable(false)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            //
            $table->integer('fee')->nullable()->change();
            $table->integer('deposit')->nullable()->change();
        });
    }
}
