<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStgAmsKeywordSearchTermSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stg_ams_keyword_search_term_summary', function (Blueprint $table) {
            $table->bigIncrements('row_id');
            $table->unsignedBigInteger('profile_id');
            $table->string('entity_id', 50);
            $table->string('keyword_report_type',4)->nullable();

            $table->mediumInteger('impressions')->default(0);
            $table->mediumInteger('clicks')->default(0);
            $table->decimal('cost', 16, 4)->default(0.0000);

            $table->mediumInteger('conversions')->nullable();
            $table->mediumInteger('conversions_same_sku')->nullable();

            $table->mediumInteger('units_ordered')->nullable();
            $table->mediumInteger('units_ordered_same_sku')->nullable();

            $table->decimal('sales', 16, 4)->nullable();
            $table->decimal('sales_same_sku', 16, 4)->nullable();

            $table->date('reported_date')->default("1999-09-09");
            $table->integer('date_key')->default("19990909");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stg_ams_keyword_search_term_summary');
    }
}
