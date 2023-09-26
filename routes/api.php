<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/checkURL', ['uses' => 'HomeController@checkURL'])->name('admin.checkURL');
Route::get('/options/{module}/tags', ['uses' => 'HomeController@getAllTags'])->name('admin.get-all-tags');
Route::post('/create/tag',['uses' => 'HomeController@storeTag'])->name('api.store.tags');
Route::post('/upload-image/{module}',['uses' => 'HomeController@uploadImage'])->name('api.upload-image');
Route::get('/product/images',['uses' => 'HomeController@getImageProduct'])->name('api.get-image-product');
