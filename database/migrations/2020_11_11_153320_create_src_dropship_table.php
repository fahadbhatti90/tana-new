<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrcDropshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('src_dropship', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->string('fk_vendor_id', 4);
            $table->string('order_id', 20);
            $table->string('order_status', 20);
            $table->string('warehouse_code', 10);
            $table->timestamp('order_place_date')->useCurrent();
            $table->timestamp('required_ship_date')->useCurrent();
            $table->string('ship_method', 255);
            $table->string('ship_method_code', 100);
            $table->string('ship_to_name', 60);
            $table->string('ship_to_address_line_1', 255);
            $table->string('ship_to_address_line_2', 255);
            $table->string('ship_to_address_line_3', 255);
            $table->string('ship_to_city', 60);
            $table->string('ship_to_state', 25);
            $table->string('ship_to_zipcode', 15);
            $table->string('ship_to_country', 4);
            $table->string('phone_number', 20);
            $table->string('is_it_gift', 4);
            $table->string('item_cost', 22);
            $table->string('sku', 48);
            $table->string('asin', 10);
            $table->string('item_title', 255);
            $table->string('item_quantity', 14);
            $table->string('gift_message', 255);
            $table->text('tracking_id');
            $table->timestamp('shipped_date')->useCurrent();
            $table->timestamp('capture_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('src_dropship');
    }
}
