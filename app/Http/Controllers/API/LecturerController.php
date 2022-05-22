<?php

namespace App\Http\Controllers\API;

use App\Models\Lecturer;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Rules\Password;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class LecturerController extends Controller
{
    public function index(Request $request)
    {
        $dosen = Lecturer::all();
        // $dosen = DB::table('lecturers')
        //     ->join('users', 'lecturers.user_id', '=', 'users.id')
        //     ->select('lecturers.*', 'users.name as user_name')
        //     ->get();

        $id = $request->input('id');

        // mengambil data group berdasarkan id
        if ($id) {
            $dosen = Lecturer::find($id);
            if ($dosen) {
                return ResponseFormatter::success([
                    'data' => $dosen,
                    'message' => 'Data dosen berhasil di ambil',
                ]);
            } else {
                return ResponseFormatter::error(404, 'Dosen not found');
            }
        }

        return ResponseFormatter::success([
            'data' => $dosen,
            'message' => 'Data dosen berhasil di ambil',
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nip' => ['required', 'string', 'max:255', 'unique:lecturers'],
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'year_lecturer' => ['required', 'string', 'max:255'],
                'community_service' => ['required', 'string', 'max:255'],
                'achievement_lecturer' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', new Password],
                'path_photo' => ['nullable', 'string', 'max:255'],
            ]);

            // Create user
            Lecturer::create([
                'nip' => $request->nip,
                'name' => $request->name,
                'phone' => $request->phone,
                'year_lecturer' => $request->year_lecturer,
                'community_service' => $request->community_service,
                'achievement_lecturer' => $request->achievement_lecturer,
                'password' => Hash::make($request->password),
            ]);

            $dosen = Lecturer::where('name', $request->name)->first();
            $tokenResult = $dosen->createToken('authToken')->plainTextToken;


            return ResponseFormatter::success([
                'token' => $tokenResult,
                'type' => 'Bearer',
                'dosen' => $dosen,
            ], 'Dosen registered successfully');
        } catch (QueryException $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authentication failed', 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nip' => ['required', 'string', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'year_lecturer' => ['required', 'string', 'max:255'],
                'community_service' => ['required', 'string', 'max:255'],
                'achievement_lecturer' => ['required', 'string', 'max:255'],
                'path_photo' => ['nullable', 'string', 'max:255'],
            ]);

            $dosen = Lecturer::find($id);
            if ($dosen) {
                $dosen->update([
                    'nip' => $request->nip,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'year_lecturer' => $request->year_lecturer,
                    'community_service' => $request->community_service,
                    'achievement_lecturer' => $request->achievement_lecturer,
                ]);

                return ResponseFormatter::success([
                    'data' => $dosen,
                    'message' => 'Data dosen berhasil di update',
                ]);
            } else {
                return ResponseFormatter::error(404, 'Dosen not found');
            }
        } catch (QueryException $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authentication failed', 500);
        }
    }

    public function loginDosen(Request $request)
    {
        try {
            $request->validate([
                'nip' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'max:255'],
            ]);

            $dosen = Lecturer::where('nip', $request->nip)->first();

            if ($dosen) {
                if (password_verify($request->password, $dosen->password)) {
                    return ResponseFormatter::success([
                        'dosen' => $dosen,
                    ], 'Login success');
                } else {
                    return ResponseFormatter::error(401, 'Wrong password');
                }
            } else {
                return ResponseFormatter::error(404, 'Dosen not found');
            }
        } catch (QueryException $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authentication failed', 500);
        }
    }
}
