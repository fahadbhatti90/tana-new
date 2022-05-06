<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErrorLogAmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('error_log_ams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('profile_id', 25)->nullable();
            $table->string('name', 191)->nullable();
            $table->string('country_code', 4)->nullable();
            $table->string('type', 15)->nullable();
            $table->string('sub_type', 4)->nullable();
            $table->string('error_type', 20)->nullable();
            $table->date('report_date')->default('1999-09-09');
            $table->tinyInteger('sent')->default('0');
            $table->timestamp("captured_at")->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('error_log_ams');
    }
}
