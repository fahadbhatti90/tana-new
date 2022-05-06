<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrcSaleManufacturingOrderedRevTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('src_sale_manufacturing_ordered_rev', function (Blueprint $table) {
        $table->Increments('id');
        $table->string('fk_vendor_id', 4);
        $table->string('asin', 20);
        $table->string('product_title', 255);
        $table->string('subcategory', 255);
        $table->string('category', 255);
        $table->string('model_number', 255)->nullable();
        $table->string('ordered_revenue', 100)->nullable();
        $table->string('ordered_revenue_%_of_total', 10)->nullable();
        $table->string('ordered_revenue_prior_period', 10)->nullable();
        $table->string('ordered_revenue_last_year', 10)->nullable();
        $table->string('ordered_units', 50)->nullable();
        $table->string('ordered_units_%_of_total', 10)->nullable();
        $table->string('ordered_units_prior_period', 10)->nullable();
        $table->string('ordered_units_last_year', 10)->nullable();
        $table->string('subcategory_sales_rank', 100)->nullable();
        $table->string('avg_sale_price', 100)->nullable();
        $table->string('avg_sale_price_prior_period', 10)->nullable();
        $table->string('glance_views', 50)->nullable();
        $table->string('glance_views_prior_period', 10)->nullable();
        $table->string('change_in_GV_last_year', 10)->nullable();
        $table->string('conversion_rate', 10)->nullable();
        $table->string('rep_OOS', 10)->nullable();
        $table->string('rep_OOS_%_of_total', 10)->nullable();
        $table->string('rep_OOS_prior_period', 10)->nullable();
        $table->string('LBB_price', 10)->nullable();
        $table->date('sale_date')->default('1999-09-09');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('src_sale_manufacturing_ordered_rev');
    }
}
