<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PersonalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\VilleController;

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::resource('villes', VilleController::class);
Route::resource('personals', PersonalController::class);
Route::resource('orders', OrderController::class);
Route::resource('sliders', SliderController::class);
Route::resource('promotions', PromotionController::class);
Route::resource('subscribers', SubscribeController::class);
Route::resource('banners', BannerController::class);
Route::resource('comments', CommentController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
