<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json(
            [
                'message' => 'User Register Successfully',
                'User' => $user
            ],
            201
        );
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($request->only('email', 'password')))
            return response()->json(
                [
                    'message' => 'invalid email or password'
                ],
                401
            );

        $user = User::where('email', $request->email)->FirstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(
            [
                'message' => 'Login Successfully',
                'User' => $user,
                'Token' => $token
            ],
            200
        );
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(
            [
                'message' => 'Logout Successfully',
            ],
            200
        );
    }


    // public function getProfile($id)
    // {
    //     $profile = User::findOrFail($id)->profile;
    //     return response()->json(['profile' => $profile], 200);
    // }

    // public function getUserTasks($id)
    // {
    //     $tasks = User::findOrFail($id)->tasks;
    //     return response()->json($tasks, 200);
    // }
}
