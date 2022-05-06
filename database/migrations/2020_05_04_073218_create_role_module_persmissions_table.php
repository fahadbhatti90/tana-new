<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleModulePersmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mgmt_role_module_permission', function (Blueprint $table) {
            $table->smallIncrements('auth_id');
            $table->unsignedSmallInteger('fk_role_id');
            $table->unsignedSmallInteger('fk_permission_id');
            $table->unsignedSmallInteger('fk_module_id');
            $table->foreign('fk_role_id')->references('role_id')->on('mgmt_role')->onDelete('cascade');
            $table->foreign('fk_permission_id')->references('permission_id')->on('mgmt_permission')->onDelete('cascade');
            $table->foreign('fk_module_id')->references('module_id')->on('mgmt_module')->onDelete('cascade');
            $table->index('fk_role_id');
            $table->index('fk_permission_id');
            $table->index('fk_module_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mgmt_role_module_permission');
    }
}
