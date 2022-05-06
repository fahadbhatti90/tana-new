<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMgmtUserModulePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mgmt_user_module_permission', function (Blueprint $table) {
            $table->smallIncrements('auth_id');
            $table->unsignedSmallInteger('fk_user_id');
            $table->unsignedSmallInteger('fk_permission_id');
            $table->unsignedSmallInteger('fk_module_id');
            $table->index('fk_user_id');
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
        Schema::dropIfExists('mgmt_user_module_permission');
    }
}
