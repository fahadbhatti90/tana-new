<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mgmt_user', function (Blueprint $table) {
            $table->smallIncrements('user_id');
            $table->string('username',64);
            $table->string('email')->unique();
            $table->string('password',255);
            $table->unsignedTinyInteger('is_active')->default('1');
            $table->index('user_id');
            $table->index('email');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mgmt_user');
    }
}
