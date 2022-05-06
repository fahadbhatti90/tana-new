<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactInventoryMonthlySummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('fact_inventory_monthly_summary', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->SmallInteger('fk_vendor_id');
            $table->decimal('net_received', 11, 2);
            $table->integer('net_received_units');
            $table->unsignedInteger('open_purchase_order_quantity')->nullable();
            $table->decimal('ptp_net_receipts_dollar', 11, 2)->nullable();
            $table->decimal('ptp_daily_net_receipts_dollar', 11, 2)->nullable();
            $table->decimal('ptp_net_shipped_units', 11, 2)->nullable();
            $table->decimal('ptp_daily_net_shipped_units', 11, 2)->nullable();
            $table->date('start_date')->default('1999-09-09');
            $table->date('end_date');
            $table->unsignedInteger('date_key');

            $table->index(['fk_vendor_id','start_date'],'idx_vendor_date');
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
        Schema::connection('mysql2')->dropIfExists('fact_inventory_monthly_summary');
    }
}
