<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Exception;

class AuthController extends Controller
{
    // Register User
    public function register(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'username' => 'required',
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
            
            // Membuat user baru
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => 1,  // Jika role_id selalu 1, bisa langsung diatur seperti ini
            ]);

            // Membuat token JWT
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'status' => 201,
                'message' => 'Created successfully',
                'data' => $user,  // Mengembalikan data user yang baru saja dibuat
                'token' => $token  // Mengembalikan token yang dihasilkan
            ], 201);
        } catch (Exception $e) {
            // Tangani error
            return response()->json([
                'status' => 500,
                'message' => 'Server error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    // Login User and generate token
    public function login(Request $request)
    {
        try {
            // Validasi input
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

            // Cek kredensial dan buat token
            if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized',
                    'data' => null
                ], 401);
            }

            // Jika berhasil login, kembalikan token
            return response()->json([
                'status' => 200,
                'message' => 'Authorized',
                'data' => ['token' => $token],  // Kembalikan token
            ], 200);
        } catch (Exception $e) {
            // Tangani error
            return response()->json([
                'status' => 500,
                'message' => 'Server error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    // Get the authenticated User
    public function user()
    {
        try {
            return response()->json(auth()->user());
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Server error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
