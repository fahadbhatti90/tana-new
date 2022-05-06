<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMgmtKpiThresholdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mgmt_kpi_threshold', function (Blueprint $table) {
            $table->Increments('threshold_id');
            $table->unsignedSmallInteger('fk_user_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->unsignedSmallInteger('fk_kpi_id');
            $table->char('threshold_type',1);
            $table->decimal('threshold_value', 20, 2);
            $table->tinyInteger('is_active')->default('1');
            $table->timestamp('captured_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mgmt_kpi_threshold');
    }
}
