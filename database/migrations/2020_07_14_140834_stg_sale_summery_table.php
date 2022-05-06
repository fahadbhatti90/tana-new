<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StgSaleSummeryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stg_sale_summary', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->unsignedDecimal('shipped_cogs', 20, 4);
            $table->unsignedMediumInteger('shipped_units')->nullable();
            $table->decimal('shipped_cogs_prior_period', 12, 4)->nullable();
            $table->decimal('shipped_units_prior_period', 12, 4)->nullable();
            $table->unsignedDecimal('acu', 12, 4)->nullable();
            $table->decimal('ptp_shipped_cogs', 12, 4)->nullable();
            $table->decimal('ptp_daily_shipped_cogs', 16, 4)->nullable();
            $table->decimal('ptp_shipped_units', 12, 4)->nullable();
            $table->decimal('ptp_daily_shipped_units', 16, 4)->nullable();
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
        Schema::dropIfExists('stg_sale_summary');
    }
}
