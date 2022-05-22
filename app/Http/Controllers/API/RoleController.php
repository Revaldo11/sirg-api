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
}
