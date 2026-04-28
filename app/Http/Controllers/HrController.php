<?php

namespace App\Http\Controllers;

use App\Models\Hr;
use Illuminate\Http\Request;

class HrController extends Controller
{
    /**
     * Display HR list
     */
    public function index()
    {
        $hrs = Hr::latest()->get();
        return view('hrs.index', compact('hrs'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('hrs.create');
    }

    /**
     * Store HR
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => ['required','digits:10','regex:/^[6-9]\d{9}$/'],
            'email'  => 'required|email|unique:hrs,email',
            'state'  => 'required|string|max:5', // ISO code like MP
        ], [
            'mobile.required' => 'Mobile number is required.',
            'mobile.digits'   => 'Mobile number must be exactly 10 digits.',
            'mobile.regex'    => 'Enter a valid Indian mobile number.',
        ]);

        Hr::create([
            'name'   => $request->name,
            'mobile' => $request->mobile,
            'email'  => $request->email,
            'state'  => $request->state, // MP, MH, DL
        ]);

        return redirect()->route('hrs.index')
            ->with('success', 'HR added successfully!');
    }

    /**
     * Show edit form
     */
    public function edit(Hr $hr)
    {
        return view('hrs.edit', compact('hr'));
    }

    /**
     * Update HR
     */
    public function update(Request $request, Hr $hr)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => ['required','digits:10','regex:/^[6-9]\d{9}$/'],
            'email'  => 'required|email|unique:hrs,email,' . $hr->id,
            'state'  => 'required|string|max:5',
        ], [
            'mobile.required' => 'Mobile number is required.',
            'mobile.digits'   => 'Mobile number must be exactly 10 digits.',
            'mobile.regex'    => 'Enter a valid Indian mobile number.',
        ]);

        $hr->update([
            'name'   => $request->name,
            'mobile' => $request->mobile,
            'email'  => $request->email,
            'state'  => $request->state,
        ]);

        return redirect()->route('hrs.index')
            ->with('success', 'HR updated successfully!');
    }

    /**
     * Delete HR safely
     */
    public function destroy(Hr $hr)
    {
        // Prevent delete if used somewhere
        if (method_exists($hr, 'assignments') && $hr->assignments()->exists()) {
            return redirect()->route('hrs.index')
                ->with('error', 'This HR is assigned and cannot be deleted.');
        }

        $hr->delete();

        return redirect()->route('hrs.index')
            ->with('success', 'HR deleted successfully!');
    }
}