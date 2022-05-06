<?php

namespace App\Model\Ams\BiddingRule;

use App\Model\Ams\Campaign;
use App\Model\Ams\Portfolio;
use App\Model\Ams\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BiddingRule extends Model
{
    protected $table = 'tbl_ams_bidding_rules';
    protected $primaryKey = 'id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_user_id', 'rule_name', 'profile_id', 'rule_ad_type', 'rule_select_type', 'rule_select_type_value', 'fk_pre_set_rule_id', 'look_back_period', 'look_back_period_days', 'frequency', 'metric', 'condition', 'integer_values', 'and_or', 'bid_cpc_type', 'then_clause', 'bid_by_type', 'bid_by_value', 'cc_emails',
    ];

    /**
     * @param $id
     * @param $rule_ad_type
     * @return bool
     */
    public static function getKeywordData($id, $rule_ad_type)
    {
        $response = Keyword::where('campaign_id', $id)
            ->where('ad_type', $rule_ad_type)
            ->get();
        if (!$response->isEmpty()) {
            return $response;
        }
        return FALSE;
    }

    /**
     * @param $id
     * @param $rule_ad_type
     * @return bool
     */
    public static function getTargetData($id, $rule_ad_type)
    {
        $response = Target::where('campaign_id', $id)
            ->where('ad_type', $rule_ad_type)
            ->get();
        if (!$response->isEmpty()) {
            return $response;
        }
        return FALSE;
    }

    /**
     * @return mixed
     */
    public function getProfileInfo()
    {
        $profileId = explode('|', $this->profile_id);
        return Profile::findOrFail($profileId[0]);
    }

    /**
     * @return string
     */
    public function getStatment()
    {
        $statement = "";
        $bid_type = ($this->bid_by_type == "dollar") ? "$" . number_format($this->bid_by_value, 2) : number_format($this->bid_by_value, 2) . "%";

        if ($this->and_or != "NA") {
            $metrics = explode(',', $this->metric);
            $conditions = explode(',', $this->condition);
            $integer_values = explode(',', $this->integer_values);
            $and_or = $this->and_or;
            $statement = ucfirst(
                str_replace(
                    "_",
                    " ",
                    "If " .
                        $metrics[0] . " is " . $conditions[0] . "  " . number_format($integer_values[0], 2) .
                        " " . $and_or . " " .
                        $metrics[1] . " is " . $conditions[1] . "  " . number_format($integer_values[1], 2) .
                        " then " . $this->then_clause . " bid by " . $bid_type
                )
            );
        } else {
            $statement = ucfirst(
                str_replace(
                    "_",
                    " ",
                    "If " .
                        $this->metric . " is " . $this->condition . "  " . number_format($this->integer_values, 2) .
                        " then " . $this->then_clause . " bid by " . $bid_type
                )
            );
        }
        return $statement;
    }

    /**
     * @param $campaign_id
     * @param $keyword_id
     * @param $ad_type
     * @param $look_back_period_days
     * @return array
     */
    public static function calculateKeywordBiddingRule($campaign_id, $keyword_id, $ad_type, $look_back_period_days)
    {
        $param = array($campaign_id, $keyword_id, strtolower($ad_type), $look_back_period_days);
        return DB::connection('mysql')->select('call sp_view_bidding_rule_keyword(?,?,?,?)', $param);
    }

    /**
     * @param $campaign_id
     * @param $target_id
     * @param $ad_type
     * @param $look_back_period_days
     * @return array
     */
    public static function calculateTargetsBiddingRule($campaign_id, $target_id, $ad_type, $look_back_period_days)
    {
        $param = array($campaign_id, $target_id, strtolower($ad_type), $look_back_period_days);
        return DB::connection('mysql')->select('call sp_view_bidding_rule_targets(?,?,?,?)', $param);
    }

    /**
     * @param $campaign_id
     * @param $target_id
     * @param $ad_type
     * @param $look_back_period_days
     * @return array
     */
    public static function calculateAudienceTargetsBiddingRule($campaign_id, $target_id, $ad_type, $look_back_period_days)
    {
        $param = array($campaign_id, $target_id, $look_back_period_days);
        return DB::connection('mysql')->select('call sp_view_bidding_rule_targets_audience(?,?,?)', $param);
    }

    /**
     * @return mixed
     */
    public function getCampaigns()
    {
        $rule_select_type_value = explode(",", $this->rule_select_type_value);
        $campaigns = Campaign::wherein('campaign_id', $rule_select_type_value)->where('is_active', 1)->get();
        if ($this->rule_select_type == 'portfolio') {
            $portfolios = Portfolio::wherein('portfolios_id', $rule_select_type_value)->get();
            $rule_select_type_value = array();
            foreach ($portfolios as $portfolio) {
                $campaigns = $portfolio->getCampaign;
                foreach ($campaigns as $campaign) {
                    array_push($rule_select_type_value, $campaign->campaign_id);
                }
            }
            $campaigns = Campaign::wherein('campaign_id', $rule_select_type_value)->where('is_active', 1)->get();
        }
        return $campaigns;
    }

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::created(function ($rule) {
            $frequency_days = 1;
            switch ($rule->frequency) {
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
            }

            $rule->getCronInfo()->create([
                "rule_ad_type" => $rule->rule_ad_type,
                "look_back_period" => $rule->look_back_period,
                "look_back_period_days" => $rule->look_back_period_days,
                "frequency" => $rule->frequency,
                "frequency_days" => $frequency_days,
                "current_run" => date("Y-m-d H:i:s"),
            ]);
        });
    }

    /**
     * @return false
     */
    public static function getAllRuleList()
    {
        $response = BiddingRule::where('is_active', '1')
            ->get();
        if (!$response->isEmpty()) {
            return $response;
        }
        return FALSE;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function getCronInfo()
    {
        return $this->hasOne('App\Model\Ams\BiddingRule\Cron', 'fk_bidding_rule_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getCampaignsInfo()
    {
        return $this->hasMany('App\Model\Ams\BiddingRule\RulePortfolioCampaignDataCron', 'fk_bidding_rule_id');
    }
}
