@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">

    <h2 style="font-size:22px; font-weight:600;">
        <i class="fa-solid fa-link" style="color:#6366f1;"></i> Linked Assignments
    </h2>

    <a href="{{ route('link-assignments.create') }}" style="display:flex; align-items:center; gap:8px; padding:10px 16px;
       background:linear-gradient(135deg,#6366f1,#ec4899);
       color:#fff; border-radius:10px; font-weight:600; text-decoration:none;">

        <i class="fa-solid fa-circle-plus"></i> Add Link
    </a>
</div>

{{-- SUCCESS MESSAGE --}}
@if(session('success'))
<div style="padding:10px 14px; background:#22c55e; color:white; border-radius:8px; margin-bottom:12px;">
    {{ session('success') }}
</div>
@endif

{{-- TABLE --}}
<div style="overflow-x:auto;">
    <table style="width:100%; border-collapse:collapse; background:white;
            border-radius:12px;
            box-shadow:0 4px 12px rgba(0,0,0,0.05);">

        <thead>
            <tr style="background:#f9fafb; border-bottom:2px solid #e5e7eb; text-align:left;">
                <th style="padding:12px;">#</th>
                <th style="padding:12px;">Form</th>
                <th style="padding:12px;">Assignment</th>
                <th style="padding:12px; text-align:center;">Students Submitted</th>
                <th style="padding:12px; text-align:center;">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($links as $key => $link)
            <tr style="border-bottom:1px solid #f1f5f9; vertical-align:middle;">

                <td style="padding:12px;">{{ $key + 1 }}</td>

                <td style="padding:12px;">
                    {{ $link->form->title }}
                </td>

                <td style="padding:12px; font-weight:600; color:#111827;">
                    {{ $link->assignment->assignment_name }}
                </td>

                <td style="padding:12px; text-align:center;">
                    <a href="{{ route('link-assignments.show', $link->id) }}"
                        style="display:inline-block; background:#e0e7ff; color:#3730a3; padding:6px 14px; border-radius:20px; font-weight:600; text-decoration:none;">
                        {{ $link->form_responses_count ?? 0 }}
                    </a>
                </td>

                {{-- ACTIONS --}}
                <td
                    style="padding:12px; text-align:center; display:flex; justify-content:center; align-items:center; gap:14px;">


                    <a href="{{ route('link-assignments.edit',$link->id) }}" style="color:#3b82f6;" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>

                    {{-- DELETE --}}
                    <form method="POST" action="{{ route('link-assignments.destroy',$link->id) }}"
                        onsubmit="return confirm('Delete this link?');">
                        @csrf
                        @method('DELETE')

                        <button type="submit" style="background:none; border:none; color:#ef4444; cursor:pointer;"
                            title="Delete Link">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>

                </td>
            </tr>

            @empty
            <tr>
                <td colspan="5" style="padding:14px; text-align:center; color:#9ca3af;">
                    No Linked Assignments Found
                </td>
            </tr>
            @endforelse
        </tbody>

    </table>
</div>

@endsection