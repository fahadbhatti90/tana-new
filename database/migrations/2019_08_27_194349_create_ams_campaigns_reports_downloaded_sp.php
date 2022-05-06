<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmsCampaignsReportsDownloadedSp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_campaigns_reports_downloaded_sp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('fk_reports_download_linksId');
            $table->string('fk_profile_id', 50);
            $table->boolean('bid_plus');
            $table->string('attributed_sales7d',50);
            $table->string('attributed_sales30d',50);
            $table->string('attributed_units_ordered30d',50);
            $table->string('attributed_sales_id',50);
            $table->string('attributed_conversions_id',50);
            $table->string('attributed_sales1d_same_sku',50);
            $table->string('attributed_conversions30d',50);
            $table->string('attributed_conversions7d',50);
            $table->string('attributed_conversions14d',50);
            $table->string('campaign_status',50);
            $table->string('attributed_conversions7d_same_sku',50);
            $table->string('attributed_conversions1d_same_sku',50);
            $table->string('cost',50);
            $table->string('portfolio_id');
            $table->string('portfolio_name');
            $table->string('attributed_units_ordered7d',50);
            $table->string('attributed_sales7d_same_sku',50);
            $table->string('campaign_id',50);
            $table->string('attributed_sales14d_same_sku',50);
            $table->string('attributed_sales30d_same_sku',50);
            $table->string('impressions',50);
            $table->string('attributed_units_ordered1d',50);
            $table->string('attributed_conversions30d_same_sku',50);
            $table->string('campaign_budget',50);
            $table->string('attributed_conversions14d_same_sku',50);
            $table->string('clicks',50);
            $table->string('attributed_sales14d',50);
            $table->string('campaign_name');
            $table->string('attributed_units_ordered14d',50);
            $table->string('attributed_units_ordered1d_same_sku',50)->default(0);
            $table->string('attributed_units_ordered7d_same_sku',50)->default(0);
            $table->string('attributed_units_ordered14d_same_sku',50)->default(0);
            $table->string('attributed_units_ordered30d_same_sku',50)->default(0);
            $table->string('report_date',50);
            $table->date('creation_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ams_campaigns_reports_downloaded_sp');
    }
}
