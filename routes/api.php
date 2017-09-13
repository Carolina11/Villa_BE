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

Route::post('signUp', 'AuthController@signUp');
Route::post('signIn', 'AuthController@signIn');
Route::get('getUser', 'AuthController@getUser');
Route::get('getTypes', 'DBController@getTypes');
Route::get('getIngredients', 'DBController@getIngredients');
Route::post('storeItem', 'DBController@storeItem');

Route::any('{path?}', 'MainController@index')->where("path", ".+");
