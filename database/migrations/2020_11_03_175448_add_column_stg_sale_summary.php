<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStgSaleSummary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stg_sale_summary', function($table) {
            $table->decimal('yoy', 8, 2)->after('ptp_daily_shipped_units')->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stg_sale_summary', function($table) {
            $table->dropColumn('yoy');
        });
    }
}
