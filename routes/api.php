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
    Route::put('/users/me', 'Api\UserController@updateProfile')->middleware('permission:update-profile');
    Route::get('/users/me', 'Api\UserController@showProfile')->middleware('permission:show-profile');

    // show themselves order by user or show order of other user by admin
    Route::get('/orders/{id}', 'Api\OrderController@showOrder')->middleware('permission:show-order');

    /* user can do */
    Route::middleware('role:user')->group(function () {
		//get order of themselves
        Route::get('/getThemselvesOrder', 'Api\OrderController@getThemselvesOrder')->middleware('permission:get-themselves-order');
        //create themselves order
        Route::post('/orders', 'Api\OrderController@storeOrder')->middleware('permission:create-order');
        // delete themselves order by user
        Route::delete('/orders/{id}', 'Api\OrderController@destroyOrder')->middleware('permission:delete-order');
        // update order items of themselves order by user
        Route::put('/orders/{id}', 'Api\OrderController@updateOrder')->middleware('permission:update-order');
        Route::post('/payments', 'Api\PaymentController@store')->middleware('permission:create-payment');
	});


    /* admin can do */
    Route::group(['prefix' => 'admin', 'middleware' => 'role:admin'], function () {

        // show information of user
        Route::get('/users/{id}', 'Api\UserController@show')->middleware('permission:show-user');
        // get all users
        Route::get('/users', 'Api\UserController@index')->middleware('permission:get-all-users');
        // create user
        Route::post('/users', 'Api\UserController@store')->middleware('permission:create-user');
        // update user
        Route::put('/users/{id}', 'Api\UserController@update')->middleware('permission:update-user');
        // delete user when id is not themselves
        Route::delete('/users/{id}', 'Api\UserController@destroy')->middleware('permission:delete-user');


        // create category
        Route::post('/categories', 'Api\CategoryController@store')->middleware('permission:create-category');
        // update category
        Route::put('/categories/{id}', 'Api\CategoryController@update')->middleware('permission:update-category');
        // delete category
        Route::delete('/categories/{id}', 'Api\CategoryController@destroy')->middleware('permission:delete-category');

        // create item
        Route::post('/items', 'Api\ItemController@store')->middleware('permission:create-item');
        // update item
        Route::put('/items/{id}', 'Api\ItemController@update')->middleware('permission:update-item');
        // delete item
        Route::delete('/items/{id}', 'Api\ItemController@destroy')->middleware('permission:delete-item');

        // get all orders
        Route::get('/orders', 'Api\OrderController@index')->middleware('permission:get-all-orders');
        //get orders follow user
        Route::get('/getOrderFollowUser/{userId}', 'Api\OrderController@getOrderFollowUser')->middleware('permission:get-all-orders-follow-user');
        // get orders to statistic
        Route::get('/statisticOrders', 'Api\OrderController@getStatisticOrder')->middleware('permission:get-statistic-orders');

        // update status order of other user by admin
        Route::put('/change-status-orders/{id}', 'Api\OrderController@changeStatusOrder')->middleware('permission:change-status-order');

        // get all role of user
        Route::get('/get-all-roles', function () {
            return json_decode(\App\Role::select(['id', 'name'])->get());
        })->middleware('permission:get-all-roles');

        // update image for item
        Route::post('/upload-image/items', 'Api\ItemController@postUploadImage');
        // delete image of item
        Route::delete('/remove-image/items', 'Api\ItemController@deleteImage');

        // get all payments
        Route::get('/payments', 'Api\PaymentController@index')->middleware('permission:get-all-payments');
	});
});

// check exist email
Route::get('/check-exist-email/{email}', 'Api\UserController@checkExistEmail');

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

//get best sale items follow related this item
Route::get('/items-related/{id}', 'Api\ItemController@getBestSaleItemsRelatedToItem');
