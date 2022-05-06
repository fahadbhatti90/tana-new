<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogAmsCampaignSbReportDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_ams_campaign_sb_report_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('fk_reports_download_linksId');
            $table->string('profile_id', 150);
            $table->string('entity_id', 50);

            $table->string('campaign_name', 765);
            $table->string('campaign_id',150);
            $table->string('campaign_status', 765);
            $table->string('campaign_budget',150);
            $table->string('campaign_budget_type',150);
            $table->string('campaign_rule_based_budget', 765);
            $table->string('applicable_budget_rule_id', 765);
            $table->string('applicable_budget_rule_name', 765);

            $table->string('impressions',150);
            $table->string('clicks',150);
            $table->string('cost',150);

            $table->string('attributed_detail_page_views_clicks_14d',150);

            $table->string('attributed_sales_14d',150);
            $table->string('attributed_sales_14d_same_sku',150);

            $table->string('attributed_conversions_14d',150);
            $table->string('attributed_conversions_14d_same_sku',150);

            $table->string('attributed_orders_new_to_brand_14d',150);
            $table->string('attributed_orders_new_to_brand_percentage_14d',150);
            $table->string('attributed_order_rate_new_to_brand_14d',150);

            $table->string('attributed_sales_new_to_brand_14d',150);
            $table->string('attributed_sales_new_to_brand_percentage_14d',150);

            $table->string('attributed_units_ordered_new_to_brand_14d',150);
            $table->string('attributed_units_ordered_new_to_brand_percentage_14d',150);

            $table->string('units_sold_14d',150);
            $table->string('dpv_14d',150);

            $table->string('report_date',150);
            $table->date('reported_date');
            $table->timestamp('captured_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_ams_campaign_sb_report_data');
    }
}
