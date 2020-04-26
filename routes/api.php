<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => ['api']], function ($api) {
    /**
     * Authorization
     */
    $api->group(['prefix' => 'auth'], function ($api) {
        $api->post('token', 'App\Http\Controllers\AuthController@token');
        $api->post('logout', 'App\Http\Controllers\AuthController@invalidateToken');
    });

    $api->group(['middleware' => 'resources'], function ($api) {

        // check auth route
        $api->post('checkauth', 'App\Http\Controllers\AuthController@checkAuth');

        // others
        $api->get('valuelookups/to_machship/{id}', 'App\Http\Controllers\Api\ValueLookupController@getToMachships');
        $api->get('valuelookups/from_machship/{id}', 'App\Http\Controllers\Api\ValueLookupController@getFromMachships');
        $api->get('valuelookups/options/{id}', 'App\Http\Controllers\Api\ValueLookupController@getOptions');

        $api->get('fieldmappers/to_machship/{id}', 'App\Http\Controllers\Api\FieldMapperController@getToMachships');
        $api->get('fieldmappers/from_machship/{id}', 'App\Http\Controllers\Api\FieldMapperController@getFromMachships');
        $api->get('fieldmappers/options/{id}', 'App\Http\Controllers\Api\FieldMapperController@getOptions');
        $api->get('fieldmappers/carriers/{id}/services', 'App\Http\Controllers\Api\FieldMapperController@getCarrierMapServices');
        $api->get('fieldmappers/carriers/{id}', 'App\Http\Controllers\Api\FieldMapperController@getCarrierOptions');
        $api->post('fieldmappers/carrier', 'App\Http\Controllers\Api\FieldMapperController@saveCarrierService');
        $api->post('fieldmappers/carrier/services', 'App\Http\Controllers\Api\FieldMapperController@getCarrierServicesOptions');
        $api->delete('fieldmappers/carrier/{id}', 'App\Http\Controllers\Api\FieldMapperController@deleteCarrierService');

        $api->get('integrations/defaults/{id}', 'App\Http\Controllers\Api\IntegrationController@getDefaultMaps');
        $api->post('integrations/activate', 'App\Http\Controllers\Api\IntegrationController@activate');
        $api->post('integrationrecords/repull', 'App\Http\Controllers\Api\IntegrationRecordController@repull');
        $api->post('integrationrecords/reprocess', 'App\Http\Controllers\Api\IntegrationRecordController@reprocess');
        $api->post('integrationrecords/repush', 'App\Http\Controllers\Api\IntegrationRecordController@repush');

        $api->get('status/mapping/options', 'App\Http\Controllers\Api\MachshipStatusMappingController@getOptions');

        // resource routes
        $api->resource('users', 'App\Http\Controllers\Api\UserController');
        $api->resource('accounts', 'App\Http\Controllers\Api\AccountController');
        $api->resource('integrations', 'App\Http\Controllers\Api\IntegrationController');
        $api->resource('integrationsyncs', 'App\Http\Controllers\Api\IntegrationSyncController');
        $api->resource('integrationrecords', 'App\Http\Controllers\Api\IntegrationRecordController');
        $api->resource('integrationtypes', 'App\Http\Controllers\Api\IntegrationTypeController');
        $api->resource('integrationkeys', 'App\Http\Controllers\Api\IntegrationKeyController');
        $api->resource('integrationmetas', 'App\Http\Controllers\Api\IntegrationMetaController');
        $api->resource('integrationfilters', 'App\Http\Controllers\Api\IntegrationFilterController');
        $api->resource('fieldmappers', 'App\Http\Controllers\Api\FieldMapperController');
        $api->resource('valuelookups', 'App\Http\Controllers\Api\ValueLookupController');
        $api->resource('status/mapping', 'App\Http\Controllers\Api\MachshipStatusMappingController');

        // bulks save
        $api->post('integrationkeys/bulk', 'App\Http\Controllers\Api\IntegrationKeyController@bulkStore');
        $api->post('integrationmetas/bulk', 'App\Http\Controllers\Api\IntegrationMetaController@bulkStore');
        $api->post('fieldmappers/bulk', 'App\Http\Controllers\Api\FieldMapperController@bulkStore');
        $api->post('valuelookups/bulk', 'App\Http\Controllers\Api\ValueLookupController@bulkStore');
        $api->post('filters/bulk', 'App\Http\Controllers\Api\IntegrationFilterController@bulkStore');
        $api->post('status/mapping/bulk', 'App\Http\Controllers\Api\MachshipStatusMappingController@bulkStore');

        $api->post('valuelookups/removes', 'App\Http\Controllers\Api\ValueLookupController@bulkRemove');
        $api->post('fieldmappers/removes', 'App\Http\Controllers\Api\FieldMapperController@bulkRemove');

        // resets
        $api->post('fieldmappers/reset/{id}', 'App\Http\Controllers\Api\FieldMapperController@reset');
        $api->get('filters/reset/{id}', 'App\Http\Controllers\Api\IntegrationFilterController@reset');

        $api->get('filters/options/{id}', 'App\Http\Controllers\Api\IntegrationFilterController@getFilterOptions');
    });

    // other specific routes
    $api->get('dashboard', 'App\Http\Controllers\Api\DashboardController@index');
});
