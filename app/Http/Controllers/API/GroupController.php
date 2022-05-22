<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{

    public function all(Request $request)
    {
        $group = DB::table('groups')
            ->join('users', 'groups.user_id', '=', 'users.id')
            ->select('groups.*', 'users.name as user_name')
            ->get();
        $id = $request->input('id');

        // mengambil data group berdasarkan id
        if ($id) {
            $group = Group::find($id);
            if ($group) {
                return ResponseFormatter::success([
                    'data' => $group,
                    'message' => 'Data group berhasil di ambil',
                ]);
            } else {
                return ResponseFormatter::error(404, 'Group not found');
            }
        }

        return ResponseFormatter::success([
            'data' => $group,
            'message' => 'Data group berhasil di ambil',
        ], 200);
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'title' => ['required', 'string', 'max:255', 'unique:groups'],
                'description' => ['required', 'string', 'max:255'],
            ]);

            // chechk if user_id is exist
            if (Group::where('user_id', Auth::user()->id)->exists()) {
                return ResponseFormatter::error([
                    'message' => 'User id already exists group'
                ], 'User ID already exists', 500);
            }

            Group::create([
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => Auth::user()->id,
            ]);

            // $group = Group::where('user_id', $request->user_id)->exists();

            $group = Group::where('title', $request->title)->first();
            return ResponseFormatter::success([
                'message' => 'Group created successfully',
                'group' => $group
            ], 200);
        } catch (QueryException $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 500);
        }
    }
}
