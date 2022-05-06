<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmsProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fk_access_token');
            $table->string('profile_id',50);
            $table->string('country_code',50);
            $table->string('currency_code',50);
            $table->string('time_zone');
            $table->string('market_place_string_id');
            $table->string('entity_id');
            $table->string('type');
            $table->string('name');
            $table->unsignedTinyInteger('ad_group_sp_sixty_days')->default(0);
            $table->unsignedTinyInteger('asins_sixty_days')->default(0);
            $table->unsignedTinyInteger('campaign_sp_sixty_days')->default(0);
            $table->unsignedTinyInteger('keyword_sb_sixty_days')->default(0);
            $table->unsignedTinyInteger('keyword_sp_sixty_days')->default(0);
            $table->unsignedTinyInteger('product_ads_sixty_days')->default(0);
            $table->unsignedTinyInteger('product_targeting_sixty_days')->default(0);
            $table->unsignedTinyInteger('sponsored_brand_campaigns_sixty_days')->default(0);
            $table->unsignedTinyInteger('sponsored_display_campaigns_sixty_days')->default(0);
            $table->unsignedTinyInteger('sponsored_display_adgroup_sixty_days')->default(0);
            $table->unsignedTinyInteger('sponsored_display_productads_sixty_days')->default(0);
            $table->unsignedTinyInteger('sponsored_brand_adgroup_sixty_days')->default(0);
            $table->unsignedTinyInteger('sponsored_brand_targeting_sixty_days')->default(0);
            $table->unsignedTinyInteger('is_sandbox_profile')->default(0);
            $table->unsignedTinyInteger('is_active')->default(0);
            $table->text('status')->nullable();
            $table->dateTime('creation_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ams_profiles');
    }
}
