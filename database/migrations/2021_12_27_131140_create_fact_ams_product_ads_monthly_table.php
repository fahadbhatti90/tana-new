<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactAmsProductAdsMonthlyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('fact_ams_product_ads_monthly', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('profile_id');
            $table->string('entity_id', 50);

            $table->bigInteger('campaign_id');
            $table->bigInteger('ad_group_id');

            $table->string('product_report_type',4);
            $table->string('asin',20);
            $table->string('sku',50);
            $table->string('adId',50);

            $table->integer('impressions');
            $table->integer('clicks');
            $table->decimal('cost',16, 4);
            $table->string('currency',5);

            $table->decimal('conversion',16, 4);
            $table->decimal('conversion_sku',16, 4);

            $table->mediumInteger('units_ordered');
            $table->mediumInteger('units_ordered_sku');

            $table->decimal('sales',16, 4);
            $table->decimal('sales_sku',16, 4);
            $table->integer('date_key');

            $table->date('start_date');
            $table->date('end_date');
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
        Schema::connection('mysql2')->dropIfExists('fact_ams_product_ads_monthly');
    }
}
