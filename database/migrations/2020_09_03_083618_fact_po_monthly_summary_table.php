<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FactPoMonthlySummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('fact_po_monthly_summary', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->unsignedMediumInteger('total_cases')->nullable();
            $table->unsignedDecimal('total_cost', 11, 2)->nullable();
            $table->unsignedDecimal('confirmation_rate', 7, 2)->nullable();
            $table->date('start_date')->default('1999-09-09');
            $table->date('end_date')->default('1999-09-09');
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
        Schema::connection('mysql2')->dropIfExists('fact_po_monthly_summary');
    }
}
