<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Validator;

class RoleController extends Controller
{
    // Menampilkan semua role
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    // Menambahkan role baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $role = Role::create([
            'name' => $request->name,
        ]);

        return response()->json($role, 201);
    }

    // Mengupdate role
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        $role->update($request->all());
        return response()->json($role);
    }

    // Menghapus role
    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }

    public function assignPermissions(Request $request, $roleId)
{
    $role = Role::find($roleId);

    if (!$role) {
        return response()->json(['error' => 'Role not found'], 404);
    }

    $role->permissions()->sync($request->permission_ids);
    return response()->json($role->permissions);
}


}
