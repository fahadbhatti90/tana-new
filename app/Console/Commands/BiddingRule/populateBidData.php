<?php

namespace App\Console\Commands\BiddingRule;

use App\Model\Ams\BiddingRule\BiddingRule;
use App\Model\Ams\BiddingRule\RulePortfolioCampaignDataCron;
use Artisan;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class populateBidData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populateBidData:bidding_rule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to data of campaign to get keyword list of specific type.';

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
     * @throws \Throwable
     */
    public function handle()
    {
        Log::info("filePath:App\Console\Commands\Ams\Keyword\Data\populateBidData. Start Cron.");
        Log::info($this->description);
        $ruleList = BiddingRule::getAllRuleList();
        if (count($ruleList) > 0) {
            $storeData = array(); // define array for store
            foreach ($ruleList as $singleRule) {
                $response = RulePortfolioCampaignDataCron::where('fk_bidding_rule_id', $singleRule->id)->get();
                if ($response->isEmpty()) {

                    $frequency_days = 1;
                    switch ($singleRule->frequency) {
                        case "once_per_day":
                            $frequency_days = 1;
                            break;
                        case "every_other_day":
                            $frequency_days = 2;
                            break;
                        case "once_per_week":
                            $frequency_days = 7;
                            break;
                        case "once_per_month":
                            $frequency_days = 30;
                            break;
                        default:
                            $frequency_days = 1;
                    }// end switch

                    $campaigns = $singleRule->getCampaigns();

                    foreach ($campaigns as $campaign) {

                        $dataArray = array();

                        $dataArray["fk_access_token"] = $campaign->fk_access_token;
                        $dataArray["fk_bidding_rule_id"] = $singleRule->id;
                        $dataArray["rule_ad_type"] = $campaign->type;
                        $dataArray["rule_select_type"] = $singleRule->rule_select_type;
                        $dataArray["frequency"] = $singleRule->frequency;
                        $dataArray["frequency_days"] = $frequency_days;
                        $dataArray["profile_id"] = $campaign->profile_id;
                        $dataArray["campaign_id"] = $campaign->campaign_id;
                        $dataArray["portfolio_id"] = $campaign->portfolios_id;

                        array_push($storeData, $dataArray);
                    }// end foreach
                } // end if
            }// end foreach

            $rulePortfolioCampaignDataCron = new RulePortfolioCampaignDataCron();
            $response = $rulePortfolioCampaignDataCron->storeDataForBiddingRule($storeData);
            if ($response) {
                // successfully store into DB
                Log::info("Bid (Portfolio / campaign ) Data cron is populated successfully");
            } else {
                // no store into DB
                Log::info("Bid (Portfolio / campaign ) Data cron is populated successfully");
            } // end if else
        } else {
            // no data found in bidding rule table
        } // end if else
        Log::info("filePath:App\Console\Commands\Ams\Keyword\Data\populateBidData. End Cron.");
    } // end function
} // end class
