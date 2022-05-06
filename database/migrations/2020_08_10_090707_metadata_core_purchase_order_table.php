<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MetadataCorePurchaseOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadata_core_purchase_order', function (Blueprint $table) {

            $table->increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->date('max_ordered_on')->default('1999-09-09');
            $table->timestamp('inserted_at')->useCurrent();
            $table->index(['fk_vendor_id', 'max_ordered_on'], 'idx_vendor_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metadata_core_purchase_order');
    }
}
