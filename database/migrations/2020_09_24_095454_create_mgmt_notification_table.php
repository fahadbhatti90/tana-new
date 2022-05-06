<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMgmtNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mgmt_notification', function (Blueprint $table) {
            $table->Increments('alert_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->unsignedSmallInteger('fk_user_id');
            $table->string('alert_name',128)->nullable();
            $table->string('reported_attribute',48)->nullable();
            $table->string('sub_reported_attribute',48)->nullable();
            $table->string('sub_reported_value',128)->nullable();
            $table->decimal('reported_value', 20, 2)->nullable();
            $table->date('reported_date')->nullable();
            $table->string('report_range', 8)->nullable();
            $table->date('trigger_date');
            $table->tinyInteger('is_viewed')->default(1);
            $table->tinyInteger('is_notified')->default(1);
            $table->tinyInteger('is_disabled')->default(1);
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
        Schema::dropIfExists('mgmt_notification');
    }
}
