<?php

namespace App\Model\Ams\Target\SD\Audience;

use Illuminate\Database\Eloquent\Model;

class SdTargetAudienceReportLink extends Model
{
    protected $table = 'tbl_ams_targets_sd_audiences_report_link';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
