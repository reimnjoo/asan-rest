<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ScrapdataController;
use App\Http\Controllers\Api\V1\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::any("/register", [AuthController::class, "register"]);
Route::any("/login", [AuthController::class, "login"]);

Route::middleware('auth:sanctum')->prefix('v1')->namespace('Api\V1')->group(function () {
    Route::get("/warehouse", [WarehouseController::class, "index"]);
    Route::post("/scrapdata", [ScrapdataController::class, "create"]);
});

// Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1', 'middleware' => 'auth:sanctum'], function () {
//     Route::get("/warehouse-data", [WarehouseController::class, "index"]);
// });

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/csrf-cookie', function () {
    return response()->json(['csrfToken' => csrf_token()]);
});


// 14|qXBYk5oDXCF2EqqYvFvG0C0VLO166lcfYc0SJ4JXa125c99d