<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ScrapdataController;
use App\Http\Controllers\Api\V1\WarehouseController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use Illuminate\Support\Facades\Route;

Route::any("/register", [AuthController::class, "register"]);
Route::any("/login", [AuthController::class, "login"]);

Route::middleware('auth:sanctum')->prefix('v1')->namespace('Api\V1')->group(function () {
    Route::post("/users/verification/{user}", [AuthController::class, "requestVerification"]);
    Route::post("/users/update/{user}", [AuthController::class, "updateProfile"]);
    Route::post("/users/subscription/update/{user}", [SubscriptionController::class, "registerSubscription"]);
    Route::get("/users/currentUser/{id}", [AuthController::class, "getUserById"]);
    Route::get("/warehouse", [WarehouseController::class, "index"]);
    Route::post("/scrapdata/create", [ScrapdataController::class, "create"]);
    Route::get("/scrapdata/warehouse/{warehouse}", [ScrapdataController::class, "getByWarehouseId"]);
    Route::get("/scrapdata/summary/{warehouse}", [ScrapdataController::class, "getScrapDataSummary"]);
    Route::post("/scrapdata/update/{scrapdata}", [ScrapdataController::class, "updateScrapData"]);
    Route::delete("/scrapdata/delete/{scrapdata}", [ScrapdataController::class, "deleteScrapData"]);

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

Route::post('/forgot-password', [PasswordResetLinkController::class, "store"]);


// 14|qXBYk5oDXCF2EqqYvFvG0C0VLO166lcfYc0SJ4JXa125c99d