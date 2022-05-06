<?php

namespace App\Model\Ams;

use Illuminate\Database\Eloquent\Model;

class ReportLink extends Model
{
    protected $table = 'tbl_ams_reports_link';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
