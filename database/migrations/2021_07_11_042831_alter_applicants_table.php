<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            //                
            $table->boolean('is_attend')->nullable()->default(null)->change();
        });
        \DB::table('applicants')->where('is_attend', 0)->update(['is_attend' => null]);
        \DB::table('applicants')->whereIn('id', [
            3386, 3917, 5605, 
            3562, 5741, 
            3211, 3241, 3368, 3728, 4217, 
            5151, 5155, 5185, 
            4324, 5687, 
            3319, 3882, 5974, 
            5624])->update(['is_attend' => 0]);
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
            $table->boolean('is_attend')->default(0)->change();
        });
    }
}
