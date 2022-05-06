<?php

namespace App\Model\Process;

use Illuminate\Database\Eloquent\Model;
use DB;

class Process extends Model
{
    /**
     * SHOW PROCESSES
     */
    public static function fetchData()
    {
        return DB::select("SHOW PROCESSLIST");
    }
}
