<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UserAccountController extends Controller
{
    public function register(Request $request)
    {
        $user = User::make($request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3'
        ]));
        
        $user->save();
        
        Auth::login($user);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'data' => $user,
        ]);
    }
}
