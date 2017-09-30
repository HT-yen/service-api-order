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
/* User and admin can do (account exist) */
Route::middleware(['auth:api'])->group(function () {
    Route::put('/users/me', 'Api\UserController@updateProfile');
    Route::get('/users/me', 'Api\UserController@showProfile');

    // update themselves order by user or update order of other user by admin
    Route::put('/orders/{id}', 'Api\OrderController@updateOrder');
    // delete themselves order by user or delete order of other user by admin
    Route::delete('/orders/{id}', 'Api\OrderController@destroy');
    // show themselves order by user or show order of other user by admin
    Route::get('/orders/{id}', 'Api\OrderController@showOrder');

    /* user can do */
    Route::middleware('role:user')->group(function () {
		//get order of themselves
        Route::get('/getThemselvesOrder', 'Api\OrderController@getThemselvesOrder');
        //create themselves order
        Route::post('/orders', 'Api\OrderController@storeOrder');
	});


    /* admin can do */
    Route::group(['prefix' => 'admin', 'middleware' => 'role:admin'], function () {

        // show information of user
        Route::get('/users/{id}', 'Api\UserController@show');
        // get all users
        Route::get('/users', 'Api\UserController@index');
        // create user
        Route::post('/users', 'Api\UserController@store');
        // update user
        Route::put('/users/{id}', 'Api\UserController@update');
        // delete user when id is not themselves
        Route::delete('/users/{id}', 'Api\UserController@destroy');


        // create category
        Route::post('/categories', 'Api\CategoryController@store');
        // update category
        Route::put('/categories/{id}', 'Api\CategoryController@update');
        // delete category
        Route::delete('/categories/{id}', 'Api\CategoryController@destroy');

        // create item
        Route::post('/items', 'Api\ItemController@store');
        // update item
        Route::put('/items/{id}', 'Api\ItemController@update');
        // delete item
        Route::delete('/items/{id}', 'Api\ItemController@destroy');

        // get all orders
        Route::get('/orders', 'Api\OrderController@index');
        //get orders follow user
        Route::get('/getOrderFollowUser/{userId}', 'Api\OrderController@getOrderFollowUser');
	});
});

// register
Route::post('/users', 'Api\UserController@register');

// login
Route::post('/users/login', 'Api\UserController@login');

//get best sale items
Route::get('/getItemsBestSale', 'Api\ItemController@getItemsBestSale');

// get all categories
Route::get('/categories', 'Api\CategoryController@index');

// get all items
Route::get('/items', 'Api\ItemController@index');

// show information of item
Route::get('/items/{id}', 'Api\ItemController@show');

// get all items follow category
Route::get('/categories/{idCategory}/items', 'Api\ItemController@getItemsFollowCategory');
