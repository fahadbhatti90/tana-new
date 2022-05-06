<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAmsBiddingRuleKeywordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_bidding_rule_keyword', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('fk_rule_cron_id');
            $table->unsignedBigInteger('fk_bidding_rule_id');
            $table->string('ad_type',64);

            $table->bigInteger('profile_id');
            $table->bigInteger('ad_group_id');
            $table->bigInteger('campaign_id');
            $table->bigInteger('keyword_id');

            $table->string('keyword_text', 255);
            $table->string('match_type', 255);
            $table->string('state', 255);
            $table->decimal('bid', 8, 2);
            $table->string('serving_status', 255);
            $table->string('creation_date', 255);
            $table->string('last_updated_date', 255);
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
        Schema::dropIfExists('tbl_ams_bidding_rule_keyword');
    }
}
