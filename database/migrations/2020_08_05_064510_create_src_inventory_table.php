<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrcInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('src_inventory', function (Blueprint $table) {
            $table->increments('row_id');
            $table->string('fk_vendor_id', 4);
            $table->string('asin', 10);
            $table->string('product_title', 255)->nullable();
            $table->string('subcategory', 128)->nullable();
            $table->string('category', 128)->nullable();
            $table->string('model_no', 64)->nullable();
            $table->string('net_received', 12)->nullable();
            $table->string('net_received_units', 8)->nullable();
            $table->string('sell_through_rate', 10)->nullable();
            $table->string('open_purchase_order_quantity', 8)->nullable();
            $table->string('sellable_on_hand_inventory', 14)->nullable();
            $table->string('sellable_on_hand_inventory_trailing_30_day_average', 14)->nullable();
            $table->string('sellable_on_hand_units', 10)->nullable();
            $table->string('unsellable_on_hand_inventory', 14)->nullable();
            $table->string('unsellable_on_hand_inventory_trailing_30_day_average', 14)->nullable();
            $table->string('unsellable_on_hand_units', 6)->nullable();
            $table->string('aged_90+_days_sellable_inventory', 14)->nullable();
            $table->string('aged_90+_days_sellable_inventory_trailing_30_day_average', 14)->nullable();
            $table->string('aged_90+_days_sellable_units', 6)->nullable();
            $table->string('replenishment_category', 24)->nullable();
            $table->date('received_date')->default('1999-09-09');
            $table->timestamp("captured_at")->useCurrent();
            $table->index(['fk_vendor_id', 'received_date'], 'idx_vendor_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('src_inventory');
    }
}
