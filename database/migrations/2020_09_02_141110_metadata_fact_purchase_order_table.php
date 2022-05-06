<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MetadataFactPurchaseOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadata_fact_purchase_order', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id')->nullable();
            $table->date('daily_max_date')->default('1999-09-09');
            $table->date('weekly_max_date')->default('1999-09-09');
            $table->date('monthly_max_date')->default('1999-09-09');
            $table->timestamp('inserted_at')->useCurrent();
            $table->index(['fk_vendor_id', 'daily_max_date'], 'idx_vendor_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metadata_fact_purchase_order');
    }
}
