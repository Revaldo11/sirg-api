<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $response = [
            'message' => 'Data list role berhasil di ambil',
            'roles' => $roles,
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        if ($request->has('name')) {
            $role = new Role;
            $role->name = $request->input('name');
            $role->save();
            $response = [
                'message' => 'Data role berhasil ditambahkan',
                'role' => $role,
            ];
            return response()->json($response, Response::HTTP_OK);
        } else {
            $response = [
                'message' => 'Data role gagal ditambahkan',
            ];
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }
    }
}
