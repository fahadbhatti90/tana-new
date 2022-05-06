<?php

namespace App\Model\Ams\BiddingRule;

use Illuminate\Database\Eloquent\Model;

class PreSetRule extends Model
{
    protected $table = 'tbl_ams_bidding_rules_preset';
    protected $primaryKey = 'id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'preset_name', 'look_back_period', 'look_back_period_days', 'frequency', 'metric', 'condition', 'integer_values', 'and_or', 'bid_cpc_type', 'then_clause', 'bid_by_type', 'bid_by_value',
    ];
}
