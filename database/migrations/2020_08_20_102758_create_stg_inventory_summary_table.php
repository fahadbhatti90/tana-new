<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStgInventorySummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stg_inventory_summary', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->decimal('net_received', 11, 2);
            $table->integer('net_received_units');
            $table->unsignedMediumInteger('open_purchase_order_quantity')->nullable();
            $table->decimal('ptp_net_receipts_dollar', 11, 2)->nullable();
            $table->decimal('ptp_daily_net_receipts_dollar', 11, 2)->nullable();
            $table->decimal('ptp_net_shipped_units', 11, 2)->nullable();
            $table->decimal('ptp_daily_net_shipped_units', 11, 2)->nullable();
            $table->date('received_date')->default('1999-09-09');
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
        Schema::dropIfExists('stg_inventory_summary');
    }
}
