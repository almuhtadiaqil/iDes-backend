<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Validator;

class PermissionController extends Controller
{
    // Menampilkan semua permission
    public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    // Menambahkan permission baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $permission = Permission::create([
            'name' => $request->name,
        ]);

        return response()->json($permission, 201);
    }

    // Mengupdate permission
    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        $permission->update($request->all());
        return response()->json($permission);
    }

    // Menghapus permission
    public function destroy($id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        $permission->delete();
        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
