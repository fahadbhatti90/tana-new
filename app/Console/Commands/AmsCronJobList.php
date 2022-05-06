<?php

namespace App\Console\Commands;

use App\Mail\errorMail;
use App\Model\Ams\CronJob;
use App\Model\Ams\Tracker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AmsCronJobList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amscronjobs:cronlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command run every minute and check coming ams schedule job.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //dd(date('Y-m-d H:i:s', strtotime('-1 day', time())));
        $currentTimeNow = date('H');
        $reportTypeArray = [];
        $CronArrayResponse = CronJob::getAllEnabledCronList();
        if ($CronArrayResponse != FALSE) {
            // get enable cron lists
            Tracker::insertTrackRecord('get list of enabled schedules', 'record found');
            foreach ($CronArrayResponse as $singleCron) {
                // create variable CronRun
                $cronRunStatus = $singleCron->run_status;
                // create variable cronType
                $cronType = $singleCron->cron_slag;
                // convert cron into hour
                $cronTime = date('H', strtotime($singleCron->cron_time));
                // create variable for last Time Cron
                $cronLastRun = $singleCron->last_run;
                // check last cron time
                if ($cronLastRun == null) {
                    $lastDateTimeFormat = date('Y-m-d H:i:s', strtotime('-1 day', time()));
                    $cronLastRun = date('Y-m-d H', strtotime($lastDateTimeFormat));
                } else {
                    $cronLastRun = date('Y-m-d H', strtotime($cronLastRun));
                }
                // create variable for Next Time Cron
                $nextRunTime = $singleCron->next_run;
                // check Next Cron Time is not NA
                if ($nextRunTime == null) {
                    $nextRunTime = date('Y-m-d H:i:s', strtotime('+1 day', time()));
                    $nextRunTime = date('Y-m-d H', strtotime($nextRunTime));
                } else {
                    $nextRunTime = date('Y-m-d H', strtotime($nextRunTime));
                    // if next time is greater than last time
                    if ($cronLastRun > $nextRunTime) {
                        $nextRunTime = date('Y-m-d H:i:s', strtotime('+1 day', time()));
                        $nextRunTime = date('Y-m-d H', strtotime($nextRunTime));
                    }
                }
                // currently Retort Status
                $checkReportStatus = CronJob::where('cron_slag', $singleCron->cron_slag)->get()->first();
                if (empty($checkReportStatus)) {
                    Log::info('Schedule Table(tbl_ams_cron_jobs) is empty.');
                }
                // check Current system Time equal to Cron Set Time
                // Check Last run cron time less than coming next Cron time
                if ($cronTime == $currentTimeNow && $cronLastRun < $nextRunTime && $checkReportStatus->run_status == 0) {
                    // tracker code
                    Tracker::insertTrackRecord('got enabled schedule type ' . $cronType, 'record found');
                    // Update Token
                    Artisan::call('getaccesstoken:amsauth');
                    //store cron type in variable to check for etl_core sp
                    if ($cronType == 'campaigns_sp') {
                        $reportTypeArray[0] = $cronType;
                    } elseif ($cronType == 'campaigns_sb') {
                        $reportTypeArray[1] = $cronType;
                    } elseif ($cronType == 'campaigns_sd') {
                        $reportTypeArray[2] = $cronType;
                    }
                    // call function gathering api data
                    $this->innerFunction($singleCron);
                    // change is running status on completion
                    $checkReportStatus->is_running = 0;
                    $checkReportStatus->save();
                } elseif ($cronRunStatus == 1 && $cronTime < $currentTimeNow) { // change cronRun status again 0
                    // tracker code
                    Tracker::insertTrackRecord('change enabled schedule type ' . $cronType, 'success');
                    Log::info('start update query for update next_run status to 0');
                    $updateArray = array(
                        'modified_date' => date('Y-m-d H:i:s'),
                        'run_status' => '0',
                    );
                    CronJob::updateCronRunStatus($cronType, $updateArray);
                    Log::info('end update query for update run_status status to 0');
                }
            } // end foreach loop
            Log::info('End foreach loop');
        } else {
            Log::info('not record found');
        }
        Log::info('End Schedule for AMS');
    }

    /**
     * This function is used to run until cron status '1'
     *
     * @param $data
     * @return mixed
     */
    private function innerFunction($data)
    {
        if ($data->run_status == 0) {
            // update cron status when it done on time
            $updateArray = array(
                'last_run' => date('Y-m-d H:i:s'),
                'next_run' => date('Y-m-d H:i:s', strtotime('+1 day', time())),
                'modified_date' => date('Y-m-d H:i:s'),
                'run_status' => '1',
                'recover' => '0',
                'recover_back_from_date' => date('Y-m-d H:i:s'),
            );
            $data->update($updateArray);
            // tracker code
            Tracker::insertTrackRecord('got enabled schedule status : 0', 'record found');
            // Create variable for Report Type
            $ReportType = $data->cron_slag;
            switch ($ReportType) {
                case "campaigns_sp":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getcampaignreportid:spcampaign');
                    // Second Get Report Link
                    Artisan::call('getcampaignreportlink:spcampaign');
                    // Third Get Report Data From Link
                    Artisan::call('getcampaignreportlinkdata:spcampaign');
                    Log::info('start sp identifire ');
                    $CronArrayResponse = CronJob::spIdentifire('Campaing', 'SP');
                    if (!empty($CronArrayResponse)) {
                        Log::info('Sp identifire reponse error occured ');
                    }
                    Log::info('sp identifire succeeded ');
                    break;
                case "ad_group_sp":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getadgroupreportid:spadgroup');
                    // Second Get Report Link
                    Artisan::call('getadgroupreportlink:spadgroup');
                    // Third Get Report Data From Link
                    Artisan::call('getadgroupreportlinkdata:spadgroup');
                    break;
                case "keyword_sp":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getkeywordreportid:spkeyword');
                    // Second Get Report Link
                    Artisan::call('getkeywordreportlink:spkeyword');
                    // Third Get Report Data From Link
                    Artisan::call('getkeywordreportlinkdata:spkeyword');
                    break;
                case "search_term_sp":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getsearchtermreportid:spsearchterm');
                    // Second Get Report Link
                    Artisan::call('getsearchtermreportlink:spsearchterm');
                    // Third Get Report Data From Link
                    Artisan::call('getsearchtermreportlinkdata:spsearchterm');
                    break;
                case "products_ads_sp":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getproductsadsreportid:productsads');
                    // Second Get Report Link
                    Artisan::call('getproductsadsreportlink:productsads');
                    // Third Get Report Data From Link
                    Artisan::call('getproductsadsreportlinkdata:productsads');
                    break;
                case "asin_sp":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getasinreportid:spasin');
                    // Second Get Report Link
                    Artisan::call('getasinreportlink:spasin');
                    // Third Get Report Data From Link
                    Artisan::call('getasinreportlinkdata:spasin');
                    break;
                case "products_targets_sp":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('gettargetreportid:targets');
                    // Second Get Report Link
                    Artisan::call('gettargetreportlink:targets');
                    // Third Get Report Data From Link
                    Artisan::call('gettargetreportlinkdata:targets');
                    break;
                case "keyword_sb": // keyword SB reports
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getkeywordreportid:sbkeyword');
                    // Second Get Report Link
                    Artisan::call('getkeywordreportlink:sbkeyword');
                    // Third Get Report Data From Link
                    Artisan::call('getkeywordreportlinkdata:sbkeyword');
                    break;
                case "campaigns_sb":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getcampaignreportid:sbcampaign');
                    // Second Get Report Link
                    Artisan::call('getcampaignreportlink:sbcampaign');
                    // Third Get Report Data From Link
                    Artisan::call('getcampaignreportlinkdata:sbcampaign');
                    Log::info('start sp identifire ');
                    $CronArrayResponse = CronJob::spIdentifire('Campaing', 'SB');
                    if (!empty($CronArrayResponse)) {
                        Log::info('Sp identifire reponse error occured ');
                    }
                    Log::info('sp identifire succeeded ');
                    break;
                case "campaigns_sd":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getcampaignreportid:sdcampaign');
                    // Second Get Report Link
                    Artisan::call('getcampaignreportlink:sdcampaign');
                    // Third Get Report Data From Link
                    Artisan::call('getcampaignreportlinkdata:sdcampaign');
                    Log::info('start sp identifire ');
                    $CronArrayResponse = CronJob::spIdentifire('Campaing', 'SD');
                    if (!empty($CronArrayResponse)) {
                        Log::info('Sp identifire reponse error occured ');
                    }
                    Log::info('sp identifire succeeded ');
                    break;
                case "audiences_campaigns_sd":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getCampaignReportId:sdAudiencesCampaign');
                    // Second Get Report Link
                    Artisan::call('getCampaignReportLink:sdAudiencesCampaign');
                    // Third Get Report Data From Link
                    Artisan::call('getCampaignReportLinkData:sdAudiencesCampaign');
                    Log::info('start sp identifire ');
                    $CronArrayResponse = CronJob::spIdentifire('Campaing', 'SD');
                    if (!empty($CronArrayResponse)) {
                        Log::info('Sp identifire reponse error occured ');
                    }
                    Log::info('sp identifire succeeded ');
                    break;
                case "ad_group_sd":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getsdadgroupreportid:sdadgroup');
                    // Second Get Report Link
                    Artisan::call('getsdadgroupreportlink:sdadgroup');
                    // Third Get Report Data From Link
                    Artisan::call('getsdadgroupreportlinkdata:sdadgroup');
                    break;
                case "audiences_ad_group_sd":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getSdAdGroupReportId:sdAudiencesAdGroup');
                    // Second Get Report Link
                    Artisan::call('getSdAdgroupReportLink:sdAudiencesAdGroup');
                    // Third Get Report Data From Link
                    Artisan::call('getSdAdGroupReportLinkData:sdAudienceAdGroup');
                    break;
                case "products_ads_sd":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getsdproductsadsreportid:sdproductsads');
                    // Second Get Report Link
                    Artisan::call('getsdproductsadsreportlink:sdproductsads');
                    // Third Get Report Data From Link
                    Artisan::call('getsdproductsadsreportlinkdata:sdproductsads');
                    break;
                case "audiences_products_ads_sd":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getSdProductsAdsReportId:sdAudiencesProductsAds');
                    // Second Get Report Link
                    Artisan::call('getSdProductsAdsReportLink:sdAudiencesProductsads');
                    // Third Get Report Data From Link
                    Artisan::call('getSdProductsAdsReportLinkData:sdAudiencesProductsAds');
                    break;
                case "targets_sd":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('gettargetreportid:sdTargets');
                    // Second Get Report Link
                    Artisan::call('gettargetreportlink:sdTargets');
                    // Third Get Report Data From Link
                    Artisan::call('gettargetreportlinkdata:sdTargets');
                    break;
                case "audiences_targets_sd":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('gettargetreportid:sdTargetsAudience');
                    // Second Get Report Link
                    Artisan::call('gettargetreportlink:sdTargetsAudience');
                    // Third Get Report Data From Link
                    Artisan::call('gettargetreportlinkdata:sdTargetsAudience');
                    break;
                case "ad_group_sb":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getsbadgroupreportid:sbadgroup');
                    // Second Get Report Link
                    Artisan::call('getsbadgroupreportlink:sbadgroup');
                    // Third Get Report Data From Link
                    Artisan::call('getsbadgroupreportlinkdata:sbadgroup');
                    break;
                case "products_targets_sb":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('gettargetreportid:sbtargets');
                    // Second Get Report Link
                    Artisan::call('gettargetreportlink:sbtargets');
                    // Third Get Report Data From Link
                    Artisan::call('gettargetreportlinkdata:sbtargets');
                    break;
                default:
                    Log::info('Report not selected.');
            } // end switch statement
        } else {
            // tracker code
            Tracker::insertTrackRecord('got enabled schedule status : 1', 'record found');
            Log::info('schedule status is 1');
        }
    }
}
