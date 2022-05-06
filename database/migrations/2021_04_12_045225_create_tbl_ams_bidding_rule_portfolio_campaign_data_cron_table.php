<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAmsBiddingRulePortfolioCampaignDataCronTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_bidding_rule_portfolio_campaign_data_cron', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fk_bidding_rule_id');
            $table->unsignedBigInteger('fk_access_token');

            $table->string('rule_ad_type',64);// SP, SB, or SD
            $table->string('rule_select_type',64); // campaign or portfolio
            $table->string('frequency', 64);
            $table->unsignedInteger('frequency_days');

            $table->bigInteger('profile_id');
            $table->bigInteger('campaign_id');
            $table->bigInteger('portfolio_id');

            $table->unsignedTinyInteger('is_done')->default('0');//1(done) or 0(pending)

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
        Schema::dropIfExists('tbl_ams_bidding_rule_portfolio_campaign_data_cron');
    }
}
