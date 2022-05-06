<?php

namespace App\Console\Commands;

use App\Mail\biddingRuleMail;
use App\Model\Ams\BiddingRule\BiddingRule;
use App\Model\Ams\BiddingRule\BiddingRuleTracker;
use App\Model\Ams\BiddingRule\Cron;
use App\Model\Ams\Campaign;
use App\Model\Ams\Tracker;
use App\Model\User;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BiddingRuleCronJobList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biddingRuleCronJobs:cronList';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command run every minute and check coming bidding rules.';

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
     * @throws \Exception
     */
    public function handle()
    {
        $currentTimeNow = date('Y-m-d H');
        $CronArrayResponse = Cron::getAllEnabledCronList();

        if ($CronArrayResponse != FALSE) {
            // get enable cron lists
            Tracker::insertTrackRecord('get list of enabled schedules', 'record found');
            foreach ($CronArrayResponse as $singleCron) {
                // create variable CronRun
                $cronRunStatus = $singleCron->run_status;
                // create variable cronType
                $cronID = $singleCron->id;
                $cronType = $singleCron->rule_ad_type;
                // create variable for last Time Cron
                $cronLastRun = $singleCron->last_run;
                $cronCurrentRun = $singleCron->current_run;
                $cronNextRun = $singleCron->next_run;
                $cronFrequency = $singleCron->frequency_days;
                // convert cron into hour
                $cronTime = date('Y-m-d H', strtotime($cronCurrentRun));

                $lastRunDate = date('Y-m-d', strtotime($cronLastRun));
                $currentRunDate = date('Y-m-d');

                // check last cron time
                if ($cronLastRun == null) {
                    $lastDateTimeFormat = date('Y-m-d H:i:s', strtotime('-' . $cronFrequency . ' day', time()));
                    $cronLastRun = date('Y-m-d H', strtotime($lastDateTimeFormat));
                    $lastRunDate = date('Y-m-d', strtotime($lastDateTimeFormat));
                } else {
                    $cronLastRun = date('Y-m-d H', strtotime($cronLastRun));
                }

                if ($cronNextRun == null) {
                    $cronNextRun = date('Y-m-d H:i:s');
                    $cronNextRun = date('Y-m-d H', strtotime($cronNextRun));
                } else {
                    $cronNextRun = date('Y-m-d H', strtotime($cronNextRun));
                    // if next time is greater than last time
                    if ($cronLastRun > $cronNextRun) {
                        $cronNextRun = date('Y-m-d H:i:s', strtotime('+' . $cronFrequency . ' day', time()));
                        $cronNextRun = date('Y-m-d H', strtotime($cronNextRun));
                    }
                }
                // currently Retort Status
                $checkReportStatus = Cron::where('id', $singleCron->id)->get()->first();
                if (empty($checkReportStatus)) {
                    Log::info('Schedule Table(tbl_ams_bidding_rule_cron) is empty.');
                }
                // check Current system Time equal to Cron Set Time
                // Check Last run cron time less than coming next Cron time
                $diff = date_diff(new DateTime($lastRunDate), new DateTime($currentRunDate));
                $LastRunDifference = $diff->format("%a");

                if ($cronTime == $currentTimeNow && $cronLastRun < $cronNextRun && $LastRunDifference == $cronFrequency && $checkReportStatus->run_status == 0) {
                    // tracker code
                    Tracker::insertTrackRecord('got enabled schedule type ' . $cronType, 'record found');

                    // call function gathering api data
                    $this->innerFunction($singleCron);
                } elseif ($cronRunStatus == 1 && $cronTime == $currentTimeNow && $LastRunDifference == $cronFrequency) { // change cronRun status again 0
                    // tracker code
                    Tracker::insertTrackRecord('change enabled schedule type ' . $cronType, 'success');
                    $updateArray = array(
                        'run_status' => '0',
                        'check_rule_status' => '0',
                        'rule_result' => '0',
                        'email_send_status' => '0',
                    );
                    Cron::updateCronRunStatus($cronID, $updateArray);
                    $singleCron->setCampaignsDoneStatus();
                }
            } // end foreach loop
            Log::info('End foreach loop');
        } else {
            Log::info('not record found');
        } // end if else
        Log::info('End Schedule for AMS');
    }

    /**
     * This function is used to run until cron status '1'
     *
     * @param $data
     * @return mixed
     * @throws \Box\Spout\Common\Exception\IOException
     */
    private function innerFunction($data)
    {
        if ($data->run_status == 0) {
            // update cron status when it done on time
            $updateArray = array(
                'last_run' => date('Y-m-d H:i:s'),
                'next_run' => date('Y-m-d H:i:s', strtotime('+' . $data->frequency_days . ' day', time())),
                'current_run' => date('Y-m-d H:i:s', strtotime('+' . $data->frequency_days . ' day', time())),
                'run_status' => '1',
            );
            $data->update($updateArray);
            Log::info('End Update Query');

            $ruleData = BiddingRule::findOrFail($data->fk_bidding_rule_id);
            $profileInfo = $ruleData->getProfileInfo();
            $triggeredData = array();

            $fileName = "";
            $look_back_range = "";

            if ($profileInfo->is_active) {

                // populate Bid Data again bidding rule cron
                Artisan::call('populateBidData:bidding_rule');

                // Add/Update List of Keywords (SP / SB)
                Artisan::call('keywordlist:amsKeywordlist ' . $data->id);
                // Add/Update List of Targets (SD)
                Artisan::call('targetlist:amsTargetlist ' . $data->id);

                $writer = WriterEntityFactory::createXLSXWriter();

                $fileName = str_replace(" ", "_", $ruleData->id . "_" . $ruleData->rule_name . "_" . date('Y_m_d'));
                $fileName = str_replace("/", "_", $fileName);
                $fileName = public_path("uploads/biddingRule/" . $fileName . ".xlsx");
                $writer->openToFile($fileName);

                $sheet = $writer->getCurrentSheet();
                $sheet->setName('Rule Detail');

                // Shortcut: add a row from an array of values
                $values = ['Rule Name', 'Look Back Period', 'Look Back Range', 'Frequency', 'Bidding Rule Conditions!'];
                $rowFromValues = WriterEntityFactory::createRowFromArray($values);
                $writer->addRow($rowFromValues);

                $statement = $ruleData->getStatment();

                $look_back_period = ucwords(str_replace("_", " ", $ruleData->look_back_period));

                $look_back_day_end = date('Y-m-d', strtotime('-' . (14 + 1) . 'day', time()));
                $look_back_day_start = date('Y-m-d', strtotime('-' . (14 + $ruleData->look_back_period_days) . ' day', time()));
                $look_back_range = $look_back_day_start . ' - ' . $look_back_day_end;

                $frequency = ucwords(str_replace("_", " ", $ruleData->frequency));

                $values = [$ruleData->rule_name, $look_back_period, $look_back_range, $frequency, $statement];
                $rowFromValues = WriterEntityFactory::createRowFromArray($values);
                $writer->addRow($rowFromValues);

                $values = [''];
                $rowFromValues = WriterEntityFactory::createRowFromArray($values);
                $writer->addRow($rowFromValues);

                $values = ['** if Dash (-) is shown in the Rule Check Data sheet against the column of Select Type Text, this means data is not available in that Look Back Period range from AMS API.'];
                $rowFromValues = WriterEntityFactory::createRowFromArray($values);
                $writer->addRow($rowFromValues);

                $newSheet = $writer->addNewSheetAndMakeItCurrent();
                $newSheet->setName('Rule Check Data');

                $values = ['Campaign Id', 'Campaign Name', 'AdGroup Id', 'Advertising Type', 'Select Type', 'Select Type Id', 'Select Type Text', 'Impressions', 'Clicks', 'Cost', 'Revenue', 'ROAS', 'ACOS', 'CPC', 'CPA', 'Old Bid', 'New Bid', 'Check Status'];
                $rowFromValues = WriterEntityFactory::createRowFromArray($values);
                $writer->addRow($rowFromValues);

                $campaigns = $data->getCampaignsInfo()->get();

                if (!empty($campaigns)) {
                    foreach ($campaigns as $campaign) {
                        $ruleCampaignData = null;
                        $campaign_type = "";
                        if ($campaign->rule_ad_type != "SD") {
                            Log::info("Get keyword data again rule");
                            $campaign_type = "keyword";
                            $ruleCampaignData = BiddingRule::getKeywordData($campaign->campaign_id, $campaign->rule_ad_type);
                            if (!$ruleCampaignData) {
                                $campaign_type = "target";
                                $ruleCampaignData = BiddingRule::getTargetData($campaign->campaign_id, $campaign->rule_ad_type);
                            } // end else if statement
                        } else {
                            Log::info("Get target data again rule");
                            $campaign_type = "target";
                            $ruleCampaignData = BiddingRule::getTargetData($campaign->campaign_id, $campaign->rule_ad_type);
                        } // end else statement
                        if (!empty($ruleCampaignData)) {
                            foreach ($ruleCampaignData as $singleData) {
                                $profile_id = $singleData->profile_id;
                                $ad_group_id = $singleData->ad_group_id;
                                $campaign_id = $singleData->campaign_id;
                                $keyword_id = isset($singleData->keyword_id) ? $singleData->keyword_id : null;
                                $target_id = isset($singleData->target_id) ? $singleData->target_id : null;
                                $state = $singleData->state;
                                $bid = $singleData->bid;
                                $ad_type = $singleData->ad_type;
                                $look_back_period_days = $data->look_back_period_days;

                                $bid_data = array(
                                    'profile_id' => $profile_id,
                                    'campaign_id' => $campaign_id,
                                    'ad_group_id' => $ad_group_id,
                                    'state' => $state,
                                    'ad_type' => $ad_type,
                                    'old_bid' => $bid,
                                );

                                if ($campaign_type != "target") {
                                    $bid_data['keyword_id'] = $keyword_id;
                                } else {
                                    $bid_data['target_id'] = $target_id;
                                } // end else statement
                                $reportResponse = null;

                                if ($campaign_type != "target") {
                                    Log::info("Calculate metrics data again Campaign: " . $campaign_id . " & keyword: " . $keyword_id);
                                    $reportResponse = BiddingRule::calculateKeywordBiddingRule($campaign_id, $keyword_id, $ad_type, (int)$look_back_period_days);
                                } else {
                                    Log::info("Calculate metrics data again Campaign: " . $campaign_id . " & Targets: " . $target_id);

                                    $campaigninfo = Campaign::where('campaign_id', $campaign->campaign_id)->get()->first();
                                    if ($campaigninfo->tactic == 'T00030') {
                                        //if campaign tactics is audience type than
                                        $reportResponse = BiddingRule::calculateAudienceTargetsBiddingRule($campaign_id, $target_id, $ad_type, (int)$look_back_period_days);
                                    } else {
                                        $reportResponse = BiddingRule::calculateTargetsBiddingRule($campaign_id, $target_id, $ad_type, (int)$look_back_period_days);
                                    }
                                } // end else statement
                                if (!empty($reportResponse)) {
                                    $updateArray = array(
                                        'check_rule_status' => '1',
                                    );
                                    $data->update($updateArray);

                                    $arrayReportResponse = (array)$reportResponse[0];

                                    if (!empty($ruleData)) {
                                        $conditionText = "";
                                        $metricList = explode(',', $ruleData->metric);
                                        $conditionList = explode(',', $ruleData->condition);
                                        $integerValuesList = explode(',', $ruleData->integer_values);

                                        for ($i = 0; $i < count($metricList); $i++) {
                                            $condition = "=="; //default equal to
                                            switch ($conditionList[$i]) {
                                                case "greater_than":
                                                    $condition = ">"; //for greater than
                                                    break;
                                                case "less_than":
                                                    $condition = "<"; //for less than
                                                    break;
                                                case "greater_than_equal_to":
                                                    $condition = ">="; //for greater than equal to
                                                    break;
                                                case "less_than_equal_to":
                                                    $condition = "<="; //for less than equal to
                                                    break;
                                                case "equal_to":
                                                    $condition = "=="; //for equal to
                                                    break;
                                                default:
                                                    $condition = "=="; //default equal to
                                            } // end switch statement

                                            $and = "";
                                            if ($ruleData->and_or != "NA") {
                                                $and = "&&";
                                                if ($ruleData->and_or == "or") {
                                                    $and = "||";
                                                } // end if statement
                                            } // end if statement
                                            if ($metricList[$i] == "bid") {
                                                $conditionText .= '(' . $bid . ' ' . $condition . ' ' . $integerValuesList[$i] . ')' . (($i == 1) ? '' : $and);
                                            } else {
                                                $conditionText .= '(' . $arrayReportResponse[$metricList[$i]] . ' ' . $condition . ' ' . $integerValuesList[$i] . ')' . (($i == 1) ? '' : $and);
                                            } // end else
                                        } // end for statement
                                        // eval() function evaluates a string as PHP code.
                                        $result = eval("return (" . $conditionText . ");");
                                        $ruleCheck = "FALSE";
                                        $increaseBidValue = 0.0;
                                        $bid_data['new_bid'] = $increaseBidValue;
                                        $bid_data['check_status'] = $ruleCheck;
                                        if ($result) {
                                            Log::info("Rule condition is succeeded");
                                            $ruleCheck = "TRUE";
                                            $updateArray = array(
                                                'rule_result' => '1',
                                            );
                                            $data->update($updateArray);

                                            $bidBy = $ruleData->bid_by_value;
                                            $old_cpc = $arrayReportResponse['CPC'];

                                            if (!empty($ruleData->bid_cpc_type) && $ruleData->bid_cpc_type == 'cpc') {
                                                if ($ruleData->then_clause == "raise") {
                                                    $increaseBidValue = $old_cpc + abs($bidBy);
                                                    if ($ruleData->bid_by_type != "dollar") {
                                                        $increaseBidValue = round(abs((($bidBy / 100) * $old_cpc) + $old_cpc), 2);
                                                    } // end if statement
                                                } else {
                                                    $increaseBidValue = $old_cpc - abs($bidBy);
                                                    if ($ruleData->bid_by_type != "dollar") {
                                                        if ($bidBy >= 0 && $bidBy <= 100) {
                                                            $increaseBidValue = round(abs((($bidBy / 100) * $old_cpc) - $old_cpc), 2);
                                                        } else {
                                                            $increaseBidValue = round(abs(($old_cpc) - $old_cpc), 2);
                                                        } // end else statement
                                                    } // end if statement
                                                } // end else statement
                                            } else {
                                                if ($ruleData->then_clause == "raise") {
                                                    $increaseBidValue = $bid + abs($bidBy);
                                                    if ($ruleData->bid_by_type != "dollar") {
                                                        $increaseBidValue = round(abs((($bidBy / 100) * $bid) + $bid), 2);
                                                    } // end if statement
                                                } else {
                                                    $increaseBidValue = $bid - abs($bidBy);
                                                    if ($ruleData->bid_by_type != "dollar") {
                                                        if ($bidBy >= 0 && $bidBy <= 100) {
                                                            $increaseBidValue = round(abs((($bidBy / 100) * $bid) - $bid), 2);
                                                        } else {
                                                            $increaseBidValue = round(abs(($bid) - $bid), 2);
                                                        } // end else statement
                                                    } // end if statement
                                                } // end else statement
                                            }
                                            $bid_data['new_bid'] = $increaseBidValue;
                                            $bid_data['check_status'] = $ruleCheck;
                                            if (env('APP_ENV') == 'production') {
                                                if ($increaseBidValue >= 0.02) {
                                                    Log::info("Calling actual bid change call (PUT)");
                                                    Artisan::call('updateReportBid:updateBid', ['token_id' => $campaign->fk_access_token, 'campaign_type' => $campaign_type, '--bid_data' => $bid_data]);
                                                } else {
                                                    $bid_data['new_bid'] = 0.0;
                                                    $bid_data['check_status'] = 'FALSE';
                                                    Log::info("New Bid value is less then minimum: 0.02");
                                                } //end if else statement
                                            } //end if statement

                                        } //end if statement
                                        BiddingRuleTracker::insertTrackRecord($bid_data);
                                        $ad_type_information = $bid_data['ad_type'];
                                        $campaignInfo = Campaign::where('campaign_id', $bid_data['campaign_id'])->get()->first();
                                        $bid_data['campaign_name'] = $campaignInfo->name;
                                        if ($campaignInfo->targeting_type != "NA") {
                                            $ad_type_information = $bid_data['ad_type'] . " " . $campaignInfo->targeting_type;
                                        } // end  if statement

                                        if ($campaignInfo->tactic != "NA") {
                                            if ($campaignInfo->tactic == "T00030") {
                                                $ad_type_information = $bid_data['ad_type'] . " Audience Targeting";
                                            } else {
                                                $ad_type_information = $bid_data['ad_type'] . " Product Targeting";
                                            } // end  else statement
                                        } // end  if statement

                                        $bid_data['ad_type_information'] = $ad_type_information;

                                        $cells = [
                                            WriterEntityFactory::createCell("'" . $bid_data['campaign_id'] . "'"),
                                            WriterEntityFactory::createCell($bid_data['campaign_name']),
                                            WriterEntityFactory::createCell("'" . $bid_data['ad_group_id'] . "'"),
                                            WriterEntityFactory::createCell($bid_data['ad_type_information']),
                                            WriterEntityFactory::createCell(isset($bid_data['keyword_id']) ? 'Keyword' : 'Target'),
                                            WriterEntityFactory::createCell(isset($bid_data['keyword_id']) ? "'" . $bid_data['keyword_id'] . "'" : "'" . $bid_data['target_id'] . "'"),
                                            WriterEntityFactory::createCell(isset($arrayReportResponse['keyword_text']) ? $arrayReportResponse['keyword_text'] : $arrayReportResponse['targeting_text']),
                                            WriterEntityFactory::createCell($arrayReportResponse['impressions']),
                                            WriterEntityFactory::createCell($arrayReportResponse['clicks']),
                                            WriterEntityFactory::createCell($arrayReportResponse['cost']),
                                            WriterEntityFactory::createCell($arrayReportResponse['revenue']),
                                            WriterEntityFactory::createCell($arrayReportResponse['ROAS']),
                                            WriterEntityFactory::createCell($arrayReportResponse['ACOS']),
                                            WriterEntityFactory::createCell($arrayReportResponse['CPC']),
                                            WriterEntityFactory::createCell($arrayReportResponse['CPA']),
                                            WriterEntityFactory::createCell($bid_data['old_bid']),
                                            WriterEntityFactory::createCell($bid_data['new_bid']),
                                            WriterEntityFactory::createCell($bid_data['check_status']),
                                        ];
                                        if ($bid_data['check_status'] == "TRUE") {
                                            $bid_data['ad_type_text'] = isset($arrayReportResponse['keyword_text']) ? $arrayReportResponse['keyword_text'] : $arrayReportResponse['targeting_text'];
                                            array_push($triggeredData, $bid_data);
                                        }
                                        $row = WriterEntityFactory::createRow($cells);
                                        $writer->addRow($row);
                                        unset($bid_data);
                                    } //end if statement
                                } else {
                                    Log::error("metrics data not found");
                                } // end else statement
                            } //end foreach statement
                        } // end if statement
                        Log::error("Change is_done status for portfolio/campaign data cron");
                        $campaign->update([
                            'is_done' => '1'
                        ]);
                    } // end foreach statement
                } // end if statement
                $writer->close();
            } else {
                // update cron status when it done on time
                Log::info('Rule Profile is inactive');
                $updateArray = array(
                    'last_run' => date('Y-m-d H:i:s'),
                    'next_run' => date('Y-m-d H:i:s', strtotime('+' . $data->frequency_days . ' day', time())),
                    'current_run' => date('Y-m-d H:i:s', strtotime('+' . $data->frequency_days . ' day', time())),
                    'run_status' => '0',
                    "is_active" => '0',
                );
                $data->update($updateArray);

                $ruleData->is_active = 0;
                $ruleData->save();
            } // end else

            $user = User::findorFail($ruleData->fk_user_id);
            $cc_mail_list = explode(',', $ruleData->cc_emails);
            if (sizeof($cc_mail_list) == 1 && $cc_mail_list[0] == "") {
                Mail::to($user->email)
                    ->send(new biddingRuleMail(date('d/m/Y H:i:s'), $ruleData, $look_back_range, $fileName, $profileInfo, $triggeredData));
            } else {
                Mail::to($user->email)
                    ->cc($cc_mail_list)
                    ->send(new biddingRuleMail(date('d/m/Y H:i:s'), $ruleData, $look_back_range, $fileName, $profileInfo, $triggeredData));
            } // end else
            $updateArray = array(
                'email_send_status' => 1,
                'last_execution_time' => date('Y-m-d H:i:s'),
            );
            $data->update($updateArray);
            if ($fileName != "") {
                unlink($fileName);
            } // end if
        } else {
            // tracker code
            Tracker::insertTrackRecord('got enabled schedule status : 1', 'record found');
            Log::info('schedule status is 1');
        } // end else statement
    } // end function

} //end class
