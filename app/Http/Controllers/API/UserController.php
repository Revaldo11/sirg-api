<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required'],
                'password' => ['required'],
            ]);


            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');
        } catch (QueryException $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authentication Failed', 500);
        }
    }

    public function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->has('remember_token')
        );
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $user->update($request->all());
        return ResponseFormatter::success([
            'message' => 'Success',
            'data' => $user
        ], 'Data user berhasil di update', 200);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success($token, 'Token Revoked');
    }
}
