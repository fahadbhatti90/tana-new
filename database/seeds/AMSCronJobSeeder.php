<?php

use App\Model\Ams\CronJob;
use Illuminate\Database\Seeder;

class AMSCronJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CronJob::create(['cron_name' => 'Campaigns - SP', 'cron_slag' => 'campaigns_sp', 'cron_type' => 'Sponsored Product']);
        CronJob::create(['cron_name' => 'Products Ads - SP', 'cron_slag' => 'products_ads_sp', 'cron_type' => 'Sponsored Product']);
        CronJob::create(['cron_name' => 'Products Targets - SP', 'cron_slag' => 'products_targets_sp', 'cron_type' => 'Sponsored Product']);
        CronJob::create(['cron_name' => 'AdGroup - SP', 'cron_slag' => 'ad_group_sp', 'cron_type' => 'Sponsored Product']);
        CronJob::create(['cron_name' => 'Keyword - SP', 'cron_slag' => 'keyword_sp', 'cron_type' => 'Sponsored Product']);
        CronJob::create(['cron_name' => 'ASIN - SP', 'cron_slag' => 'asin_sp', 'cron_type' => 'Sponsored Product']);

        CronJob::create(['cron_name' => 'Campaigns - SB', 'cron_slag' => 'campaigns_sb', 'cron_type' => 'Sponsored Brand']);
        CronJob::create(['cron_name' => 'Products Targets - SB', 'cron_slag' => 'products_targets_sb', 'cron_type' => 'Sponsored Brand']);
        CronJob::create(['cron_name' => 'AdGroup - SB', 'cron_slag' => 'ad_group_sb', 'cron_type' => 'Sponsored Brand']);
        CronJob::create(['cron_name' => 'Keyword - SB', 'cron_slag' => 'keyword_sb', 'cron_type' => 'Sponsored Brand']);

        CronJob::create(['cron_name' => 'Campaigns - SD', 'cron_slag' => 'campaigns_sd', 'cron_type' => 'Sponsored Display']);
        CronJob::create(['cron_name' => 'AdGroup - SD', 'cron_slag' => 'ad_group_sd', 'cron_type' => 'Sponsored Display']);
        CronJob::create(['cron_name' => 'Targets - SD', 'cron_slag' => 'targets_sd', 'cron_type' => 'Sponsored Display']);
        CronJob::create(['cron_name' => 'Products Ads - SD', 'cron_slag' => 'products_ads_sd', 'cron_type' => 'Sponsored Display']);
        CronJob::create(['cron_name' => 'Targets - SD - Audience', 'cron_slag' => 'audiences_targets_sd', 'cron_type' => 'Sponsored Display']);

        CronJob::create(['cron_name' => 'Campaigns - SD - Audience', 'cron_slag' => 'audiences_campaigns_sd', 'cron_type' => 'Sponsored Display']);
        CronJob::create(['cron_name' => 'AdGroup - SD - Audience', 'cron_slag' => 'audiences_ad_group_sd', 'cron_type' => 'Sponsored Display']);
        CronJob::create(['cron_name' => 'Products Ads - SD - Audience', 'cron_slag' => 'audiences_products_ads_sd', 'cron_type' => 'Sponsored Display']);
    }
}
