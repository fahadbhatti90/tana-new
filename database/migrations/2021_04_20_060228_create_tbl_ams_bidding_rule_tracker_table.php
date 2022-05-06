<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAmsBiddingRuleTrackerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_bidding_rule_tracker', function (Blueprint $table) {
            $table->increments('id');

            $table->string('profile_id', 64);
            $table->string('campaign_id', 64);
            $table->string('ad_group_id', 64);

            $table->string('keyword_id', 64)->default("0");
            $table->string('target_id', 64)->default("0");

            $table->string('state', 64);
            $table->string('ad_type', 64);
            $table->string('old_bid', 64);
            $table->string('new_bid', 64);
            $table->string('check_status', 64);

            $table->timestamp('tracked_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ams_bidding_rule_tracker');
    }
}
