<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStgPurchaseOrderSummary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stg_purchase_order_summary', function($table) {
            $table->dropColumn('po');
            $table->unsignedMediumInteger('total_quantity_requested')->after('fk_vendor_id')->nullable();
            $table->unsignedMediumInteger('total_accepted_quantity')->after('total_quantity_requested')->nullable();
            $table->unsignedMediumInteger('total_quantity_received')->after('total_accepted_quantity')->nullable();
            $table->unsignedMediumInteger('total_quantity_outstanding')->after('total_quantity_received')->nullable();
            $table->unsignedMediumInteger('total_unit_cost')->after('total_quantity_outstanding')->nullable();
            $table->decimal('yoy', 8, 2)->after('confirmation_rate')->default(0.00);
            $table->dropColumn('total_cases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stg_purchase_order_summary', function($table) {
            $table->string('po', 10)->after('fk_vendor_id')->nullable();
            $table->dropColumn('total_quantity_requested');
            $table->dropColumn('total_accepted_quantity');
            $table->dropColumn('total_quantity_received');
            $table->dropColumn('total_quantity_outstanding');
            $table->dropColumn('total_unit_cost');
            $table->dropColumn('yoy');
            $table->unsignedMediumInteger('total_cases')->after('po')->nullable();
        });
    }
}
