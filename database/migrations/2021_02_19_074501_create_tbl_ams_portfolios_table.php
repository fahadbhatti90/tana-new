<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAmsPortfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_portfolios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fk_profile_id');
            $table->unsignedBigInteger('fk_access_token');
            $table->string('profile_id',50);
            $table->string('portfolios_id',50);
            $table->string('portfolios_name', 255);
            $table->double('amount', 20, 2)->default(0.00);
            $table->string('currency_code', 50)->default('NA');
            $table->string('policy', 100)->default('NA');
            $table->string('in_budget', 50);
            $table->string('state', 50);
            $table->unsignedTinyInteger('is_sandbox');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ams_portfolios');
    }
}
