<?php

namespace App\Model\Ams\BiddingRule;

use App\Model\Ams\Tracker;
use Illuminate\Database\Eloquent\Model;

class BiddingRuleTracker extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'tbl_ams_bidding_rule_tracker';
    protected $connection = 'mysql';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_id', 'campaign_id', 'ad_group_id', 'keyword_id', 'target_id', 'state', 'ad_type', 'old_bid', 'new_bid', 'check_status', 'tracked_at',
    ];

    /**
     * Inserts Recorded Data into Database
     *
     * @param $data
     */
    public static function insertTrackRecord($data)
    {
        $data['tracked_at'] = date('Y-m-d H:i:s');
        BiddingRuleTracker::insert($data);
    }
}
