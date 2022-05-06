<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoreAmsCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_ams_campaign', function (Blueprint $table) {
            $table->increments('row_id');
            $table->integer('fk_vendor_id');
            $table->string('fk_profile_id', 25);
            $table->string('fk_entity_id', 25);
            $table->string('campaign_id', 25);
            $table->string('campaign_name', 225)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->nullable();
            $table->string('profile_name', 120)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->nullable();
            $table->string('campaign_status', 25)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->nullable();

            $table->decimal('campaign_budget', 10, 4)->nullable();
            $table->string('campaign_type', 64)->nullable();
            $table->unsignedMediumInteger('impressions')->nullable();
            $table->unsignedMediumInteger('clicks')->nullable();
            $table->unsignedDecimal('cost', 12, 4)->nullable();
            $table->unsignedDecimal('campaign_sales', 12, 4)->nullable();
            $table->unsignedDecimal('campaign_sales_sku', 12, 4)->nullable();
            $table->unsignedMediumInteger('campaign_units')->nullable();
            $table->unsignedMediumInteger('campaign_units_sku')->nullable();
            $table->unsignedDecimal('campaign_conversion', 12, 4)->nullable();
            $table->unsignedDecimal('campaign_conversion_sku', 12, 4)->nullable();

            $table->date('reported_date')->default('1999-09-09');
            $table->unsignedInteger('date_key');
            $table->timestamp("captured_at")->useCurrent();
            $table->index(['fk_profile_id', 'campaign_id'], 'idx_asin_subcategory');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('core_ams_campaign');
    }
}
