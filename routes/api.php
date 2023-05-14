<?php

use App\Http\Controllers\ProductReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

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

// Token API
Route::post('tokens/create', 'TokenController@createToken')->name('token.store');

// Product APIs
Route::get('products', 'ProductController@getAllProducts')->name('products.list');
Route::get('products/{id}', 'ProductController@getSingleProduct')->name('products.show');

// Category APIs
Route::get('categories', 'CategoryController')->name('categories.list');

// Routes that require authentication
Route::middleware(['auth:sanctum'])->group(function () {
    // Product Review APIs
    Route::post('products/{id}/review', 'ProductReviewController@postReviewForProduct')->name('products.review');
});

// Sign up
Route::post('users/signup', 'UserController@createUser')->name('users.signup');