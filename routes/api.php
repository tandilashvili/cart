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

Route::get('/cart', [\App\Http\Controllers\CartController::class, 'getUserCart']);
Route::post('/add', [\App\Http\Controllers\CartController::class, 'addProductInCart']);
Route::post('/delete', [\App\Http\Controllers\CartController::class, 'removeProductFromCart']);
Route::post('/update', [\App\Http\Controllers\CartController::class, 'setCartProductQuantity']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
