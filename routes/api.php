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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('v1/books', 'BookController@index');
Route::get('v1/books/{book}', 'BookController@show');
Route::post('v1/books', 'BookController@store')->middleware('auth:api');
Route::patch('v1/books/{book}', 'BookController@update')->middleware('auth:api');
Route::delete('v1/books/{book}', 'BookController@destroy')->middleware('auth:api');
