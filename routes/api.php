<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;



/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::apiResources([
    'products'  => ProductController::class,
]);


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::group(['middleware' => 'auth.jwt'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
