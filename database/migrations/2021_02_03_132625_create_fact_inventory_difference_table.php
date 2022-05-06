<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactInventoryDifferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('fact_inventory_difference', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('fk_vendor_id');
            $table->decimal('monthly_sum_net_received', 11, 2)->nullable();
            $table->integer('monthly_sum_net_received_units')->nullable();
            $table->decimal('daily_sum_net_received', 11, 2)->nullable();
            $table->integer('daily_sum_net_received_units')->nullable();
            $table->decimal('net_receieved_diff', 11, 2)->nullable();
            $table->integer('net_receieved_units_diff')->nullable();
            $table->decimal('net_received_diff_for_day', 11, 2)->nullable();
            $table->integer('net_received_units_diff_for_day')->nullable();
            $table->date('start_date')->default('1999-09-09');
            $table->date('end_date')->default('1999-09-09');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('fact_inventory_difference');
    }
}
