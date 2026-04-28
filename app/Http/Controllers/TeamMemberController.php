<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    /**
     * Display all team members
     */
    public function index(Request $request)
    {
        $query = TeamMember::query();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('mobile', 'like', '%' . $request->search . '%');
            });
        }

        // Designation filter
        if ($request->filled('designation')) {
            $query->where('designation', 'like', '%' . $request->designation . '%');
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Date filters
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

       $members = $query->withCount('assignments')
                 ->latest()
                 ->get();

        return view('team-members.index', compact('members'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('team-members.create');
    }

    /**
     * Store new member
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'email'       => ['required','email:rfc,dns','max:255','unique:team_members,email'],
            'mobile'      => ['required','digits:10','regex:/^[6-9]\d{9}$/'],
            'status'      => 'required|boolean',
        ], [
            'mobile.required' => 'Mobile number is required.',
            'mobile.digits'   => 'Mobile number must be exactly 10 digits.',
            'mobile.regex'    => 'Enter a valid Indian mobile number.',
            'email.required'  => 'Email is required.',
            'email.email'     => 'Enter a valid email address.',
            'email.unique'    => 'This email is already registered.',
        ]);

        TeamMember::create([
            'name'        => trim($request->name),
            'designation' => trim($request->designation),
            'email'       => strtolower(trim($request->email)),
            'mobile'      => $request->mobile,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('team-members.index')
            ->with('success', 'Team member added successfully');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $member = TeamMember::findOrFail($id);
        return view('team-members.edit', compact('member'));
    }

    /**
     * Update member
     */
    public function update(Request $request, TeamMember $teamMember)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'email'       => ['required','email:rfc,dns','max:255','unique:team_members,email,' . $teamMember->id],
            'mobile'      => ['required','digits:10','regex:/^[6-9]\d{9}$/'],
            'status'      => 'required|boolean',
        ], [
            'mobile.required' => 'Mobile number is required.',
            'mobile.digits'   => 'Mobile number must be exactly 10 digits.',
            'mobile.regex'    => 'Enter a valid Indian mobile number.',
            'email.required'  => 'Email is required.',
            'email.email'     => 'Enter a valid email address.',
            'email.unique'    => 'This email is already registered.',
        ]);

        $teamMember->update([
            'name'        => trim($request->name),
            'designation' => trim($request->designation),
            'email'       => strtolower(trim($request->email)),
            'mobile'      => $request->mobile,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('team-members.index')
            ->with('success', 'Team member updated successfully');
    }

    /**
     * Delete member
     */
    public function destroy(TeamMember $teamMember)
    {
        if ($teamMember->assignments()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete member. Activities are assigned.');
        }

        $teamMember->delete();

        return redirect()
            ->route('team-members.index')
            ->with('success', 'Team member deleted successfully');
    }

    /**
     * Toggle status
     */
    public function toggleStatus(TeamMember $teamMember)
    {
        $teamMember->status = !$teamMember->status;
        $teamMember->save();

        return redirect()
            ->back()
            ->with('success', 'Member status updated');
    }
}