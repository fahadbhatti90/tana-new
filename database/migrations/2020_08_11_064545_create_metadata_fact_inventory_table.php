<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetadataFactInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadata_fact_inventory', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id')->nullable();
            $table->date('daily_max_date')->default('1999-09-09');
            $table->date('week_max_date')->default('1999-09-09');
            $table->date('monthly_max_date')->default('1999-09-09');
            $table->dateTime('inserted_at', 0);
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
        Schema::dropIfExists('metadata_fact_inventory');
    }
}
