<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mgmt_user_role', function (Blueprint $table) {
            $table->unsignedSmallInteger('fk_user_id');
            $table->unsignedSmallInteger('fk_role_id');
            $table->foreign('fk_user_id')->references('user_id')->on('mgmt_user')->onDelete('cascade');
            $table->foreign('fk_role_id')->references('role_id')->on('mgmt_role')->onDelete('cascade');
            $table->primary(['fk_user_id', 'fk_role_id'], 'ur_primary_key');
            $table->index('fk_user_id');
            $table->index('fk_role_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mgmt_user_role');
    }
}
