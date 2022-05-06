<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PtpSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('src_sale_ptp', function (Blueprint $table) {
            $table->smallIncrements('row_id');
            $table->string('fk_vendor_name', 64);
            $table->string('category_name', 128)->nullable();
            $table->string('shipped_cogs', 16)->nullable();
            $table->string('receipt_shipped_units', 16)->nullable();
            $table->string('receipt_dollar', 16)->nullable();
            $table->string('shipped_units', 16)->nullable();
            $table->date('ptp_date')->default('1999-09-09');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('src_sale_ptp');
    }
}
