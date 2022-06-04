<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Rules\Password;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class AdminController extends Controller
{

    // Method get all users
    public function index()
    {
        $users = DB::table('users')
            ->rightJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.name as role')
            ->get();

        return ResponseFormatter::success([
            'data' => $users,
            'message' => 'Data user berhasil di ambil',
        ], 200);
    }

    // method add user
    public function register(Request $request)
    {
        try {
            // validate request
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'max:255', 'email', 'unique:users'],
                'role_id' => ['required', 'integer'],
                'password' => ['required', 'string', new Password],
            ]);

            // Create user
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password' => Hash::make($request->password),
                'remember_token' => $request->remember_token,
            ]);

            $user = User::where('email', $request->email)->first();

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'token' => $tokenResult,
                'type' => 'Bearer',
                'user' => $user,
            ], 'User registered successfully');
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage() . 'User not registered',
            ], 'Authentication failed', 500);
        }
    }


    // Method delete user 
    public function delete($id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            return ResponseFormatter::success([
                'message' => 'User deleted successfully',
            ], 'User deleted successfully', 200);
        } catch (QueryException $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'User not deleted', 500);
        }
    }
}
