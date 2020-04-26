<?php
namespace App\Services\Platforms\Netsuite\CustomFunctions;

trait integration_2_custom_functions
{
    /**
     *  NOTE: If there is a need for a CUSTOM FUNCTION (functions that doesn't exist in the PLATFORM ABSTRACT CLASS) for the SOURCE INTEGRATION QUERY BUILDER, this function would create the query needed for
     *  building the parameters for integration source update parameters, the format of the function should be 'integration_sourceQueryBuilder' + '_' + INTEGRATION.ID column for example e.g
     *
     *  public function integration_sourceQueryBuilder_<INTEGRATION_ID>()
     *
     */

    /**
     * Custom Functions Example
     */
    // protected function integration_1_preGetTest() {
    //     Log::info('[CUSTOM_FUNCTIONS][PRE_GET_TEST] just testing!!');
    // }
}
