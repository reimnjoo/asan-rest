<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

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
            $data['fullname'] = $data['last_name'] . ' ' . $data['first_name'];
            if (!empty($data['middle_initial'])) {
                $data['fullname'] .= ' ' . $data['middle_initial'];
            }

            // Generate UUID for the user
            $data['id'] = Str::uuid();

            // Hash the password
            $data['password'] = Hash::make($data['password']);

            // Attempt to create the user
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

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => ['sometimes', 'required_without:username', 'email'],
            'username' => ['sometimes', 'required_without:email', 'string'],
            'password' => ['required', 'min:6']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        $user = User::where(function ($query) use ($data) {
            if (isset($data['email'])) {
                $query->where('email', $data['email']);
            } elseif (isset($data['username'])) {
                $query->where('username', $data['username']);
            }
        })
            ->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ], Response::HTTP_OK);
    }

}
