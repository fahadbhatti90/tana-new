<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStgAmsProductAdsSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stg_ams_product_ads_summary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('profile_id');
            $table->string('entity_id', 50);

            $table->string('product_report_type',4);

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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stg_ams_product_ads_summary');
    }
}
