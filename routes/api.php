<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ScrapdataController;
use App\Http\Controllers\Api\V1\AuditLogController;
use App\Http\Controllers\Api\V1\WarehouseController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

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

Route::post('/audit-log', [AuditLogController::class, 'store']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

