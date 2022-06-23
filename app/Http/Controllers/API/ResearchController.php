<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Research;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ResearchController extends Controller
{
    public function all(Request $request)
    {

        // Get all the research
        $riset = DB::table('research')
            ->join('groups', 'research.group_id', '=', 'groups.id')
            ->join('users', 'groups.user_id', '=', 'users.id')
            ->select('research.*', 'groups.title as group_name')
            ->select('research.*', 'groups.title as group_name', 'users.name as user_name')
            ->get();
        $id = $request->input('id');
        $title = $request->input('title');

        // mengambil data riset berdasarkan id
        if ($id) {
            $riset = Research::with('groups')->find($id);
            if ($riset) {
                return ResponseFormatter::success([
                    'data' => $riset,
                    'message' => 'Data riset berdasarkan id ditemukan',
                ]);
            } else {
                return ResponseFormatter::error([
                    'message' => 'Data riset berdasarkan id tidak ditemukan',
                ], 400);
            }
        }

        // mengambil data riset berdasarkan title
        if ($title) {
            $riset = Research::where('title', 'like', '%' . $title . '%')->get();
            if ($riset) {
                return ResponseFormatter::success([
                    'data' => $riset,
                    'message' => 'Data group berhasil di ambil by title',
                ], 200);
            } else {
                return ResponseFormatter::error([
                    'message' => 'Data group tidak ditemukan',
                ], 400);
            }
        }

        return ResponseFormatter::success([
            'data' => $riset,
            'message' => 'Semua data riset berhasil di ambil',
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
            ]);

            if (Research::where('title', $request->title)->exists()) {
                return ResponseFormatter::error([
                    'message' => 'Data riset sudah ada',
                ], 400);
            }

            // $fileName = $request->file->getClientOriginalName();
            // $path = $request->file('file')->move(public_path('/files'), $fileName);
            // $fileUrl = url('/files/' . $fileName);
            $file = $request->file('file')->getClientOriginalName();
            $file_name = pathinfo($file, PATHINFO_FILENAME);
            $file_extension = $request->file('file')->getClientOriginalExtension();
            $file_name_to_store = $file_name . '_' . time() . '.' . $file_extension;
            $path = $request->file('file')->move(public_path('public/files'), $file_name_to_store);
            $fileUrl = url('/public/files/' . $file_name_to_store);

            Research::create([
                'title' => $request->title,
                'description' => $request->description,
                'date' => $request->date,
                'author' => $request->author,
                'file' => $fileUrl,
                'user_id' => Auth::user()->id,
                'group_id' => Auth::user()->groups->id,
            ]);

            $riset = Research::where('title', $request->title)->first();

            return ResponseFormatter::success([
                'data' => $riset,
                'message' => 'Data riset berhasil ditambahkan',
            ], 200);
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
        $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'string'],
            'author' => ['nullable', 'string', 'max:255'],
            'file' => ['nullable', 'mimes:doc,docx,pdf,txt,csv', 'max:2048',],
        ]);

        $riset = Research::find($id);
        $riset->update([
            'title' =>  $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'author' => $request->author,
            'user_id' => Auth::user()->id,
        ]);

        try {
            if ($request->file('file')) {
                $file = $request->file('file')->getClientOriginalName();
                $file_name = pathinfo($file, PATHINFO_FILENAME);
                $file_extension = $request->file('file')->getClientOriginalExtension();
                $file_name_to_store = $file_name . '_' . time() . '.' . $file_extension;
                $path = $request->file('file')->move(public_path('public/files'), $file_name_to_store);
                $fileUrl = url('/public/files/' . $file_name_to_store);
                $riset->update([
                    'file' => $fileUrl,
                ]);
            }
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
        }
    }

    public function delete($id)
    {
        $riset = Research::find($id);

        if ($riset) {
            $riset = Research::where('user_id', Auth::user()->id)->find($id);
            if ($riset) {
                Storage::delete($riset);
                unlink(public_path('files/' . $riset->file));
                $riset->delete();
                return ResponseFormatter::success([
                    'message' => 'Data riset berhasil dihapus',
                ], 200);
            } else {
                return ResponseFormatter::error([
                    'data' => null,
                    'message' => 'Data riset tidak ditemukan',
                ], 400);
            }
        } else {
            return ResponseFormatter::error([
                'data' => null,
                'message' => 'Data riset tidak ditemukan',
            ], 400);
        }
    }

    public function download($url)
    {
        try {
            $file = public_path('files/' . $url);
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            $content = file_get_contents($file);
            $content = "Contoh file download " . $fileName;

            $file_name = $fileName . '.txt';

            $headers = array(
                'Content-Type' => 'text/plain',
                'Content-Disposition' => 'attachment; filename=' . $file_name,
                'Content-Length' => strlen($content),
            );

            return response()->make($content, 200, $headers);

            // return response()->download($file);
        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Error',
                'data' => $e,
            ], 'Terjadi kesalahan saat mengunduh file', 500);
        }
    }
}
