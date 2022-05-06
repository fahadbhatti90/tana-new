<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMgmtUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mgmt_user_profile', function (Blueprint $table) {
            $table->smallIncrements('profile_id');
            $table->string('profile_mode', 64)->nullable();
            $table->unsignedSmallInteger('fk_user_id');
            $table->foreign('fk_user_id')->references('user_id')->on('mgmt_user')->onDelete('cascade');
            $table->unique(['fk_user_id']);
            $table->index('fk_user_id');
            $table->index('profile_id');
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
        Schema::dropIfExists('mgmt_user_profile');
    }
}
