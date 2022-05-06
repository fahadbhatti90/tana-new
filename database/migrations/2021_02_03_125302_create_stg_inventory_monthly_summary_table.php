<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStgInventoryMonthlySummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stg_inventory_monthly_summary', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->decimal('net_received', 11, 2)->nullable();
            $table->mediumInteger('net_received_units')->nullable();
            $table->unsignedMediumInteger('open_purchase_order_quantity')->nullable();
            $table->decimal('ptp_net_receipts_dollar', 11, 2)->nullable();
            $table->decimal('ptp_daily_net_receipts_dollar', 11, 2)->nullable();
            $table->decimal('ptp_net_shipped_units', 11, 2)->nullable();
            $table->decimal('ptp_daily_net_shipped_units', 11, 2)->nullable();
            $table->decimal('yoy', 8, 2)->default('0.00');
            $table->date('start_date')->default('1999-09-09');
            $table->date('end_date')->default('1999-09-09');
            $table->unsignedInteger('date_key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stg_inventory_monthly_summary');
    }
}
