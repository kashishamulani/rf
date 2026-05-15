<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = UserRole::all();

        $permissions = Permission::all();

        $rolePermissions = DB::table('role_permissions')->get();

        return view(
            'role-permissions.index',
            compact('roles', 'permissions', 'rolePermissions')
        );
    }

    public function update(Request $request)
    {
        DB::table('role_permissions')
            ->where('role_id', $request->role_id)
            ->delete();

        if ($request->permissions) {

            foreach ($request->permissions as $permission) {

                DB::table('role_permissions')->insert([
                    'role_id' => $request->role_id,
                    'permission_id' => $permission,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return back()->with('success', 'Permissions updated');
    }
}