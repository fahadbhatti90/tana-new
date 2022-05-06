<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StgPurchaseOrderSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stg_purchase_order_summary', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id')->nullable();
            $table->string('po', 10)->nullable();
            $table->unsignedDecimal('total_cases', 8, 2)->nullable();
            $table->unsignedDecimal('total_cost', 11, 2)->nullable();
            $table->unsignedDecimal('confirmation_rate', 7, 2)->nullable();
            $table->date('ordered_on')->default('1999-09-09');
            $table->unsignedInteger('date_key');
            $table->timestamp('captured_at')->useCurrent();
            $table->index(['fk_vendor_id', 'ordered_on'], 'idx_vendor_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stg_purchase_order_summary');
    }
}
