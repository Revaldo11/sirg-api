<?php

namespace App\Http\Controllers\API;

use App\Models\Lecturer;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class LecturerController extends Controller
{
    public function index(Request $request)
    {
        // $dosen = Lecturer::all();
        $dosen = DB::table('lecturers')
            ->join('groups', 'lecturers.group_id', '=', 'groups.id')
            ->select('lecturers.*', 'groups.title as group_name')
            ->get();

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
                'nip' => ['required', 'string', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'year_lecturer' => ['required', 'string', 'max:255'],
                'community_service' => ['required', 'string', 'max:255'],
                'achievement_lecturer' => ['required', 'string', 'max:255'],
                'img_url' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]);

            if ($request->hasFile('img_url')) {
                $file = $request->file('img_url');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/image', $filename);
                $request->merge(['img_url' => $filename]);
            }

            // Create dosen
            Lecturer::create([
                'nip' => $request->nip,
                'name' => $request->name,
                'phone' => $request->phone,
                'year_lecturer' => $request->year_lecturer,
                'community_service' => $request->community_service,
                'achievement_lecturer' => $request->achievement_lecturer,
                'img_url' => $request->img_url,
                'group_id' => Auth::user()->groups->id,
            ]);

            $dosen = Lecturer::where('group_id', Auth::user()->groups->id)->get();

            return ResponseFormatter::success([
                'dosen' => $dosen,
                'message' => 'Data dosen berhasil ditambahkan',
            ], 'Dosen registered successfully', 200);
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

    public function delete($id)
    {
        $dosen = Lecturer::find($id);
        if ($dosen) {
            $dosen->delete();
            return ResponseFormatter::success([
                'message' => 'Data dosen berhasil di hapus',
            ], 200);
        } else {
            return ResponseFormatter::error([
                'message' => 'Dosen not found',
            ], 404);
        }
    }
}
