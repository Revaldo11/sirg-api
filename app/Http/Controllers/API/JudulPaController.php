<?php

namespace App\Http\Controllers\API;

use App\Models\JudulPa;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class JudulPaController extends Controller
{
    public function index()
    {
        // return ('DADAW');
    $judul=JudulPa::all();
        return ResponseFormatter::success([
            'data' => $judul,
            'message' => 'Data group berhasil di ambil',
        ], 200);
    }

    public function create(Request $request)
    {
        try {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'supervisor' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'qualification' => ['required', 'string', 'max:255'],
        ]);

        JudulPa::create([
            'title' => $request->title,
            'supervisor' => $request->supervisor,
            'description' => $request->description,
            'qualification' => $request->qualification,

        ]);

        $judulpa= JudulPa::where('title', $request->title)->first();
        return ResponseFormatter::success($judulpa);
    } catch (QueryException $error) {
        return ResponseFormatter::error([
            'message' => 'Error',
            'data' => $error,
        ], 'Terjadi kesalahan saat menyimpan data', 500);
    }
    }
};