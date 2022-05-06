<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAmsAsinSpReportDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_asin_sp_report_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('fk_reports_download_linksId');
            $table->string('fk_profile_id', 50);
            $table->string('entity_id', 50);

            $table->string('campaign_name', 255);
            $table->string('campaign_id', 50);

            $table->string('adgroup_name', 255);
            $table->string('adgroup_id', 50);

            $table->string('keyword_id', 50);
            $table->string('keyword_text', 255);

            $table->string('asin', 50);
            $table->string('other_asin', 50);
            $table->string('sku', 50);
            $table->string('currency', 50);
            $table->string('match_type', 50);

            $table->string('attributed_units_ordered_1d', 50)->default(0);
            $table->string('attributed_units_ordered_7d', 50)->default(0);
            $table->string('attributed_units_ordered_14d', 50)->default(0);
            $table->string('attributed_units_ordered_30d', 50)->default(0);

            $table->string('attributed_units_ordered_1d_other_sku', 50)->default(0);
            $table->string('attributed_units_ordered_7d_other_sku', 50)->default(0);
            $table->string('attributed_units_ordered_14d_other_sku', 50)->default(0);
            $table->string('attributed_units_ordered_30d_other_sku', 50)->default(0);

            $table->string('attributed_sales_1d_other_sku', 50);
            $table->string('attributed_sales_7d_other_sku', 50);
            $table->string('attributed_sales_14d_other_sku', 50);
            $table->string('attributed_sales_30d_other_sku', 50);

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
        Schema::dropIfExists('tbl_ams_asin_sp_report_data');
    }
}
