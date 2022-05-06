<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnFactSaleManufacturingOrderedRevWeeklyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('fact_sale_manufacturing_ordered_rev_weekly', function ($table) {
            $table->integer('subcategory_sales_rank')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('fact_sale_manufacturing_ordered_rev_weekly', function ($table) {
            $table->unsignedMediumInteger('subcategory_sales_rank')->nullable()->change();
        });
    }
}
