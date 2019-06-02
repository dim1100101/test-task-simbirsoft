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

Route::post('upload', [
    'as' => 'upload', 'uses' => 'Api\FilesController@upload'
]);

Route::get('uploaded/{hashUser}/{hashFile}', [
    'as' => 'uploadedFile', 'uses' => 'Api\FilesController@uploaded'
]);

Route::get('files', [
    'as' => 'filesList', 'uses' => 'Api\FilesController@list'
]);

Route::delete('files/{id}', [
    'as' => 'deleteFile', 'uses' => 'Api\FilesController@delete'
]);

Route::patch('files/{id}', [
    'as' => 'updateFile', 'uses' => 'Api\FilesController@update'
]);
