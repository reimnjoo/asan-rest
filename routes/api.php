<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/csrf-cookie', function () {
    return response()->json(['csrfToken' => csrf_token()]);
});

// Route::post("/register", [AuthController::class, "register"]);
// Route::post("/login", [AuthController::class, "login"]);

// Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function() {
//     Route::post("/register", [AuthController::class, "register"]);
//     Route::post("/login", [AuthController::class, "login"]);
// });

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);