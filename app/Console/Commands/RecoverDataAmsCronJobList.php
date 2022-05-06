<?php

namespace App\Console\Commands;

use App\Mail\errorMail;
use App\Model\Ams\CronJob;
use App\Model\Ams\Tracker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RecoverDataAmsCronJobList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amscronjobs:recovercronlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command run every minute and check any recovery.';

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
        $currentTimeNow = date('H');
        $reportTypeArray = [];
        $CronArrayResponse = CronJob::getRecoverCronList();
        if ($CronArrayResponse != FALSE) {
            // get enable cron lists
            Tracker::insertTrackRecord('get recovery list of schedules', 'record found');
            foreach ($CronArrayResponse as $singleCron) {
                // create variable cronType
                $cronType = $singleCron->cron_slag;

                // currently Retort Status
                $checkReportStatus = CronJob::where('cron_slag', $singleCron->cron_slag)->get()->first();
                if ($checkReportStatus->recover >= 1) {
                    Tracker::insertTrackRecord('got recover schedule type ' . $cronType, 'record found');
                    // Update Token
                    Artisan::call('getaccesstoken:amsauth');
                    //store cron type in variable to check for etl_core sp
                    if ($cronType == 'campaigns_sp') {
                        $reportTypeArray[0] = $cronType;
                    } elseif ($cronType == 'campaigns_sb') {
                        $reportTypeArray[1] = $cronType;
                    } elseif ($cronType == 'campaigns_sd') {
                        $reportTypeArray[2] = $cronType;
                    } // end else if
                    // call function gathering api data
                    $this->innerFunction($singleCron);
                    // change is running status on completion
                    $checkReportStatus->is_running = 0;
                    $checkReportStatus->save();
                }
            } // end foreach loop
        } else {
            Log::info('not record found');
        } // end else
    }

    /**
     * This function is used to run until cron status '1'
     *
     * @param $data
     * @return mixed
     */
    private function innerFunction($data)
    {
        if ($data->recover >= 1) {
            $daysBack = $data->recover;
            $recoverBackFromDate = strtotime($data->recover_back_from_date);
            // update cron recovery back to 0
            $updateArray = array(
                'recover' => '0',
                'recover_back_from_date' => date('Y-m-d H:i:s'),
            );
            $data->update($updateArray);
            // tracker code
            Tracker::insertTrackRecord('got recovery schedule', 'record found');
            // Create variable for Report Type
            $ReportType = $data->cron_slag;
            switch ($ReportType) {
                case "campaigns_sp":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ") " . $daysBack . "");
                    // First Get Report Id
                    Artisan::call('getcampaignreportid:spcampaign ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('getcampaignreportlink:spcampaign');
                    // Third Get Report Data From Link
                    Artisan::call('getcampaignreportlinkdata:spcampaign');
                    Log::info('start sp identifire ');
                    $CronArrayResponse = CronJob::spIdentifire('Campaing', 'SP');
                    if (!empty($CronArrayResponse)) {
                        Log::info('Sp identifire reponse error occured ');
                    } // end if
                    Log::info('sp identifire succeeded ');
                    break;
                case "ad_group_sp":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getadgroupreportid:spadgroup ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('getadgroupreportlink:spadgroup');
                    // Third Get Report Data From Link
                    Artisan::call('getadgroupreportlinkdata:spadgroup');
                    break;
                case "keyword_sp":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getkeywordreportid:spkeyword ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('getkeywordreportlink:spkeyword');
                    // Third Get Report Data From Link
                    Artisan::call('getkeywordreportlinkdata:spkeyword');
                    break;
                case "search_term_sp":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getsearchtermreportid:spsearchterm ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('getsearchtermreportlink:spsearchterm');
                    // Third Get Report Data From Link
                    Artisan::call('getsearchtermreportlinkdata:spsearchterm');
                    break;
                case "products_ads_sp":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getproductsadsreportid:productsads ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('getproductsadsreportlink:productsads');
                    // Third Get Report Data From Link
                    Artisan::call('getproductsadsreportlinkdata:productsads');
                    break;
                case "asin_sp":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getasinreportid:spasin ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('getasinreportlink:spasin');
                    // Third Get Report Data From Link
                    Artisan::call('getasinreportlinkdata:spasin');
                    break;
                case "products_targets_sp":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('gettargetreportid:targets ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('gettargetreportlink:targets');
                    // Third Get Report Data From Link
                    Artisan::call('gettargetreportlinkdata:targets');
                    break;
                case "keyword_sb": // keyword SB reports
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getkeywordreportid:sbkeyword ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('getkeywordreportlink:sbkeyword');
                    // Third Get Report Data From Link
                    Artisan::call('getkeywordreportlinkdata:sbkeyword');
                    break;
                case "campaigns_sb":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getcampaignreportid:sbcampaign ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('getcampaignreportlink:sbcampaign');
                    // Third Get Report Data From Link
                    Artisan::call('getcampaignreportlinkdata:sbcampaign');
                    Log::info('start sp identifire ');
                    $CronArrayResponse = CronJob::spIdentifire('Campaing', 'SB');
                    if (!empty($CronArrayResponse)) {
                        Log::info('Sp identifire reponse error occured ');
                    } // end if
                    Log::info('sp identifire succeeded ');
                    break;
                case "campaigns_sd":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getcampaignreportid:sdcampaign ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('getcampaignreportlink:sdcampaign');
                    // Third Get Report Data From Link
                    Artisan::call('getcampaignreportlinkdata:sdcampaign');
                    Log::info('start sp identifire ');
                    $CronArrayResponse = CronJob::spIdentifire('Campaing', 'SD');
                    if (!empty($CronArrayResponse)) {
                        Log::info('Sp identifire reponse error occured ');
                    }  // end if
                    Log::info('sp identifire succeeded ');
                    break;
                case "audiences_campaigns_sd":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getCampaignReportId:sdAudiencesCampaign ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('getCampaignReportLink:sdAudiencesCampaign');
                    // Third Get Report Data From Link
                    Artisan::call('getCampaignReportLinkData:sdAudiencesCampaign');
                    Log::info('start sp identifire ');
                    $CronArrayResponse = CronJob::spIdentifire('Campaing', 'SD');
                    if (!empty($CronArrayResponse)) {
                        Log::info('Sp identifire reponse error occured ');
                    }  // end if
                    Log::info('sp identifire succeeded ');
                    break;
                case "ad_group_sd":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getsdadgroupreportid:sdadgroup ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('getsdadgroupreportlink:sdadgroup');
                    // Third Get Report Data From Link
                    Artisan::call('getsdadgroupreportlinkdata:sdadgroup');
                    break;
                case "audiences_ad_group_sd":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getSdAdGroupReportId:sdAudiencesAdGroup ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('getSdAdgroupReportLink:sdAudiencesAdGroup');
                    // Third Get Report Data From Link
                    Artisan::call('getSdAdGroupReportLinkData:sdAudienceAdGroup');
                    break;
                case "products_ads_sd":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getsdproductsadsreportid:sdproductsads ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('getsdproductsadsreportlink:sdproductsads');
                    // Third Get Report Data From Link
                    Artisan::call('getsdproductsadsreportlinkdata:sdproductsads');
                    break;
                case "audiences_products_ads_sd":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getSdProductsAdsReportId:sdAudiencesProductsAds ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('getSdProductsAdsReportLink:sdAudiencesProductsads');
                    // Third Get Report Data From Link
                    Artisan::call('getSdProductsAdsReportLinkData:sdAudiencesProductsAds');
                    break;
                case "targets_sd":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('gettargetreportid:sdTargets ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('gettargetreportlink:sdTargets');
                    // Third Get Report Data From Link
                    Artisan::call('gettargetreportlinkdata:sdTargets');
                    break;
                case "audiences_targets_sd":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('gettargetreportid:sdTargetsAudience ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('gettargetreportlink:sdTargetsAudience');
                    // Third Get Report Data From Link
                    Artisan::call('gettargetreportlinkdata:sdTargetsAudience');
                    break;
                case "ad_group_sb":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('getsbadgroupreportid:sbadgroup ' . $daysBack . ' ' . $recoverBackFromDate);
                    // Second Get Report Link
                    Artisan::call('getsbadgroupreportlink:sbadgroup');
                    // Third Get Report Data From Link
                    Artisan::call('getsbadgroupreportlinkdata:sbadgroup');
                    break;
                case "products_targets_sb":
                    Log::info($data->cron_name . " (" . $data->cron_slag . ")");
                    // First Get Report Id
                    Artisan::call('gettargetreportid:sbtargets ' . $daysBack . ' ' . $recoverBackFromDate);
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
            Tracker::insertTrackRecord('got schedule recovery status : 0', 'record found');
            Log::info('schedule recovery status is 0');
        } // enf else
    }
}
