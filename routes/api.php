<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\Api\ProductController;

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

Route::get('/test', function(Request $request){
    
    ///dd($request->headers->get('Authorization'));

    $response = new \Illuminate\Http\Response(json_encode(['msg' => 'first API response']));
    $response->header('Content-type', 'application/json');
    return $response;
});

//Products routing

Route::get('/products', function(){
    return Product::all();
});

Route::namespace('App\\Http\\Controllers\\Api\\')->prefix('products1')->group(function(){

    Route::get('/', 'ProductController@index');
    Route::get('/{id}', 'ProductController@show');
    Route::post('/', 'ProductController@save');
    Route::put('/', 'ProductController@update');
    Route::delete('/{id}', 'ProductController@delete');

});

///Route::get('/products1', 'App\\Http\\Controllers\\Api\\ProductController@index');