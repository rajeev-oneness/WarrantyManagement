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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Version 1
Route::group(['prefix'=>'v1'],function(){
	Route::get('home_screen','App\Http\Controllers\Api\ApiController@getHomeScreen');
	Route::post('login','App\Http\Controllers\Api\ApiController@loginAndRegistration');	
	Route::post('resend_otp','App\Http\Controllers\Api\ApiController@resendOTP');
	Route::post('verify_otp','App\Http\Controllers\Api\ApiController@verifyOTP');
	Route::post('update_user','App\Http\Controllers\Api\ApiController@updateUserProfile');
	/**************** Category ****************/
	Route::get('category','App\Http\Controllers\Api\ApiController@getCategory');
	Route::post('category','App\Http\Controllers\Api\ApiController@saveCategory');
	Route::put('category','App\Http\Controllers\Api\ApiController@updateCategory');
	Route::delete('category','App\Http\Controllers\Api\ApiController@deleteCategory');
	/**************** Brands ****************/
	Route::get('brand','App\Http\Controllers\Api\ApiController@getBrands');
	Route::post('brand','App\Http\Controllers\Api\ApiController@saveBrand');
	Route::put('brand','App\Http\Controllers\Api\ApiController@updateBrand');
	Route::delete('brand','App\Http\Controllers\Api\ApiController@deleteBrand');
	/**************** Product ****************/
	Route::get('product','App\Http\Controllers\Api\ApiController@getProduct');
	Route::post('product','App\Http\Controllers\Api\ApiController@saveProduct');
	Route::put('product','App\Http\Controllers\Api\ApiController@updateProduct');
	Route::delete('product','App\Http\Controllers\Api\ApiController@deleteProduct');
});
