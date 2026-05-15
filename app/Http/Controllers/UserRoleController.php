<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index()
    {
        $roles = UserRole::latest()->get();
        return view('user_roles.index', compact('roles'));
    }

    public function create()
    {
        return view('user_roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|unique:user_role'
        ]);

        UserRole::create([
            'role_name' => $request->role_name
        ]);

        return redirect()->route('user-roles.index')
            ->with('success', 'Role created successfully');
    }

    public function edit(UserRole $userRole)
    {
        return view('user-roles.edit', compact('userRole'));
    }

    public function update(Request $request, UserRole $userRole)
    {
        $request->validate([
            'role_name' => 'required|unique:user_role,role_name,' . $userRole->id
        ]);

        $userRole->update([
            'role_name' => $request->role_name
        ]);

        return redirect()->route('user-roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function destroy(UserRole $userRole)
    {
        // Check if role has users
        if ($userRole->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role with associated users');
        }
        
        $userRole->delete();
        return redirect()->route('user-roles.index')
            ->with('success', 'Role deleted successfully');
    }
}