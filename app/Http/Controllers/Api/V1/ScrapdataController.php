<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Scrapdata;
use App\Http\Requests\StoreScrapdataRequest;
use App\Http\Requests\UpdateScrapdataRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ScrapdataController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $scrapData = Scrapdata::select('scrap_id', 'warehouse_id', 'scrap_category', 'scrap_name', 'scrap_volume', 'scrap_price_per_kg', 'scrap_stock_count', 'scrap_image', 'is_deleted')->get();

        return $scrapData;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request) {
        $allowedCategories = ['Plastic', 'White Paper', 'Selected Paper', 'Karton Paper', 'Mixed Paper', 'Solid Metal', 'Assorted Metal'];

        $validator = Validator::make($request->all(), [
            'warehouse_id' => 'required|uuid|exists:warehouses,warehouse_id',
            'scrap_category' => ['required', 'string', 'in:' . implode(',', $allowedCategories)],
            'scrap_name' => ['required', 'string', 'unique:scrapdatas'],
            'scrap_volume' => ['string', 'nullable'],
            'scrap_price_per_kg' => ['required', 'numeric'],
            'scrap_stock_count' => ['required', 'integer'],
            'scrap_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->all();

            $data['scrap_id'] = Str::uuid();

            if ($request->hasFile('image')) {
                $warehouseId = $request->input('warehouse_id');
                $imagePath = $request->file('image')->store('scrapdata-images/' . $warehouseId); // specify the storage path

                // Save the image path in the database
                $scrapData = new Scrapdata();
                $scrapData->warehouse_id = $warehouseId;
                $scrapData->scrap_image = $imagePath;

                return response()->json(['image_id' => $scrapData->id]);
            }

            $scrapData = Scrapdata::create($data);

            return response()->json([
                'message' => 'Scrapdata registered, successfully.'
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Scrapdata creation, failed.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScrapdataRequest $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Scrapdata $scrapdata) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scrapdata $scrapdata) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScrapdataRequest $request, Scrapdata $scrapdata) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scrapdata $scrapdata) {
        //
    }
}
