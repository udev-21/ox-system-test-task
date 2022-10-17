<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\ProductController;
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


Route::group([
    'prefix' => 'auth'
],  
function (Illuminate\Routing\Router $router) {
    $router->post('/refresh', [AuthController::class, 'refresh'])->middleware('auth.jwt.refresh');
    $router->post('/login', [AuthController::class, 'login']);
    $router->post('/register', [AuthController::class, 'register']);
    $router->group([
        'middleware' => 'auth.jwt'
    ], function (Illuminate\Routing\Router $router) {
        $router->get('/user-profile', [AuthController::class, 'userProfile']);    
    });
});

Route::get('/products', [ProductController::class, 'index'])->middleware('auth.jwt');
Route::post('/products', [ProductController::class, 'store'])->middleware('auth.jwt');
Route::put('/products/{product}', [ProductController::class, 'update'])->middleware('auth.jwt');
Route::get('/products/{product}', [ProductController::class, 'details'])->middleware('auth.jwt');
Route::delete('/products/{product}', [ProductController::class, 'delete'])->middleware('auth.jwt');

Route::get('/features', [FeatureController::class, 'index'])->middleware('auth.jwt');
Route::post('/features', [FeatureController::class, 'store'])->middleware('auth.jwt');
Route::put('/features/{feature}', [FeatureController::class, 'update'])->middleware('auth.jwt');
Route::get('/features/{id}', [FeatureController::class, 'details'])->middleware('auth.jwt');
Route::delete('/features/{feature}', [FeatureController::class, 'delete'])->middleware('auth.jwt');


Route::get('/categories', [CategoryController::class, 'index'])->middleware('auth.jwt');
Route::post('/categories', [CategoryController::class, 'store'])->middleware('auth.jwt');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->middleware('auth.jwt');
Route::get('/categories/{id}', [CategoryController::class, 'details'])->middleware('auth.jwt');
Route::delete('/categories/{category}', [CategoryController::class, 'delete'])->middleware('auth.jwt');