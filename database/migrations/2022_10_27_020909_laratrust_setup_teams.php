<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class LaratrustSetupTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for storing teams
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::table('role_user', function (Blueprint $table) {
            // Add team_id column
            $table->unsignedInteger('team_id')->nullable()->after('user_type');

            // Create a unique key
            $table->unique(['user_id', 'role_id', 'user_type', 'team_id']);
        });

        Schema::table('permission_user', function (Blueprint $table) {
            // Add team_id column
            $table->unsignedInteger('team_id')->nullable();

            $table->foreign('team_id')->references('id')->on('teams')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['user_id', 'permission_id', 'user_type', 'team_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
        });

        Schema::table('permission_user', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
        });

        Schema::dropIfExists('teams');
    }
}
