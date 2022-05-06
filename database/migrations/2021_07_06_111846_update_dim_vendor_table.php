<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDimVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('dim_vendor', function ($table) {
            $table->string('vendor_alias', 64)->after('vendor_name')->nullable();
            $table->string('marketplace', 3)->after('tier')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('dim_vendor', function ($table) {
            $table->dropColumn('vendor_alias');
            $table->dropColumn('marketplace');
        });
    }
}
