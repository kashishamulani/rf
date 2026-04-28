<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\SubRole;
// ← add this 

class SubroleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
{
    $roles = Role::all();
    $selectedRole = $request->role_id ?? null;

    return view('subrole.create', compact('roles', 'selectedRole'));
}

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $request->validate([
        'role_id' => 'required',
        'name' => 'required|unique:subroles,name'
    ]);

    SubRole::create($request->all());

    return redirect()
        ->route('roles.index')
        ->with('success', 'Subrole created successfully');
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(SubRole $subrole)
{
    $subrole->delete();
    return back()->with('success', 'Subrole deleted successfully.');
}
}