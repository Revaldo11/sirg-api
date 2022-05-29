<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Creation;
use App\Models\Research;
use Illuminate\Database\QueryException;

class CreationController extends Controller
{
    public function all(Request $request)
    {
        $karya = Creation::all();
        //menampilkan semua data karya
        // $creation = DB::table('karya')
        //     ->join('groups', 'creation.group_id', '=', 'groups.id')
        //     ->get();
        $id = $request->input('id');
        $title = $request->input('title');

        //mengambil data karya berdasarkan id
        if ($id) {
            $creation = Creation::find($id);
            if ($creation) {
                return ResponseFormatter::success([
                    'data' => $creation,
                    'massage' => 'data karya berhasil diambil',
                ]);
            } else {
                return ResponseFormatter::error(404, 'karya not found');
            }
        }
        if ($title) {
            $creation = Creation::where('title', 'like', '%' . $title . '%')->get();
            if ($creation) {
                return ResponseFormatter::success([
                    'data' => $creation,
                    'massage' => 'data karya berhasil diambil gan',
                ]);
            }
        }
        return ResponseFormatter::success([
            'data' => $karya,
            'massage' => 'data karya behasil diambil bos',
        ], 200);
    }
    public function create(Request $request)
    {
        try {
            $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:255'],
                'group_id' => ['required', 'integer'],
            ]);

            Creation::create([
                'title' => $request->title,
                'description' => $request->description,
                'group_id' => $request->group_id,
            ]);

            $creation = Creation::where('title', $request->title)->first();

            return ResponseFormatter::success($creation);
        } catch (QueryException $error) {
            return ResponseFormatter::error([
                'massage' => 'error',
                'data' => $error,
            ], 'terjadi kesalahan saat menyimpan data karya', 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'group_id' => ['nullable', 'integer'],
        ]);
        $karya = Creation::find($id);

        if ($karya) {
            $karya->update($data);

            return ResponseFormatter::success([
                'data' => $karya,
                'message' => 'data karya berhasil di update',
            ]);
        } else {
            return ResponseFormatter::error([
                'data' => null,
                'message' => 'terjadi kesalahan dalam update data karya',
            ], 400);
        }
    }
    public function delete($id)
    {
        $karya = Creation::find($id);

        if ($karya) {
            $karya->delete();

            return ResponseFormatter::success([
                'data' => $karya,
                'message' => 'data karya tersebut berhasil dihapus',
            ]);
        } else {
            return ResponseFormatter::error([
                'data' => $karya,
                'message' => 'data gagal untuk dihapus',
            ], 400);
        }
    }
}
