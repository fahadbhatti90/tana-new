<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMgmtAlertTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mgmt_alert', function (Blueprint $table) {
            $table->Increments('alert_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->unsignedSmallInteger('fk_user_id');
            $table->string('reported_attribute',48)->nullable();
            $table->string('sub_reported_attribute',48)->nullable();
            $table->string('sub_reported_value',128)->nullable();
            $table->decimal('reported_value', 20, 2)->nullable();
            $table->date('reported_date')->nullable();
            $table->date('trigger_date')->nullable();
            $table->string('report_name',48);
            $table->string('report_range',8);
            $table->string('report_graph',48);
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
        Schema::dropIfExists('mgmt_alert');
    }
}
