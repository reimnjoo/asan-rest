<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $data = $request->validate([
            'user_type' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'middle_initial' => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'date'],
            'affiliation' => ['required', 'string'],
            'location' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'username' => ['required', 'min:6'],
            'password' => ['required', 'min:6']
        ]);

        // Combine last_name, first_name, and middle_initial into fullname
        $data['fullname'] = $data['last_name'] . ' ' . $data['first_name'];
        if (!empty($data['middle_initial'])) {
            $data['fullname'] .= ' ' . $data['middle_initial'];
        }

        // Generate UUID for the user
        $data['id'] = Str::uuid();

        // Hash the password
        $data['password'] = Hash::make($data['password']);

        // Attempt to create the user
        try {
            $user = User::create($data);

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

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'email', 'min:6', 'exists:users'],
            'password' => ['required', 'min:6']
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'message' => 'Invalid credentials.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
