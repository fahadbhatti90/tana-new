<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsSrcSaleManufacturingOrderedRevTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('src_sale_manufacturing_ordered_rev', function ($table) {
            $table->string('ordered_revenue_%_of_total', 50)->after('ordered_revenue');
            $table->string('ordered_units_%_of_total', 50)->after('ordered_units');
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

            $table->dropColumn('ordered_revenue_%_of_total');
            $table->dropColumn('ordered_units_%_of_total');
        });
    }
}
