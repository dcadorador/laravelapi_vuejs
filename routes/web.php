<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/test', 'HomeController@test');
Route::get('/test', 'HomeController@test');
Route::get('/test2', 'HomeController@test2');
Route::get('/test4', 'HomeController@test4');
Route::get('/test5', 'HomeController@test5');
Route::get('/test10', 'HomeController@test10');
Route::get('/test11', 'HomeController@test11');
Route::get('/test6', 'HomeController@test6');
Route::get('/test/{id}', 'Api\\IntegrationController@testValueLookup');

// Test sync value lookups from company location in specific integration id
Route::get('sync/company-location/{id}', 'SyncValueLookupsController@syncCompanyLocation');

Route::get('/{any}', 'HomeController@index')->where('any', '.*');
