<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreSaleCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_sale_category', function (Blueprint $table) {
            $table->increments('row_id');
            $table->string('fk_vendor_name', 64);
            $table->string('asin', 10);
            $table->string('category', 128);
            $table->date('inserted_at')->default('1999-09-09');
            $table->unsignedInteger('date_key');
            $table->timestamp('captured_at')->useCurrent();
            $table->index(['asin', 'category'], 'idx_asin_category');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('core_sale_category');
    }
}
