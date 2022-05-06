<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoreAmsProductAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_ams_product_ads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('profile_id');
            $table->string('entity_id', 50);

            $table->bigInteger('campaign_id');
            $table->string('campaign_name', 255);
            $table->bigInteger('ad_group_id');
            $table->string('ad_group_name', 255);

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
        Schema::dropIfExists('core_ams_product_ads');
    }
}
