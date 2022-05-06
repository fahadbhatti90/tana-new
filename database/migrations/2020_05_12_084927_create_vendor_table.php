<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mgmt_vendor', function (Blueprint $table) {
            $table->smallIncrements('vendor_id');
            $table->string('vendor_name', 64);
            $table->string('domain', 3);
            $table->string('tier', 64);
            $table->unsignedTinyInteger('is_active')->default('1');
            $table->index('vendor_id');
            $table->timestamps();

            $table->unique(['vendor_name', 'domain']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mgmt_vendor');
    }
}
