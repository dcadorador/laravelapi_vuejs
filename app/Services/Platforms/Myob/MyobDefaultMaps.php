<?php
namespace App\Services\Platforms\Myob;

trait MyobDefaultMaps
{

    /**
    * Override this so each integration platform will
    * have its own default mapper fields
    * @return Array maps
    */
    public static function defaultMaps()
    {

        return [

            [
                'machship_field' => 'companyId',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'data_conversion_type' => 'constant',
            ],
            [
                'machship_field' => 'fromCompanyLocationId',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'data_conversion_type' => 'constant',
            ],
            [
                'machship_field' => 'toContact',
                'direction' => 'to_machship',
                'map_type' => 'direct',
            ],
            [
                'machship_field' => 'toName',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'data_conversion_type' => 'constant',
            ],
            [
                'machship_field' => 'toAddressLine1',
                'direction' => 'to_machship',
                'map_type' => 'direct',
            ],
            [
                'machship_field' => 'toAddressLine2',
                'direction' => 'to_machship',
                'map_type' => 'direct',
            ],
            [
                'machship_field' => 'toLocation.suburb',
                'direction' => 'to_machship',
                'map_type' => 'direct',
            ],
            [
                'machship_field' => 'toLocation.postcode',
                'direction' => 'to_machship',
                'map_type' => 'direct',
            ],
            [
                'machship_field' => 'customerReference',
                'direction' => 'to_machship',
                'map_type' => 'direct',
            ],
            [
                'machship_field' => 'customerReference2',
                'direction' => 'to_machship',
                'map_type' => 'direct'
            ],
        ];
    }
}
