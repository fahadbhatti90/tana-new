<?php

namespace App\Model\Ams\BiddingRule;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

class RulePortfolioCampaignDataCron extends Model
{
    protected $table = 'tbl_ams_bidding_rule_portfolio_campaign_data_cron';
    protected $primaryKey = 'id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_bidding_rule_id', 'fk_access_token', 'rule_ad_type', 'rule_select_type', 'frequency', 'frequency_days',  'profile_id', 'campaign_id', 'portfolio_id', 'is_done'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getRuleInfo(){
        return $this->belongsTo('App\Model\Ams\BiddingRule\BiddingRule', 'fk_bidding_rule_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getRuleCronInfo(){
        return $this->belongsTo('App\Model\Ams\BiddingRule\Cron', 'fk_bidding_rule_id', 'fk_bidding_rule_id');
    }

    /**
     * @param $storeArray
     * @throws Throwable
     */
    public function storeDataForBiddingRule($storeArray)
    {
        DB::beginTransaction();
        try {
            foreach ($storeArray as $row) {
                DB::table($this->table)->Insert($row);
            }
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

}
