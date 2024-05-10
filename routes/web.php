<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return response()->json(['message' => 'Test']);
});

Route::get('storage/app/public/scrapdata-images/{filename}', 'App\Http\Controllers\ScrapDataController@getImage');

