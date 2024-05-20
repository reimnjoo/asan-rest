<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Subscription;
use Carbon\Carbon;
use GetStream\StreamChat\Client as StreamChatClient;


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
            'identifier' => 'required|string',
            'password' => 'required|string',
            'user_type' => 'required|string'
        ]);

        $serverClient = new StreamChatClient("ecnyy5zzzyhe", "d8sgrykjw627csytyknj7z3e893d84thdkks6mkmyhzkdrq3s9m9gt6954brdmam");

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('identifier', 'password', 'user_type');
        $identifierField = filter_var($credentials['identifier'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($identifierField, $credentials['identifier'])
        ->where('user_type', $credentials['user_type'])
        ->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            $warehouseId = null;
            if ($user->user_type === 'owner') {
                $warehouse = Warehouse::where('warehouse_owner_id', $user->id)->first();
                if ($warehouse) {
                    $warehouseId = $warehouse->warehouse_id;
                }
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            $serverClient->upsertUser([
                'id' => $user->id,
                'name' => $user->fullname,
                'image' => $user->profile_image 
            ]);

            $streamToken = $serverClient->createToken($user->id);

            // Subscription status check
            $subscription = Subscription::where('client_id', $user->id)->first();
            if ($subscription) {
                $currentDate = Carbon::now();
                if ($currentDate->greaterThan($subscription->subscription_end_date)) {
                    $subscription->subscription_status = 0; 
                    $subscription->save();
                }
                $subscriptionStatus = ['subscription_status' => $subscription->subscription_status];
            } else {
                $subscriptionStatus = ['subscription_status' => 0];
            }

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'warehouse_id' => $warehouseId,
                'stream_token' => $streamToken,
                'token' => $token,
                'subscription' => $subscriptionStatus
            ], 200);
        } else {
            // Invalid credentials
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
            'verification_status' => ['required'],
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

    // public function updateProfile(Request $request, $userId) {
    //     // Find the user by ID
    //     $user = User::findOrFail($userId);

    //     $validator = Validator::make($request->all(), [
    //         'last_name' => 'nullable|string|max:255',
    //         'first_name' => 'nullable|string|max:255',
    //         'middle_initial' => 'nullable|string|max:1',
    //         'affiliation' => 'nullable|string|max:255',
    //         'location' => 'nullable|string|max:255',
    //         'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
    //         'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
    //         'password' => 'nullable|string|min:8|confirmed',
    //         'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:50480', // Assuming max file size is 50MB
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => 'Validation error',
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     $data = $request->only([
    //         'last_name',
    //         'first_name',
    //         'middle_initial',
    //         'location',
    //         'affiliation',
    //         'username',
    //         'email',
    //         'password'
    //     ]);

    //     if ($request->filled('password')) {
    //         $data['password'] = bcrypt($request->password);
    //     }

    //     if ($request->hasFile('profile_image')) {
    //         // Delete the old profile image if it exists
    //         if ($user->profile_image) {
    //             Storage::delete(str_replace(asset('storage'), 'public', $user->profile_image));
    //         }

    //         // Store the new profile image
    //         $imageFile = $request->file('profile_image'); // Get the uploaded file object
    //         $imagePath = $imageFile->store('public/profile_images'); // Specify the storage path and store the file

    //         // Update the profile_image attribute of the User model
    //         $data['profile_image'] = asset(str_replace('public', 'storage', $imagePath));
    //     }

    //     // Check if any name fields are updated and construct the full name
    //     $nameUpdated = false;

    //     if ($request->filled('first_name') && $user->first_name !== $request->first_name) {
    //         $user->first_name = $request->first_name;
    //         $nameUpdated = true;
    //     }
    //     if ($request->filled('middle_initial') && $user->middle_initial !== $request->middle_initial) {
    //         $user->middle_initial = $request->middle_initial;
    //         $nameUpdated = true;
    //     }
    //     if ($request->filled('last_name') && $user->last_name !== $request->last_name) {
    //         $user->last_name = $request->last_name;
    //         $nameUpdated = true;
    //     }

    //     if ($nameUpdated) {
    //         $user->fullname = trim($user->last_name . ', ' . $user->first_name . ' ' . $user->middle_initial);
    //     }

    //     $user->fill($data);
    //     $user->save();

    //     return response()->json([
    //         'message' => 'Profile updated successfully',
    //         'user' => $user,
    //     ], 200);
    // }
    public function updateProfile(Request $request, $userId) {
        // Find the user by ID
        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'last_name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'middle_initial' => 'nullable|string|max:1',
            'affiliation' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:50480', // Assuming max file size is 50MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->only([
            'last_name',
            'first_name',
            'middle_initial',
            'location',
            'affiliation',
            'username',
            'email',
            'password'
        ]);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('profile_image')) {

            if ($user->profile_image) {
                Storage::delete(str_replace(asset('storage'), 'public', $user->profile_image));
            }

            // Store the new profile image
            $imageFile = $request->file('profile_image');
            $imagePath = $imageFile->store('public/profile_images');

            // Update the profile_image attribute of the User model
            $data['profile_image'] = asset(str_replace('public', 'storage', $imagePath));
        }

        $nameUpdated = false;

        if ($request->filled('first_name') && $user->first_name !== $request->first_name) {
            $user->first_name = $request->first_name;
            $nameUpdated = true;
        }
        if ($request->filled('middle_initial') && $user->middle_initial !== $request->middle_initial) {
            $user->middle_initial = $request->middle_initial;
            $nameUpdated = true;
        }
        if ($request->filled('last_name') && $user->last_name !== $request->last_name) {
            $user->last_name = $request->last_name;
            $nameUpdated = true;
        }

        if ($nameUpdated) {
            $user->fullname = trim($user->last_name . ', ' . $user->first_name . ' ' . $user->middle_initial);
        }

        $user->fill($data);
        $user->save();

        if ($user->user_type === 'owner' && ($request->filled('affiliation') || $request->filled('location'))) {
            $warehouse = Warehouse::where('warehouse_owner_id', $user->id)->first();
            if ($warehouse) {
                if ($request->filled('affiliation')) {
                    $warehouse->warehouse_name = $request->affiliation;
                }
                if ($request->filled('location')) {
                    $warehouse->warehouse_location = $request->location;
                }
                if ($nameUpdated) {
                    $warehouse->warehouse_owner = $user->fullname;
                }
                $warehouse->save();
            }
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ], 200);
    }


    public function updateSubscriptionStatus(Request $request) {
        $validator = Validator::make($request->all(), [
            'client_id' => ['required', 'string'],
            'subscription_status' => ['required', 'boolean'],
            'subscription_start_date' => ['required', 'date'],
            'subscription_end_date' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Create subscription
            $subscriptionData = [
                'subscription_id' => Str::uuid(),
                'client_id' => $request->input('client_id'),
                'subscription_status' => $request->input('subscription_status'),
                'subscription_start_date' => $request->input('subscription_start_date'),
                'subscription_end_date' => $request->input('subscription_end_date'),
            ];

            $subscription = Subscription::create($subscriptionData);

            return response()->json([
                'message' => 'Subscription created successfully',
                'subscription' => $subscription
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // Return error message if subscription creation fails
            return response()->json([
                'message' => 'Subscription creation failed',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUserById($id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $subscription = Subscription::where('client_id', $id)->first();

        if(!$subscription) {
            $subscription = ['subscription_status' => 0];
        }
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'subscription' => $subscription,
            'token' => $token
        ], 200);
    }

    public function sendResetLinkEmail(Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent to your email.'], 200)
            : response()->json(['message' => 'Unable to send reset link.'], 500);
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successful'], 200);
        }

        throw ValidationException::withMessages(['email' => [trans($status)]]);
    }

}
