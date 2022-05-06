<?php
use App\Model\Ams\CronJob;
use Illuminate\Database\Seeder;

class AMSCronJobSeederV1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CronJob::create(['cron_name' => 'Campaigns - SD - Audience', 'cron_slag' => 'audiences_campaigns_sd', 'cron_type' => 'Sponsored Display']);
        CronJob::create(['cron_name' => 'AdGroup - SD - Audience', 'cron_slag' => 'audiences_ad_group_sd', 'cron_type' => 'Sponsored Display']);
        CronJob::create(['cron_name' => 'Products Ads - SD - Audience', 'cron_slag' => 'audiences_products_ads_sd', 'cron_type' => 'Sponsored Display']);
    }
}
