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
//User and admin can do (account exist)
Route::middleware(['auth:api'])->group(function () {
    Route::put('/users/me', 'Api\UserController@updateProfile');
    Route::get('/users/me', 'Api\UserController@showProfile');
    //logout
    //
    //user can do
    Route::middleware('role:user')->group(function () {
		//get order of themselves
	});
    //admin can do
    Route::group(['prefix' => 'admin', 'middleware' => 'role:admin'], function () {
        // get all orders
		Route::get('/orders', 'Api\OrderController@index');
        // show information of user
        Route::get('/users/{id}', 'Api\UserController@show');
        // get all users
        Route::get('/users', 'Api\UserController@index');
        // create users
        Route::post('/users', 'Api\UserController@store');
	});
});

Route::post('/users', 'Api\UserController@register');
Route::post('/users/login', 'Api\UserController@login');
Route::get('/getItemsBestSale', 'Api\ItemController@getItemsBestSale');
Route::get('/categories', 'Api\CategoryController@index');
Route::get('/items', 'Api\ItemController@index');
Route::get('/items/{id}', 'Api\ItemController@show');
Route::get('/category/{idCategory}/items', 'Api\ItemController@getItemsFollowCategory');
