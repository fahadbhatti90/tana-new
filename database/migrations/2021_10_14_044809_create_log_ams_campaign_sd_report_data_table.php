<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogAmsCampaignSdReportDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_ams_campaign_sd_report_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('fk_reports_download_linksId');
            $table->string('profile_id', 150);
            $table->string('entity_id', 150);

            $table->string('campaign_name', 765);
            $table->string('campaign_id', 150);
            $table->string('campaign_status', 150);
            $table->string('campaign_budget', 150);

            //for T00001 tactic
            $table->string('attributed_dpv_14d', 150);
            $table->string('attributed_units_sold_14d', 150);

            $table->string('impressions', 150);
            $table->string('clicks', 150);
            $table->string('cost', 150);
            $table->string('currency', 150);

            $table->string('attributed_conversions_1d', 150);
            $table->string('attributed_conversions_7d', 150);
            $table->string('attributed_conversions_14d', 150);
            $table->string('attributed_conversions_30d', 150);

            $table->string('attributed_conversions_1d_same_sku', 150);
            $table->string('attributed_conversions_7d_same_sku', 150);
            $table->string('attributed_conversions_14d_same_sku', 150);
            $table->string('attributed_conversions_30d_same_sku', 150);

            $table->string('attributed_units_ordered_1d', 150);
            $table->string('attributed_units_ordered_7d', 150);
            $table->string('attributed_units_ordered_14d', 150);
            $table->string('attributed_units_ordered_30d', 150);

            $table->string('attributed_sales_1d', 150);
            $table->string('attributed_sales_7d', 150);
            $table->string('attributed_sales_14d', 150);
            $table->string('attributed_sales_30d', 150);

            $table->string('attributed_sales_1d_same_sku', 150);
            $table->string('attributed_sales_7d_same_sku', 150);
            $table->string('attributed_sales_14d_same_sku', 150);
            $table->string('attributed_sales_30d_same_sku', 150);

            $table->string('report_date', 150);
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
        Schema::dropIfExists('log_ams_campaign_sd_report_data');
    }
}
