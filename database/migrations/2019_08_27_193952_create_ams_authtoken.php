<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmsAuthtoken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_authtoken', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('client_id');
            $table->string('client_secret');
            $table->longText('access_token');
            $table->longText('refresh_token');
            $table->integer('number_of_profiles');
            $table->string('token_type',50);
            $table->string('expires_in',50);
            $table->tinyInteger('expire_flag');
            $table->dateTime('creation_date');
            $table->dateTime('last_update')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ams_authtoken');
    }
}
