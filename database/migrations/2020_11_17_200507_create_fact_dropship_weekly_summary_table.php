<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactDropshipWeeklySummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('fact_dropship_weekly_summary', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->SmallInteger('fk_vendor_id');
            $table->decimal('item_cost', 14, 4)->nullable();
            $table->unsignedSmallInteger('item_quantity')->nullable();
            $table->date('start_date')->default('1999-09-09');
            $table->date('end_date');
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
        Schema::connection('mysql2')->dropIfExists('fact_dropship_weekly_summary');
    }
}
