<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

/**
 * Helper class
 *
 * Helper class is created to have all helper methods
 *
 * @access public
 * @author Crestelito Cuyno <cres@fusedsoftware.com>
 */
class ArrayHelper extends Arr
{
    private $returnData;
    /**
     * mapper function
     * mapper method is used to search keys into data and return key value pair
     *
     * @param array $keys
     * @param array $data
     * @return array $data
     */
    public static function mapper(array $keys, array $data, array $resultArray = [])
    {
        foreach ($keys as $key) {
            if (self::has($data, $key)) {
                $resultArray[$key] = $data[$key];
            }
            foreach ($data as $item) {
                if (is_array($item) && count($item) > 0) {
                    $resultArray = self::mapper($keys, $item, $resultArray);
                }
            }
        }
        return $resultArray;
    }

    public static function directMapper(string $key, array $data)
    {
        $flattenedData = array_dot($data);
        return ( isset($flattenedData[$key]) && $flattenedData[$key] ) ? $flattenedData[$key] : '' ;
    }

    public static function customFieldMapper(string $key, array $data)
    {
        $keys = explode(".", $key);
        $searchValue = Arr::last($keys);
        $customFieldPathArr = Arr::where($keys, function ($value, $index) use ($keys) {
            return $index < count($keys) - 1;
        });

        // validate custom field
        if (empty($customFieldPathArr)) {
            \Log::info('[ARRAY_HELPER] custom field path arr is empty : ');
            return '';
        }
        $customFieldPath = implode(".", $customFieldPathArr);
        $dataGet = data_get($data, $customFieldPath);

        // validate data get from the custom field path
        if (empty($dataGet)) {
            \Log::info('[ARRAY_HELPER] custom field path is not found : ');
            return '';
        }

        $filtered = Arr::where($dataGet, function ($value, $key) use ($searchValue) {
            return in_array($searchValue, $value);
        });

        return Arr::first($filtered) ? Arr::first($filtered) : '';
    }

    public static function arrayMapper(string $key, array $data)
    {
        return data_get($data, $key) ? data_get($data, $key) : [];
    }

    /**
     * @param array $data
     * @param $param
     * @param $value
     * @param bool $like
     * @return mixed
     */
    public static function getAssociativeArraySet(array $data, $param, $value, $like = false)
    {
        $result = $like ? Arr::where($data, function ($set, $key) use ($param, $value) {
            return stripos($set[$param], $value) !== false ;
        }) : Arr::where($data, function ($set, $key) use ($param, $value) {
            return $set[$param] == $value;
        });

        return Arr::first($result);
    }
}
