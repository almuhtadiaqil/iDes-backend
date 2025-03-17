<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class AuthController extends Controller
{
    // Register User
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors(),
                'data' => null
            ], 400);
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        // return response()->json(['data'=>$request->all()]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact([
            'status' => 201,
            'message' => 'created succesfully',
            'data' => 'user'
        ]), 201);
    }

    // Login User and generate token
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors(),
                'data' => null
            ], 400);
        }

        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized',
                'data' => null
            ], 401);
        }

        return response()->json(compact([
            'status' => 200,
            'message' => 'Authorized',
            'data' => 'token'
        ]), 200);
    }

    // Get the authenticated User
    public function user()
    {
        return response()->json(auth()->user());
    }
}
