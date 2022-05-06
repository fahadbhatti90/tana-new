<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactSaleMonthlyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('fact_sale_monthly', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->unsignedMediumInteger('fk_vendor_id');
            $table->unsignedMediumInteger('fk_product_id');
            $table->unsignedMediumInteger('fk_category_id');
            $table->unsignedDecimal('shipped_cogs', 20, 4)->nullable();
            $table->decimal('shipped_cogs_percentage_of_total', 12, 4)->nullable();
            $table->decimal('shipped_cogs_prior_period', 12, 4)->nullable();
            $table->decimal('shipped_cogs_last_year', 12, 4)->nullable();
            $table->unsignedMediumInteger('shipped_units')->nullable();
            $table->decimal('shipped_units_percentage_of_total', 12, 4)->nullable();
            $table->decimal('shipped_units_prior_period', 12, 4)->nullable();
            $table->decimal('shipped_units_last_year', 12, 4)->nullable();
            $table->unsignedSmallInteger('customer_returns')->nullable();
            $table->unsignedSmallInteger('free_replacements')->nullable();
            $table->decimal('average_sales_price', 10, 2)->nullable();
            $table->decimal('average_sales_price_prior_period', 10, 2)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('date_key');

            $table->index(['fk_vendor_id','start_date'],'idx_vendor_date');
            $table->index('fk_product_id','idx_product');
            $table->index('fk_category_id','idx_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('fact_sale_monthly');
    }
}
