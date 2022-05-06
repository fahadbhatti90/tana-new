<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreSalePtpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_sale_ptp', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->string('fk_vendor_name', 64)->nullable();
            $table->string('category_name', 128)->nullable();
            $table->unsignedDecimal('shipped_cogs', 16, 4)->nullable();
            $table->unsignedInteger('receipt_shipped_units')->nullable();
            $table->unsignedDecimal('receipt_dollar', 16, 2)->nullable();
            $table->unsignedInteger('shipped_units')->nullable();
            $table->date('ptp_date')->default('1999-09-09');
            $table->unsignedInteger('date_key')->nullable();
            $table->timestamp('captured_at')->useCurrent()->nullable();
            $table->index(['fk_vendor_id', 'ptp_date'], 'idx_vendor_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('core_sale_ptp');
    }
}
