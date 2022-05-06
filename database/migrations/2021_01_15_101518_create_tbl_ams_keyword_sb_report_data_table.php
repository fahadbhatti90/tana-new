<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAmsKeywordSbReportDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_keyword_sb_report_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('fk_reports_download_linksId');
            $table->string('profile_id', 50);
            $table->string('entity_id', 50);

            $table->string('campaign_name', 255);
            $table->string('campaign_id',50);
            $table->string('campaign_status', 255);
            $table->string('campaign_budget',50);
            $table->string('campaign_budget_type',50);
            $table->string('ad_group_name', 255);
            $table->string('ad_group_id', 50);
            $table->string('match_type', 50);

            $table->string('keyword_text', 255);
            $table->string('keyword_id', 50);
            $table->string('keyword_b_id', 50);
            $table->string('keyword_status', 128);

            $table->string('target_id', 50);
            $table->string('targeting_expression', 50);
            $table->string('targeting_text', 255);
            $table->string('targeting_type', 50);

            $table->string('impressions',50);
            $table->string('clicks',50);
            $table->string('cost',50);

            $table->string('attributed_detail_page_views_clicks_14d',50);

            $table->string('attributed_sales_14d',50);
            $table->string('attributed_sales_14d_same_sku',50);

            $table->string('attributed_conversions_14d',50);
            $table->string('attributed_conversions_14d_same_sku',50);

            $table->string('attributed_orders_new_to_brand_14d',50);
            $table->string('attributed_orders_new_to_brand_percentage_14d',50);
            $table->string('attributed_order_rate_new_to_brand_14d',50);

            $table->string('attributed_sales_new_to_brand_14d',50);
            $table->string('attributed_sales_new_to_brand_percentage_14d',50);

            $table->string('attributed_units_ordered_new_to_brand_14d',50);
            $table->string('attributed_units_ordered_new_to_brand_percentage_14d',50);

            $table->string('units_sold_14d',50);
            $table->string('dpv_14d',50);

            $table->string('report_date',50);
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
        Schema::dropIfExists('tbl_ams_keyword_sb_report_data');
    }
}
