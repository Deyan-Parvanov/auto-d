<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function getUser()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user->notifications_count = $user->unreadNotifications()->count();

        return response()->json($user);
    }

    public function store(Request $request)
    {
        if (!Auth::attempt($request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]), true)) {
            throw ValidationException::withMessages([
                'message' => 'Authentication failed - invalid credentials!'
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'data' => $user,
        ]);
    }

    public function destroy(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'No token provided.'], 400);
        }

        $personalAccessToken = PersonalAccessToken::findToken($token);
        if (!$personalAccessToken) {
            return response()->json(['error' => 'Token not found.'], 404);
        }

        $personalAccessToken->delete();

        return response()->json(['success' => true]);
    }
}
