<?php

namespace App\Traits\ConversionTypes;

use \Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use App\Helpers\ArrayHelper;

trait FunctionTraits
{
    // TODO Create useful functions here

    protected function getDateTimeUTC()
    {
        return Carbon::now()->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z');
    }

    protected function getDateTimeByAus()
    {
        return Carbon::now()->setTimezone('Australia/Sydney')->format('Y-m-d\TH:i:s\Z');
    }

    /**
     * This is a generic concat function where it will concat speficic datas base from concat
     *
     * @param      array   $data       The data
     * @param      string  $params     The parameters
     * @param      string  $separator  The separator
     *
     * @return     string  concat data
     */
    protected function concat($data, $params, $separator = "|")
    {
        $concat_data = [];

        $params_arr = explode(",", $params);
        if (empty($params_arr)) {
            return "";
        }

        foreach ($params_arr as $param) {
            if (empty($param)) {
                continue;
            }
            $concat_data[] = ArrayHelper::directMapper($param, $data);
        }

        return implode($separator . " ", $concat_data);
    }
}
