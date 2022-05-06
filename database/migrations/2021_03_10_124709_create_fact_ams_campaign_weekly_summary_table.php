<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactAmsCampaignWeeklySummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('fact_ams_campaign_weekly_summary', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->string('fk_profile_id', 25);
            $table->string('fk_entity_id', 25);

            $table->decimal('campaign_budget', 10, 4)->nullable();
            $table->unsignedInteger('impressions')->nullable();
            $table->unsignedInteger('clicks')->nullable();
            $table->unsignedDecimal('cost', 12, 4)->nullable();
            $table->decimal('roas', 20, 4)->nullable();
            $table->decimal('acos', 20, 4)->nullable();
            $table->unsignedDecimal('campaign_sales', 12, 4)->nullable();
            $table->unsignedDecimal('campaign_sales_sku', 12, 4)->nullable();
            $table->unsignedInteger('campaign_units')->nullable();
            $table->unsignedInteger('campaign_units_sku')->nullable();
            $table->unsignedDecimal('campaign_conversion', 12, 4)->nullable();
            $table->unsignedDecimal('campaign_conversion_sku', 12, 4)->nullable();

            $table->date('start_date')->default('1999-09-09');
            $table->date('end_date')->default('1999-09-09');
            $table->unsignedInteger('date_key');
            $table->timestamp("captured_at")->useCurrent();
            $table->index(['fk_vendor_id'], 'idx_vendor_date');
            $table->index(['fk_profile_id'], 'idx_asin_subcategory');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('fact_ams_campaign_weekly_summary');
    }
}
