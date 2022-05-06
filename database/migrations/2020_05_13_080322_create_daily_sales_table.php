<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailySalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('src_sale', function (Blueprint $table) {

            $table->increments('row_id');
            $table->string('fk_vendor_id', 4);
            $table->string('asin', 10);
            $table->string('product_title', 255);
            $table->string('subcategory', 128);
            $table->string('category', 128);
            $table->string('model_no', 64);
            $table->string('shipped_cogs', 22);
            $table->string('shipped_cogs_percentage_of_total', 14);
            $table->string('shipped_cogs_prior_period', 14);
            $table->string('shipped_cogs_last_year', 14);
            $table->string('shipped_units', 14);
            $table->string('shipped_units_percentage_of_total', 14);
            $table->string('shipped_units_prior_period', 14);
            $table->string('shipped_units_last_year', 14);
            $table->string('customer_returns', 4);
            $table->string('free_replacements', 4);
            $table->string('average_sales_price', 10);
            $table->string('average_sales_price_prior_period', 7);
            $table->date('sale_date')->default('1999-09-09');
            $table->timestamp("captured_at")->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('src_sale');
    }
}
