<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStgSaleManufacturingOrderedRevTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stg_sale_manufacturing_ordered_rev', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->string('asin', 10);
            $table->string('product_title', 255)->nullable();
            $table->string('subcategory', 128)->nullable();
            $table->string('category', 128)->nullable();
            $table->string('model_no', 64)->nullable();
            $table->decimal('ordered_revenue', 20, 4)->nullable();
            $table->decimal('ordered_revenue_%_of_total', 12, 4)->nullable();
            $table->decimal('ordered_revenue_prior_period', 12, 4)->nullable();
            $table->decimal('ordered_revenue_last_year', 12, 4)->nullable();
            $table->unsignedMediumInteger('ordered_units')->nullable();
            $table->decimal('ordered_units_%_of_total', 12, 4)->nullable();
            $table->decimal('ordered_units_prior_period', 12, 4)->nullable();
            $table->decimal('ordered_units_last_year', 12, 4)->nullable();
            $table->unsignedMediumInteger('subcategory_sales_rank')->nullable();
            $table->unsignedMediumInteger('average_sales_price')->nullable();
            $table->unsignedDecimal('average_sales_price_prior_period', 12, 4)->nullable();
            $table->unsignedMediumInteger('glance_views')->nullable();
            $table->decimal('glance_views_prior_period', 12, 4)->nullable();
            $table->decimal('change_in_GV_last_year', 12, 4)->nullable();
            $table->decimal('conversion_rate', 12, 4)->nullable();
            $table->decimal('rep_OOS', 12, 4)->nullable();
            $table->decimal('rep_OOS_%_of_total', 12, 4)->nullable();
            $table->decimal('rep_OOS_prior_period', 12, 4)->nullable();
            $table->decimal('LBB_price', 12, 4)->nullable();
            $table->date('sale_date')->default('1999-09-09');
            $table->unsignedInteger('date_key');
            $table->index(['fk_vendor_id', 'sale_date'], 'idx_vendor_date');
            $table->index(['asin', 'subcategory'], 'idx_asin_subcategory');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stg_sale_manufacturing_ordered_rev');
    }
}
