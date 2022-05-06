<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameMgmtVendorDomainColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mgmt_vendor', function ($table) {
            $table->renameColumn('domain', 'region');
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
            $table->renameColumn('region', 'domain');
        });
    }
}
