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

Route::group(['prefix' => 'auth'], function ($router) {
    
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group(['middleware' => 'jwt.auth'], function ($router) {
    
    Route::get('providers', 'ProvidersController@getAllProviders');
    Route::get('providers/{id}', 'ProvidersController@getProvider');
    Route::post('providers/new', 'ProvidersController@newProvider');
    Route::post('providers/update', 'ProvidersController@updateProvider');
    Route::post('providers/delete', 'ProvidersController@deleteProvider');
    Route::get('zones/all/{id}', 'ZonesController@getAllZones');
    Route::get('zones/{id}', 'ZonesController@getZone');
    Route::post('zones/new', 'ZonesController@newZone');
    Route::post('zones/update', 'ZonesController@updateZone');
    Route::post('zones/delete', 'ZonesController@deleteZone');
    Route::post('zones/polygonValidate', 'ZonesController@polygonValidate');
});