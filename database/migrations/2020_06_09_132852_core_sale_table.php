<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_sale', function (Blueprint $table) {

            $table->increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->string('asin', 10);
            $table->string('product_title', 255)->nullable();
            $table->string('subcategory', 128)->nullable();
            $table->string('category', 128)->nullable();
            $table->string('model_no', 64)->nullable();
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
            $table->date('sale_date')->default('1999-09-09');
            $table->unsignedInteger('date_key');
            $table->timestamp("captured_at")->useCurrent();
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
        Schema::dropIfExists('core_sale');
    }
}
