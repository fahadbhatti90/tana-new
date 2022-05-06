<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrcSc3pSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('src_sc_sale', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->string('fk_vendor_id', 4);
            $table->string('ordered_product_sales', 22);
            $table->string('units_ordered', 14);
            $table->string('total_ordered_items', 14);
            $table->string('average_sales_per_order_item', 22);
            $table->string('average_units_per_order_item', 14);
            $table->string('average_selling_price', 22);
            $table->string('sessions', 14);
            $table->string('order_item_session_percentage', 14);
            $table->string('average_offer_count', 14);
            $table->date('sale_date')->default('1999-09-09');
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
        Schema::dropIfExists('src_sc_sale');
    }
}
