<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMetadataCoreInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('metadata_core_inventory', function ($table) {
            $table->date('max_date_monthly')->after('max_received_date')->default('1999-09-09')->comment('For inventory monthly');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('metadata_core_inventory', function ($table) {
            $table->dropColumn('max_date_monthly');
        });
    }
}
