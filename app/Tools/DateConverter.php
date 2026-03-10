<?php

namespace App\Tools;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DateConverter
{
    /**
     * @param $date
     * @param $format: format de date sql serveru
     * @return \Illuminate\Database\Query\Expression
     */
    public static function convertDate($date=null,$format=102){
        if(!$date){
            $date = Carbon::now();
        }
        return DB::raw("CONVERT(DATETIME, '$date', $format)");
    }
}
