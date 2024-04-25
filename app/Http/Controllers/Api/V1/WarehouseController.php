<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Warehouse;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Http\Controllers\Controller;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve warehouses with their owner's full name
        $warehouses = Warehouse::with('owner:uuid,first_name,last_name,middle_initial')->get();

        // Now $warehouses will contain the owner's full name instead of the UUID
        return $warehouses;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWarehouseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        // Retrieve the owner's data
        $owner = $warehouse->owner;

        // Capitalize the first letters of first name, last name, and middle initial
        $formattedFirstName = ucfirst(strtolower($owner->first_name));
        $formattedLastName = ucfirst(strtolower($owner->last_name));
        $formattedMiddleInitial = strtoupper($owner->middle_initial) . '.';

        // Concatenate the first name, last name, and formatted middle initial to get the full name
        $fullName = $formattedFirstName . ' ' . $formattedMiddleInitial . ' ' . $formattedLastName;

        // Modify the response data to include the owner's full name
        $responseData = [
            'id' => $warehouse->id,
            'location' => $warehouse->location,
            // Add the owner's full name to the response
            'warehosue_owner' => $fullName,
            // Include other warehouse data as needed
        ];

        return response()->json($responseData);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        //
    }
}
