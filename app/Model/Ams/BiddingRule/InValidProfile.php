<?php

namespace App\Model\Ams\BiddingRule;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

class InValidProfile extends Model
{
    protected $table = 'tbl_ams_bidding_rule_invalid_profile';
    protected $primaryKey = 'id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_bidding_rule_id', 'profile_id', 'campaign_id',
    ];

    /**
     * @param $id
     * @param $profile_id
     * @param $campaign_id
     * @throws Throwable
     */
    public function addInValidProfile($id, $profile_id, $campaign_id)
    {
        DB::beginTransaction();
        try {
            $row['fk_bidding_rule_id'] = $id;
            $row['profile_id'] = $profile_id;
            $row['campaign_id'] = $campaign_id;

            $existData = DB::table($this->table)
                ->where('fk_bidding_rule_id', $id)
                ->where('profile_id', $profile_id)
                ->where('campaign_id', $campaign_id)
                ->get();
            if ($existData->isEmpty()) {
                DB::table($this->table)->Insert($row);
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
