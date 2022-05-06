<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoreInventoryMonthlyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_inventory_monthly', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->string('asin', 10);
            $table->string('product_title', 255)->nullable();
            $table->string('subcategory', 128)->nullable();
            $table->string('category', 128)->nullable();
            $table->string('model_no', 64)->nullable();
            $table->decimal('net_received', 10, 2)->nullable();
            $table->mediumInteger('net_received_units')->nullable();
            $table->decimal('sell_through_rate', 6, 2)->nullable();
            $table->unsignedMediumInteger('open_purchase_order_quantity')->nullable();
            $table->decimal('sellable_on_hand_inventory', 10, 2)->nullable();
            $table->decimal('sellable_on_hand_inventory_trailing_30_day_average', 10, 2)->nullable();
            $table->unsignedMediumInteger('sellable_on_hand_units')->nullable();
            $table->decimal('unsellable_on_hand_inventory', 10, 2)->nullable();
            $table->decimal('unsellable_on_hand_inventory_trailing_30_day_average', 10, 2)->nullable();
            $table->unsignedMediumInteger('unsellable_on_hand_units')->nullable();
            $table->decimal('aged_90+_days_sellable_inventory', 10, 2)->nullable();
            $table->decimal('aged_90+_days_sellable_inventory_trailing_30_day_average', 10, 2)->nullable();
            $table->unsignedMediumInteger('aged_90+_days_sellable_units')->nullable();
            $table->string('replenishment_category', 24)->nullable();
            $table->date('start_date')->default('1999-09-09');
            $table->date('end_date')->default('1999-09-09')->nullable();
            $table->timestamp("captured_at")->useCurrent();
            $table->unsignedInteger('date_key');
            $table->index(['fk_vendor_id', 'start_date'], 'idx_vendor_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('core_inventory_monthly');
    }
}
