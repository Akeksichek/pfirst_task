<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\ProductController;
use App\Models\basket;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('login', [AuthController::class, 'Auth'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('tokenLog', [AuthController::class, 'tokenLog']);
Route::post('tokenReg', [AuthController::class], 'register');
Route::post('/signup', [AuthController::class, 'register']);

Route::get('product', [ProductController::class, 'index']);
Route::post('addProduct/{user_id}', [ProductController::class, 'addProduct']);
Route::post('delProduct/{user_id}', [ProductController::class, 'delProduct']);
Route::post('productUp', [ProductController::class, 'updateProduct']);
Route::get('product/{product_id}', [ProductController::class, 'product']);
Route::post('updateProduct/{user_id}', [ProductController::class, 'updateProduct']);

Route::post('baskets/{user_id}', [BasketController::class, 'show']);
Route::post('addpurchase/{user_id}', [BasketController::class, 'addPurchase']);
Route::get('purchase/{user_id}', [BasketController::class, 'purchase']);
Route::get('basket/{user_id}', [BasketController::class, 'index']);
//получение корзины пользователя с конкретным id
Route::get('ind/{id}', [BasketController::class, 'getBasket']);
Route::post('addBasket/{user_id}', [BasketController::class, 'addBasket']);
Route::post('delBasket/{user_id}', [BasketController::class, 'delBasket']);
