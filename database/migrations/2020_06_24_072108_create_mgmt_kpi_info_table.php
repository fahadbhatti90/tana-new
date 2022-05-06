<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMgmtKpiInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mgmt_kpi_info', function (Blueprint $table) {
            $table->smallIncrements('kpi_id');
            $table->string('kpi_name',48);
            $table->string('sub_kpi_name',48);
            $table->string('sub_kpi_value',255);
            $table->string('report_name',28);
            $table->string('report_range',8);
            $table->string('report_graph',48);
            $table->string('report_table',48);
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
        Schema::dropIfExists('mgmt_kpi_info');
    }
}
