<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMgmtAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mgmt_account', function (Blueprint $table) {
            $table->smallIncrements('account_id');
            $table->unsignedSmallInteger('fk_user_id');
            $table->unsignedSmallInteger('fk_brand_id');
            $table->foreign('fk_user_id')->references('user_id')->on('mgmt_user')->onDelete('cascade');
            $table->foreign('fk_brand_id')->references('brand_id')->on('mgmt_brand')->onDelete('cascade');
            $table->unique(['fk_user_id', 'fk_brand_id']);
            $table->index('fk_user_id');
            $table->index('fk_brand_id');
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
        Schema::dropIfExists('mgmt_account');
    }
}
