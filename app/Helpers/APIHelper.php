<?php

namespace App\Helpers;

use Carbon\Carbon;

class APIHelper
{
    public static function convertToMySQLDatetime($isoDate)
    {
        return Carbon::parse($isoDate)->format('Y-m-d H:i:s');
    }
}