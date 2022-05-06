<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableVcPurchaseOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('src_purchase_order', function (Blueprint $table) {
            $table->increments('row_id');
            $table->string('fk_vendor_id', 5);
            $table->string('po', 10);
            $table->string('vendor', 12)->nullable();
            $table->string('ship_to_location', 64)->nullable();
            $table->string('asin', 10)->nullable();
            $table->string('external_id', 24)->nullable();
            $table->string('model_number', 64)->nullable();
            $table->string('product_title', 255)->nullable();
            $table->string('availability', 48)->nullable();
            $table->string('backordered', 12)->nullable();
            $table->string('window_type', 24)->nullable();
            $table->date('window_start')->default('1999-09-09');
            $table->date('window_end')->default('1999-09-09');
            $table->date('expected_date')->default('1999-09-09');
            $table->string('quantity_requested', 8)->nullable();
            $table->string('accepted_quantity', 8)->nullable();
            $table->string('quantity_received', 8)->nullable();
            $table->string('quantity_outstanding', 8)->nullable();
            $table->string('unit_cost', 12)->nullable();
            $table->string('total_cost', 12)->nullable();
            $table->string('status', 9)->nullable();
            $table->date('ordered_on')->default('1999-09-09');
            $table->timestamp("captured_at")->useCurrent();
            $table->index(['fk_vendor_id', 'ordered_on'], 'idx_vendor_date');
            $table->index(['po', 'status'], 'idx_po_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('src_purchase_order');
    }
}
