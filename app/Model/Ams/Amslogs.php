<?php

namespace App\Model\Ams;

use Illuminate\Database\Eloquent\Model;
use DB;

class Amslogs extends Model
{
    /**
     * Move Data from src_sales To core by sp.
     */
    public static function fetchData()
    {
        return DB::select("Select * from error_log_ams");
    }
}
