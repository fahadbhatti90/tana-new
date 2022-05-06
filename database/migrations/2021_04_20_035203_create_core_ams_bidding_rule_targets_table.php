<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoreAmsBiddingRuleTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_ams_bidding_rule_targets', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedBigInteger('profile_id');
            $table->string('entity_id', 50);
            $table->unsignedBigInteger('campaign_id');
            $table->string('ad_group_id', 50)->nullable();
            $table->unsignedBigInteger('target_id');

            $table->string('campaign_name', 255)->nullable();
            $table->string('ad_group_name', 255)->nullable();
            $table->string('targeting_expression',150)->nullable();
            $table->string('targeting_text', 255)->nullable();
            $table->string('targeting_type', 150)->nullable();

            $table->unsignedInteger('impressions');
            $table->unsignedInteger('clicks');
            $table->unsignedDecimal('cost', 16, 4);

            $table->unsignedDecimal('targets_sales', 16, 4);
            $table->decimal('targets_sales_sku', 16, 4)->nullable();

            $table->integer('targets_conversions')->nullable();
            $table->integer('targets_conversions_sku')->nullable();

            $table->integer('targets_units')->nullable();

            $table->date('reported_date');
            $table->integer('date_key');
            $table->timestamp('captured_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('core_ams_bidding_rule_targets');
    }
}
