<?php

namespace App\Model\Ams\Campaign\SD\Audiences;

use Illuminate\Database\Eloquent\Model;

class SdAudiencesCampaignReportLink extends Model
{
    protected $table = 'tbl_ams_campaign_sd_audiences_report_link';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
