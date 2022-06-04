<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Group;
use App\Models\Research;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class ResearchController extends Controller
{
    public function all(Request $request)
    {

        // Get all the research
        $riset = DB::table('research')
            ->join('groups', 'research.group_id', '=', 'groups.id')
            ->select('research.*', 'groups.title as group_name')
            ->get();
        $id = $request->input('id');
        $title = $request->input('title');

        // mengambil data riset berdasarkan id
        if ($id) {
            $riset = Research::find($id);
            if ($riset) {
                return ResponseFormatter::success([
                    'data' => $riset,
                    'message' => 'Data group berhasil di ambil',
                ]);
            } else {
                return ResponseFormatter::error(404, 'Riset not found');
            }
        }

        // mengambil data riset berdasarkan title
        if ($title) {
            $riset = Research::where('title', 'like', '%' . $title . '%')->get();
            if ($riset) {
                return ResponseFormatter::success([
                    'data' => $riset,
                    'message' => 'Data riset berhasil di ambil',
                ]);
            }
        }

        return ResponseFormatter::success([
            'data' => $riset,
            'message' => 'Data riset berhasil di ambil',
        ], 200);
    }


    public function create(Request $request)
    {
        try {
            $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:255'],
                'date' => ['required', 'string'],
                'author' => ['required', 'string', 'max:255'],
                'file' => ['required', 'mimes:doc,docx,pdf,txt,csv', 'max:2048',],
                'group_id' => ['required', 'integer'],
            ]);


            $file = $request->file('file')->getClientOriginalName();
            $file_name = pathinfo($file, PATHINFO_FILENAME);
            $file_extension = $request->file('file')->getClientOriginalExtension();
            $file_name_to_store = $file_name . '_' . time() . '.' . $file_extension;
            $request->file('file')->move(public_path('public/files'), $file_name_to_store);

            Research::create([
                'title' => $request->title,
                'description' => $request->description,
                'date' => $request->date,
                'author' => $request->author,
                'file' => $file_name_to_store,
                'user_id' => Auth::user()->id,
                'group_id' => $request->group_id,
            ]);

            $riset = Research::where('title', $request->title)->first();

            return ResponseFormatter::success([
                'data' => $riset,
                'message' => 'Data riset berhasil ditambahkan',
            ]);
        } catch (QueryException $error) {
            return ResponseFormatter::error([
                'message' => 'Error',
                'data' => $error,
            ], 'Terjadi kesalahan saat menyimpan data', 500);
        }
    }

    public function update(Request $request, $id)
    {
        $riset = Research::find($id);
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'string'],
            'author' => ['nullable', 'string', 'max:255'],
            'file' => ['nullable', 'mimes:doc,docx,pdf,txt,csv', 'max:2048',],
        ]);

        if (Research::where('title', $request->title)->first()) {
            return ResponseFormatter::error(404, 'Riset already exists');
        }

        try {
            if ($request->file('file')) {
                $file = $request->file('file')->getClientOriginalName();
                $file_name = pathinfo($file, PATHINFO_FILENAME);
                $file_extension = $request->file('file')->getClientOriginalExtension();
                $file_name_to_store = $file_name . '_' . time() . '.' . $file_extension;
                $request->file('file')->move(public_path('public/files'), $file_name_to_store);
                $data['file'] = $file_name_to_store;
            }
            $riset->update($data);
            $riset->save();

            return ResponseFormatter::success([
                'data' => $riset,
                'message' => 'Data riset berhasil diubah',
            ]);
        } catch (QueryException $error) {
            return ResponseFormatter::error([
                'message' => 'Error',
                'data' => $error,
            ], 'Terjadi kesalahan saat mengubah data', 500);

            // if ($riset) {
            //     $riset->update($data);

            //     if ($request->file('file')) {
            //         $file = $request->file('file')->getClientOriginalName();
            //         $file_name = pathinfo($file, PATHINFO_FILENAME);
            //         $file_extension = $request->file('file')->getClientOriginalExtension();
            //         $file_name_to_store = $file_name . '_' . time() . '.' . $file_extension;
            //         $request->file('file')->move(public_path('public/files'), $file_name_to_store);
            //         $riset->file = $file_name_to_store;
            //     }

            //     $riset->save();
            //     return ResponseFormatter::success([
            //         'data' => $riset,
            //         'message' => 'Data riset berhasil diubah',
            //     ]);
            // } else {
            //     return ResponseFormatter::error(404, 'Riset not found');
            // }
        }
    }

    public function delete($id)
    {
        $riset = Research::find($id);

        if ($riset) {
            $riset->delete();

            return ResponseFormatter::success([
                'data' => $riset,
                'message' => 'Data riset berhasil di hapus',
            ]);
        } else {
            return ResponseFormatter::error([
                'data' => null,
                'message' => 'Data riset tidak ditemukan',
            ], 400);
        }
    }

    public function download($filename)
    {
        $riset = Research::where('file', $filename)->first();

        if ($riset) {
            $file = $riset->file;
            $path = public_path('public/files/' . $file);
            $file_name = pathinfo($file, PATHINFO_FILENAME);
            $file_extension = pathinfo($file, PATHINFO_EXTENSION);
            $file_name_to_store = $file_name . '_' . time() . '.' . $file_extension;
            return response()->download($path, $file_name_to_store);
        } else {
            return ResponseFormatter::error([
                'data' => null,
                'message' => 'Data riset tidak ditemukan',
            ], 400);
        }
    }
}
