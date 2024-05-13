<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use GetStream\StreamChat\Client;

class AuthController extends Controller {

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_type' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'middle_initial' => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'date'],
            'affiliation' => ['required', 'string'],
            'location' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'username' => ['required', 'min:6', 'unique:users'],
            'password' => ['required', 'min:6']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->all();
            // Combine last_name, first_name, and middle_initial into fullname
            $data['fullname'] = $data['last_name'] . ', ' . $data['first_name'];
            if (!empty($data['middle_initial'])) {
                $data['fullname'] .= ' ' . $data['middle_initial'];
            }

            // Hash the password
            $data['password'] = Hash::make($data['password']);

            // Attempt to create the user
            $user = User::create($data);

            if ($data['user_type'] === "owner") {
                $warehouseData = [
                    "warehouse_id" => Str::uuid(),
                    "warehouse_owner_id" => $user->id,
                    "warehouse_name" => $data["affiliation"],
                    "warehouse_owner" => $data['fullname'],
                    "warehouse_location" => $data['location'],
                ];
                $warehouse = Warehouse::create($warehouseData);
            }

            // Create token for authentication
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user,
                'token' => $token
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // Return error message if user creation fails
            return response()->json([
                'message' => 'User registration failed',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'identifier' => ['required', 'string'],
            'password' => ['required', 'min:6'],
            'user_type' => ['required', 'string']
        ]);
    
        $serverClient = new Client("ecnyy5zzzyhe", "d8sgrykjw627csytyknj7z3e893d84thdkks6mkmyhzkdrq3s9m9gt6954brdmam");
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
    
        $credentials = $request->only('identifier', 'password', 'user_type');
    
        // Determine if the identifier is an email or a username
        $identifierField = filter_var($credentials['identifier'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
        // Query the User model to find the user based on the identifier and user type
        $user = User::where($identifierField, $credentials['identifier'])
                    ->where('user_type', $credentials['user_type'])
                    ->first();
    
        if ($user) {
            // Check if the provided password matches the user's password
            if (Hash::check($credentials['password'], $user->password)) {
                // Authentication successful
                // Proceed with additional logic...
    
                // Retrieve user's warehouse ID if applicable
                $warehouseId = null;
                if ($user->user_type === 'owner') {
                    $warehouse = Warehouse::where('warehouse_owner_id', $user->id)->first();
                    if ($warehouse) {
                        $warehouseId = $warehouse->warehouse_id;
                    }
                }
    
                // Create token for authentication
                $token = $user->createToken('auth_token')->plainTextToken;
    
                // Generate Stream Chat token
                $streamToken = $serverClient->createToken($user->id);
    
                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user,
                    'warehouse_id' => $warehouseId,
                    'stream_token' => $streamToken,
                    'token' => $token
                ], 200);
            } else {
                // Password does not match
                return response()->json([
                    'message' => 'Invalid credentials.'
                ], 401);
            }
        } else {
            // User not found
            return response()->json([
                'message' => 'Invalid credentials.'
            ], 401);
        }
    }

    public function requestVerification(Request $request, $userId) {
        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'date_of_birth' => ['required', 'date'],
            'id_type' => ['required', 'string'],
            'id_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:50480'],
            'id_address' => ['required', 'string'],
            'verification_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:50480'],
            'verification_status' => ['required', 'int'],
        ]);


        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->only([
            'date_of_birth',
            'id_type',
            'id_address',
            'verification_status',
        ]);

        if ($request->hasFile('id_image')) {
            $imageFile = $request->file('id_image');
            $imagePath = $imageFile->store('public/users/verification/submittedIDs/' . $userId);
            $data['id_image'] = asset(str_replace('public', 'storage', $imagePath));
        }

        if ($request->hasFile('verification_image')) {
            $imageFile = $request->file('verification_image');
            $imagePath = $imageFile->store('public/users/verification/submittedPhotos/' . $userId);
            $data['verification_image'] = asset(str_replace('public', 'storage', $imagePath));
        }

        $user->fill($data);
        $user->save();

        return response()->json([
            'message' => 'User verification request successful.',
        ], 200);
    }



}
