<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactSaleWeeklySummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('fact_sale_weekly_summary', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->unsignedMediumInteger('fk_vendor_id');
            $table->unsignedDecimal('shipped_cogs', 20, 4)->nullable();
            $table->unsignedMediumInteger('shipped_units')->nullable();
            $table->decimal('shipped_cogs_prior_period', 12, 4)->nullable();
            $table->decimal('shipped_units_prior_period', 12, 4)->nullable();
            $table->unsignedDecimal('acu', 12, 4)->nullable();
            $table->decimal('ptp_shipped_cogs', 12, 4)->nullable();
            $table->decimal('ptp_daily_shipped_cogs', 16, 4)->nullable();
            $table->decimal('ptp_shipped_units', 12, 4)->nullable();
            $table->decimal('ptp_daily_shipped_units', 16, 4)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedInteger('date_key');

            $table->index(['fk_vendor_id','start_date'],'idx_vendor_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('fact_sale_weekly_summary');
    }
}
