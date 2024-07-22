<?php

use App\Http\Controllers\Admin\CaruselController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\ProductController as FrontProductController;
use App\Http\Controllers\GlobalsController;
use App\Models\Admin\Store;
use Illuminate\Support\Facades\Route;

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

Route::get('/link', function () {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo 'ok';
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forget', [AuthController::class, 'forgetPassword']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum', 'auth.type:user')->group(function () {
    // Auth
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile/update', [AuthController::class, 'profileUpdate']);
    Route::post('verify-email', [AuthController::class, 'emailVerify']);
    Route::get('send-verify-email', [AuthController::class, 'sendEmailVerify']);
    // Order
    Route::post('order', [OrderController::class, 'makeOrder']);
});

Route::group([
    'middleware' => ['auth:sanctum', 'auth.type:admin,super-admin'],
    'prefix' => 'dashboard'
], function () {
    // stores
    Route::apiResource('/stores', StoreController::class);
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/carusels', CaruselController::class);
});

// Front
Route::get('/home', [HomeController::class, 'index']);
Route::get('/products', [FrontProductController::class, 'index']);
Route::get('/products/{slug}', [FrontProductController::class, 'show']);

// Globals
Route::get('/globals/categories', [GlobalsController::class, 'categories']);
Route::get('/globals/stores', [GlobalsController::class, 'stores']);
