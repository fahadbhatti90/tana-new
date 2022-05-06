<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAmsBiddingRuleInvalidProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_bidding_rule_invalid_profile', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('fk_bidding_rule_id');
            $table->unsignedBigInteger('profile_id');
            $table->unsignedBigInteger('campaign_id');

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
        Schema::dropIfExists('tbl_ams_bidding_rule_invalid_profile');
    }
}
