<?php

namespace App\Model\Ams\BiddingRule;

use App\Model\Ams\Tracker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Cron extends Model
{
    protected $table = 'tbl_ams_bidding_rule_cron';
    protected $primaryKey = 'id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_bidding_rule_id', 'rule_ad_type', 'look_back_period', 'look_back_period_days', 'frequency', 'frequency_days', 'last_run', 'current_run', 'next_run', 'run_status', 'check_rule_status', 'rule_result', 'email_send_status', 'last_execution_time', 'is_active',
        ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getRuleInfo(){
        return $this->belongsTo('App\Model\Ams\BiddingRule\BiddingRule', 'fk_bidding_rule_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getCampaignsInfo(){
        return $this->hasMany('App\Model\Ams\BiddingRule\RulePortfolioCampaignDataCron', 'fk_bidding_rule_id', 'fk_bidding_rule_id');
    }

    public function setCampaignsDoneStatus(){
        return RulePortfolioCampaignDataCron::where('fk_bidding_rule_id', $this->fk_bidding_rule_id)
                ->update(array(
                    'is_done' => '0',
                ));

    }

    public static function getAllEnabledCronList()
    {
        $response = Cron::where('is_active', '1')
            ->get();
        if (!$response->isEmpty()) {
            return $response;
        }
        return FALSE;
    }

    /**
     * changes Cron Job Status
     *
     * @param $id
     * @param $updateArray
     */
    public static function updateCronRunStatus($id, $updateArray)
    {
        Log::info('Bidding Rule Model file methods name : updateCronRunStatus.');
        // tracker code
        Tracker::insertTrackRecord('change enable crons status : 0', 'record found');
        Cron::where('id', $id)->update($updateArray);
        Log::info('End AMS Model file methods name : updateCronRunStatus.');
    }
}
