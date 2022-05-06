<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StgPurchaseOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stg_purchase_order', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id');
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
            $table->unsignedSmallInteger('quantity_requested')->nullable();
            $table->unsignedSmallInteger('accepted_quantity')->nullable();
            $table->unsignedSmallInteger('quantity_received')->nullable();
            $table->unsignedSmallInteger('quantity_outstanding')->nullable();
            $table->decimal('unit_cost', 8, 2)->nullable();
            $table->decimal('total_cost', 11, 2)->nullable();
            $table->string('status', 15)->nullable();
            $table->date('ordered_on')->default('1999-09-09');
            $table->integer('datekey');
            $table->timestamp("captured_at")->useCurrent();
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
        Schema::dropIfExists('stg_purchase_order');
    }
}
