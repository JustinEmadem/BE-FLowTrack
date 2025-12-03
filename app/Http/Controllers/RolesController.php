<?php


namespace App\Http\Controllers;
use Spatie\Permission\Models\Role;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function getRoles()
    {
        $roles = Role::select('id', 'name')->get();
        return response()->json($roles);
    }

    public function createRole(RoleRequest $request)
    {
        $validated = $request->validated();
        $role = Role::create(['name' => $request->name]);
        return response()->json($role, 201);
    }
}
