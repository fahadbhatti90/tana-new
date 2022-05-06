<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMgmtVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mgmt_vendor', function ($table) {
            $table->string('vendor_alias', 64)->after('vendor_name')->nullable();
            $table->string('marketplace', 3)->after('tier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mgmt_vendor', function ($table) {
            $table->dropColumn('vendor_alias');
            $table->dropColumn('marketplace');
        });
    }
}
