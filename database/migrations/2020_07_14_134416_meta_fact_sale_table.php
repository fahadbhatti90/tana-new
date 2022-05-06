<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MetaFactSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadata_fact_sale', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id')->nullable();
            $table->date('daily_max_date')->default('1999-09-09')->nullable();
            $table->date('week_max_date')->default('1999-09-09')->nullable();
            $table->date('monthly_max_date')->default('1999-09-09')->nullable();
            $table->dateTime('inserted_at', 0)->is_nullable();
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
        Schema::dropIfExists('metadata_fact_sale');
    }
}
