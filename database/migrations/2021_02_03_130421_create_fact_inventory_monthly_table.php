<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactInventoryMonthlyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('fact_inventory_monthly', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->unsignedMediumInteger('fk_vendor_id');
            $table->unsignedMediumInteger('fk_product_id');
            $table->mediumInteger('fk_category_id');
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
            $table->date('end_date')->default('1999-09-09');
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
        Schema::connection('mysql2')->dropIfExists('fact_inventory_monthly');
    }
}
