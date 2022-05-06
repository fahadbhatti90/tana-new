<?php

use App\Model\Ams\CronJob;
use Illuminate\Database\Seeder;

class AMSAudiencesCronJobSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CronJob::create(['cron_name' => 'Audiences Targets - SD', 'cron_slag' => 'audiences_targets_sd', 'cron_type' => 'Sponsored Display']);
    }
}
