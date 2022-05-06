<?php

namespace App\Model\Ams;

use Illuminate\Database\Eloquent\Model;

class CampaignReportDownload extends Model
{
    protected $table = 'tbl_ams_campaigns_reports_downloaded_sp';
    protected $primaryKey = 'id';
    public $timestamps = false;


}
