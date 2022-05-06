<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FactPoDailyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('fact_po_daily', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->mediumInteger('fk_vendor_id')->nullable();
            $table->mediumInteger('fk_product_id')->nullable();
            $table->mediumInteger('fk_po_id')->nullable();
            $table->date('window_start')->default('1999-09-09');
            $table->date('window_end')->default('1999-09-09');
            $table->date('expected_date')->default('1999-09-09');
            $table->mediumInteger('quantity_requested')->nullable();
            $table->mediumInteger('accepted_quantity')->nullable();
            $table->mediumInteger('quantity_received')->nullable();
            $table->mediumInteger('quantity_outstanding')->nullable();
            $table->decimal('unit_cost', 14, 4)->nullable();
            $table->decimal('total_cost', 16, 4)->nullable();
            $table->date('ordered_on')->default('1999-09-09');
            $table->integer('datekey');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('fact_po_daily');
    }
}
