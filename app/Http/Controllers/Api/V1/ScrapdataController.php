<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Scrapdata;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            'scrap_name' => ['required', 'string'],
            'scrap_volume' => ['string', 'nullable'],
            'scrap_price_per_kg' => ['required', 'numeric'],
            'scrap_total_weight' => ['required', 'numeric'],
            'scrap_stock_count' => ['required', 'integer'],
            'scrap_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:50480',
            'scrap_bar_color' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->all();
            $data['scrap_id'] = Str::uuid();
            $data['scrap_created_date'] = Carbon::now();
            $data['scrap_updated_date'] = Carbon::now();

            if ($request->hasFile('scrap_image')) {
                $warehouseId = $request->input('warehouse_id');
                $imageFile = $request->file('scrap_image'); // Get the uploaded file object
                $imagePath = $imageFile->store('public/scrapdata-images/' . $warehouseId); // Specify the storage path and store the file

                // Save the image path in the database
                $data['scrap_image'] = asset(str_replace('public', 'storage', $imagePath));
            }

            // Create Scrapdata model
            Scrapdata::create($data);

            return response()->json([
                'message' => 'Scrapdata registered successfully.'
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Scrapdata creation failed.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    public function getImage($filename) {
        $path = 'scrapdata-images/' . $filename;

        if (Storage::disk('public')->exists($path)) {
            return response()->file(storage_path('app/public/' . $path));
        } else {
            abort(404);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function getByWarehouseId($warehouseId) {
        try {
            $scrapData = Scrapdata::where('warehouse_id', $warehouseId)
                ->where('is_deleted', 0)
                ->get();

            return response()->json($scrapData, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve scrapdata items.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function updateScrapData(Request $request, $scrapId) {
        $scrapdata = Scrapdata::findOrFail($scrapId);

        $allowedCategories = ['Plastic', 'White Paper', 'Selected Paper', 'Karton Paper', 'Mixed Paper', 'Solid Metal', 'Assorted Metal'];

        $validator = Validator::make($request->all(), [
            'scrap_category' => ['string', 'in:' . implode(',', $allowedCategories)],
            'scrap_name' => ['string'],
            'scrap_volume' => 'string',
            'scrap_price_per_kg' => 'numeric',
            'scrap_total_weight' => 'numeric',
            'scrap_stock_count' => 'integer',
            'scrap_image' => 'image|mimes:jpeg,png,jpg,gif|max:50480',
            'scrap_bar_color' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->only([
            'scrap_category',
            'scrap_name',
            'scrap_volume',
            'scrap_price_per_kg',
            'scrap_total_weight',
            'scrap_stock_count',
            'scrap_bar_color'
        ]);

        $data['scrap_updated_date'] = Carbon::now();

        if ($request->hasFile('scrap_image')) {
            $warehouseId = $scrapdata->warehouse_id; // Use the warehouse_id from the existing record
            $imageFile = $request->file('scrap_image'); // Get the uploaded file object
            $imagePath = $imageFile->store('public/scrapdata-images/' . $warehouseId); // Specify the storage path and store the file

            // Update the scrap_image attribute of the Scrapdata model
            $data['scrap_image'] = asset(str_replace('public', 'storage', $imagePath));
        }

        $scrapdata->fill($data);
        $scrapdata->save();

        return response()->json([
            'message' => 'Scrapdata updated successfully',
            'data' => $scrapdata,
        ], 200);
    }


    public function deleteScrapData($scrapId) {
        try {
            // Find the Scrapdata item by its ID
            $scrapData = Scrapdata::findOrFail($scrapId);

            // Soft delete the Scrapdata item by setting the is_deleted column to 1
            $scrapData->is_deleted = 1;
            $scrapData->save();

            return response()->json([
                'message' => 'Scrapdata deleted successfully',
                'data' => $scrapData,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete scrapdata item.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getScrapDataSummary($warehouse_id) {
        // All possible scrap categories
        $all_categories = [
            "Plastic",
            "White Paper",
            "Selected Paper",
            "Karton Paper",
            "Mixed Paper",
            "Solid Metal",
            "Assorted Metal"
        ];

        // 1. Today's Scrap
        $todays_scrap = (int) DB::table('scrapdatas')
            ->where('warehouse_id', $warehouse_id)
            ->whereDate('scrap_created_date', Carbon::today()->toDateString())
            ->where('is_deleted', 0)
            ->sum('scrap_total_weight');

        // 2. Week Total
        $week_start_date = Carbon::now()->startOfWeek()->toDateString();
        $week_end_date = Carbon::now()->endOfWeek()->toDateString();
        $week_total = (int) DB::table('scrapdatas')
            ->where('warehouse_id', $warehouse_id)
            ->whereBetween('scrap_created_date', [$week_start_date, $week_end_date])
            ->where('is_deleted', 0)
            ->sum('scrap_total_weight');

        // 3. Overall Stocks
        $overall_stocks = (int) DB::table('scrapdatas')
            ->where('warehouse_id', $warehouse_id)
            ->where('is_deleted', 0)
            ->sum('scrap_total_weight');

        // 4. Week Start Date
        $week_start_date_str = Carbon::parse($week_start_date)->format('M j');

        // 5. Week End Date
        $week_end_date_str = Carbon::parse($week_end_date)->format('M j');

        // 6. Week Current Date
        $week_current_date = Carbon::now()->format('M j');

        // 7. Week Stacked Data
        $week_stacked_data = DB::table('scrapdatas')
            ->select(
                'scrap_category',
                'scrap_bar_color',
                DB::raw("LEFT(DATE_FORMAT(scrap_created_date, '%a'), 1) AS scrap_issued_day"),
                DB::raw("CONCAT(DATE_FORMAT(scrap_created_date, '%W'), ', ', DATE_FORMAT(scrap_created_date, '%b %e')) AS day_and_date"),
                DB::raw("CAST(SUM(scrap_total_weight) AS UNSIGNED) AS scrap_total_weight")
            )
            ->where('warehouse_id', $warehouse_id)
            ->whereRaw("WEEK(scrap_created_date, 1) = WEEK(CURDATE(), 1)") // Ensure week starts on Monday
            ->where('is_deleted', 0)
            ->groupBy('scrap_category', 'scrap_bar_color', 'scrap_issued_day', 'day_and_date')
            ->orderByRaw("FIELD(LEFT(DATE_FORMAT(scrap_created_date, '%a'), 1), 'M', 'T', 'W', 'Th', 'F', 'Sa', 'S')") // Order days starting from Monday
            ->get();

        // 8. Today Stacked Data
        $today_stacked_data = DB::table('scrapdatas')
            ->select(
                'scrap_category',
                DB::raw("CAST(SUM(scrap_total_weight) AS UNSIGNED) AS scrap_total_weight")
            )
            ->where('warehouse_id', $warehouse_id)
            ->whereDate('scrap_created_date', Carbon::today()->toDateString())
            ->where('is_deleted', 0)
            ->groupBy('scrap_category')
            ->get();

        // 9. Weight Stacked Data
        $weight_stacked_data = DB::table('scrapdatas')
            ->select(
                'scrap_category',
                DB::raw("CAST(SUM(scrap_total_weight) AS UNSIGNED) AS total_weight")
            )
            ->where('warehouse_id', $warehouse_id)
            ->where('is_deleted', 0)
            ->groupBy('scrap_category')
            ->get()
            ->keyBy('scrap_category')
            ->toArray();

        // Ensure all categories are present in the weight_stacked_data
        $final_weight_stacked_data = [];
        foreach ($all_categories as $category) {
            if (isset($weight_stacked_data[$category])) {
                $final_weight_stacked_data[] = $weight_stacked_data[$category];
            } else {
                $final_weight_stacked_data[] = [
                    'scrap_category' => $category,
                    'total_weight' => 0
                ];
            }
        }

        // 10. Total Buyers
        $total_buyers = User::where('user_type', 'buyer')->count();

        return response()->json([
            'todays_scrap' => $todays_scrap,
            'week_total' => $week_total,
            'overall_stocks' => $overall_stocks,
            'week_start_date' => $week_start_date_str,
            'week_end_date' => $week_end_date_str,
            'week_current_date' => $week_current_date,
            'week_stacked_data' => $week_stacked_data,
            'today_stacked_data' => $today_stacked_data,
            'weight_stacked_data' => $final_weight_stacked_data,
            'total_buyers' => $total_buyers
        ]);
    }




}
