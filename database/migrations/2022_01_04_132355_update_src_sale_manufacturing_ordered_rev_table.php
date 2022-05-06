<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSrcSaleManufacturingOrderedRevTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('src_sale_manufacturing_ordered_rev', function ($table) {
            $table->string('ordered_revenue_prior_period', 50)->change();
            $table->string('ordered_revenue_last_year', 50)->change();
            $table->string('ordered_units_prior_period', 50)->change();
            $table->string('ordered_units_last_year', 50)->change();

            $table->dropColumn('ordered_revenue_%_of_total');
            $table->dropColumn('ordered_units_%_of_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('src_sale_manufacturing_ordered_rev', function (Blueprint $table) {
            $table->string('ordered_revenue_%_of_total', 10)->after('ordered_revenue');
            $table->string('ordered_revenue_prior_period', 10)->change();
            $table->string('ordered_revenue_last_year', 10)->change();
            $table->string('ordered_units_%_of_total', 10)->after('ordered_units');
            $table->string('ordered_units_prior_period', 10)->change();
            $table->string('ordered_units_last_year', 10)->change();
        });
    }
}
