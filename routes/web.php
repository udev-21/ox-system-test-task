<?php

use App\Models\Category;
use App\Models\Feature;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // DB::enableQueryLog();
    $product = Product::with('category','category.features', 'features', 'featureValues')->find(1);
    return response()->json($product);
    // dd(DB::getQueryLog());
});
