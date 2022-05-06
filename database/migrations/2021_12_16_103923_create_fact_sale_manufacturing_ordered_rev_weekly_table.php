<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactSaleManufacturingOrderedRevWeeklyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::connection('mysql2')->create('fact_sale_manufacturing_ordered_rev_weekly', function (Blueprint $table){
                $table->Increments('row_id');
                $table->unsignedMediumInteger('fk_vendor_id');
                $table->unsignedMediumInteger('fk_product_id');
                $table->unsignedMediumInteger('fk_category_id');
                $table->decimal('ordered_revenue', 20, 4)->nullable();
                $table->decimal('ordered_revenue_%_of_total', 12, 4)->nullable();
                $table->decimal('ordered_revenue_prior_period', 12, 4)->nullable();
                $table->decimal('ordered_revenue_last_year', 12, 4)->nullable();
                $table->unsignedMediumInteger('ordered_units')->nullable();
                $table->decimal('ordered_units_%_of_total', 12, 4)->nullable();
                $table->decimal('ordered_units_prior_period', 12, 4)->nullable();
                $table->decimal('ordered_units_last_year', 12, 4)->nullable();
                $table->unsignedMediumInteger('subcategory_sales_rank')->nullable();
                $table->decimal('average_sales_price',12,4)->nullable();
                $table->decimal('average_sales_price_prior_period', 12, 4)->nullable();
                $table->unsignedMediumInteger('glance_views')->nullable();
                $table->decimal('glance_views_prior_period', 12, 4)->nullable();
                $table->decimal('change_in_GV_last_year', 12, 4)->nullable();
                $table->decimal('conversion_rate', 12, 4)->nullable();
                $table->date('start_date')->default('1999-09-09');
                $table->date('end_date')->default('1999-09-09');
                $table->unsignedInteger('date_key');
                $table->index(['fk_vendor_id', 'start_date'], 'idx_vendor_date');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('fact_sale_manufacturing_ordered_rev_weekly');

    }
}
