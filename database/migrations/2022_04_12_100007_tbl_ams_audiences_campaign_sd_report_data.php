<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblAmsAudiencesCampaignSdReportData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_campaign_sd_audiences_report_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('fk_reports_download_linksId');
            $table->string('profile_id', 50);
            $table->string('entity_id', 50);

            $table->string('campaign_name', 255);
            $table->string('campaign_id', 50);
            $table->string('campaign_status', 50);
            $table->string('campaign_budget', 50);

            //for T00001 tactic
            $table->string('attributed_dpv_14d', 50);
            $table->string('attributed_units_sold_14d', 50);

            $table->string('impressions', 50);
            $table->string('clicks', 50);
            $table->string('cost', 50);
            $table->string('currency', 50);

            $table->string('attributed_conversions_1d', 50);
            $table->string('attributed_conversions_7d', 50);
            $table->string('attributed_conversions_14d', 50);
            $table->string('attributed_conversions_30d', 50);

            $table->string('attributed_conversions_1d_same_sku', 50);
            $table->string('attributed_conversions_7d_same_sku', 50);
            $table->string('attributed_conversions_14d_same_sku', 50);
            $table->string('attributed_conversions_30d_same_sku', 50);

            $table->string('attributed_units_ordered_1d', 50)->default(0);
            $table->string('attributed_units_ordered_7d', 50)->default(0);
            $table->string('attributed_units_ordered_14d', 50)->default(0);
            $table->string('attributed_units_ordered_30d', 50)->default(0);

            $table->string('attributed_sales_1d', 50)->default(0);
            $table->string('attributed_sales_7d', 50)->default(0);
            $table->string('attributed_sales_14d', 50)->default(0);
            $table->string('attributed_sales_30d', 50)->default(0);

            $table->string('attributed_sales_1d_same_sku', 50);
            $table->string('attributed_sales_7d_same_sku', 50);
            $table->string('attributed_sales_14d_same_sku', 50);
            $table->string('attributed_sales_30d_same_sku', 50);

            $table->string('view_attributed_conversions_14d', 50)->default(0);
            $table->string('view_attributed_detail_page_view_14d', 50)->default(0);
            $table->string('view_attributed_sales_14d', 50)->default(0);
            $table->string('view_attributed_units_ordered_14d', 50)->default(0);
            $table->string('view_impressions', 50)->default(0);

            $table->string('report_date', 50);
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
        Schema::dropIfExists('tbl_ams_campaign_sd_audiences_report_data');
    }
}
