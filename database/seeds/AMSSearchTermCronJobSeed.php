<?php

use App\Model\Ams\CronJob;
use Illuminate\Database\Seeder;

class AMSSearchTermCronJobSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CronJob::create(['cron_name' => 'Search Term - SP', 'cron_slag' => 'search_term_sp', 'cron_type' => 'Sponsored Product']);
    }
}
