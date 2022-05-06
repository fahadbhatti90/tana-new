<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FactPoDailySummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('fact_po_daily_summary', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->string('po', 10)->nullable();
            $table->unsignedMediumInteger('total_cases');
            $table->unsignedDecimal('total_cost', 11, 2)->nullable();
            $table->unsignedDecimal('confirmation_rate', 7, 2)->nullable();
            $table->date('ordered_on')->default('1999-09-09');
            $table->unsignedInteger('date_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('fact_po_daily_summary');
    }
}
