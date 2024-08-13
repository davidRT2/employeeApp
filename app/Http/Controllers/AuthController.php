<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validateData['name'],
            'username' => $validateData['username'],
            'phone' => $validateData['phone'],
            'email' => $validateData['email'],
            'password' => $validateData['password'],
        ]);

        // Respons JSON
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'phone' => $user->phone,
                'email' => $user->email,
            ]
        ], 201); // HTTP Status Code 201 Created
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Revoke old tokens (if any)
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'phone' => $user->phone,
                    'email' => $user->email,
                ],
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = auth('sanctum')->user();
        $user->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully',
        ], 200);
    }
}
