<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAmsCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fk_profile_id');
            $table->unsignedBigInteger('fk_access_token');
            $table->string('profile_id',50);
            $table->string('portfolios_id',50);
            $table->string('type', 50);
            $table->string('campaign_id', 50);
            $table->mediumText('name');
            $table->string('campaign_type', 50);

            $table->string('strategy', 100);
            $table->string('predicate', 100);
            $table->integer('percentage');
            $table->double('budget', 8, 2)->default(0.00);
            $table->double('daily_budget', 8, 2)->default(0.00);
            $table->string('budget_type', 20);

            $table->string('state', 20);

            $table->string('targeting_type', 50);
            $table->string('premium_bid_adjustment', 20);
            $table->string('bid_optimization',10);
            $table->string('serving_status', 100);
            $table->string('page_type', 50);
            $table->mediumText('url');

            $table->string('brand_name', 100);
            $table->string('brand_logo_asset_id', 100);
            $table->mediumText('headline');
            $table->string('should_optimize_asins', 20);
            $table->mediumText('brand_logo_url');
            $table->mediumText('asins');

            $table->string('start_date', 20);
            $table->string('end_date', 20);

            $table->unsignedTinyInteger('is_active')->default('1');
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
        Schema::dropIfExists('tbl_ams_campaigns');
    }
}
