<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\OwnersController;
use App\Http\Controllers\Api\V1\WarehouseController;
use Illuminate\Http\Request;


Route::group(['prefix' => '/api/v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function() {
  // Define route model binding for the 'uuid' column in the OwnersController
  Route::model('owner', \App\Models\Owners::class);

  // Define route model binding for the 'warehouse_id' column in the WarehouseController
  Route::model('warehouse', \App\Models\Warehouse::class);

  // Use the 'owner' parameter in the route definition for owners resource
  Route::apiResource('owners', OwnersController::class);

  // Use the 'warehouse' parameter in the route definition for warehouses resource
  Route::apiResource('warehouses', WarehouseController::class);
});
