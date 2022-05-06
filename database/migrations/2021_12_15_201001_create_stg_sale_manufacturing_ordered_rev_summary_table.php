<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStgSaleManufacturingOrderedRevSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stg_sale_manufacturing_ordered_rev_summary', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->decimal('ordered_revenue', 20, 4)->nullable();
            $table->decimal('ordered_revenue_prior_period', 12, 4)->nullable();
            $table->decimal('ordered_revenue_last_year', 12, 4)->nullable();
            $table->mediumInteger('ordered_units')->nullable();
            $table->decimal('ordered_units_prior_period', 12, 4)->nullable();
            $table->decimal('ordered_units_last_year', 12, 4)->nullable();
            $table->mediumInteger('subcategory_sales_rank')->nullable();
            $table->decimal('average_sales_price', 12, 4)->nullable();
            $table->decimal('average_sales_price_prior_period', 12, 4)->nullable();
            $table->mediumInteger('glance_views')->nullable();
            $table->decimal('glance_views_prior_period', 12, 4)->nullable();
            $table->decimal('change_in_GV_last_year', 12, 4)->nullable();
            $table->decimal('conversion_rate', 12, 4)->nullable();
            $table->date('sale_date')->default('1999-09-09');
            $table->unsignedInteger('date_key');
            $table->index(['fk_vendor_id', 'sale_date'], 'idx_vendor_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stg_sale_manufacturing_ordered_rev_summary');
    }
}
