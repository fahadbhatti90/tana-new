<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactInventoryDailySummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('fact_inventory_daily_summary', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->SmallInteger('fk_vendor_id');
            $table->decimal('net_received', 11, 2);
            $table->integer('net_received_units');
            $table->unsignedMediumInteger('open_purchase_order_quantity')->nullable();
            $table->decimal('ptp_net_receipts_dollar', 11, 2)->nullable();
            $table->decimal('ptp_daily_net_receipts_dollar', 11, 2)->nullable();
            $table->decimal('ptp_net_shipped_units', 11, 2)->nullable();
            $table->decimal('ptp_daily_net_shipped_units', 11, 2)->nullable();
            $table->date('received_date')->default('1999-09-09');
            $table->integer('date_key');

            $table->index(['fk_vendor_id','received_date'],'idx_vendor_date');
            $table->index(['fk_vendor_id','net_received'],'idx_vendor_nr');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('fact_inventory_daily_summary');
    }
}
