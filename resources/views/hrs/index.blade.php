@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2 style="font-size:22px; font-weight:600;">
        <i class="fa-solid fa-user-tie" style="color:#6366f1;"></i> HR Details
    </h2>

    <a href="{{ route('hrs.create') }}" style="display:flex; align-items:center; gap:8px; padding:10px 16px;
              background:linear-gradient(135deg,#6366f1,#ec4899);
              color:#fff; border-radius:10px; font-weight:600; text-decoration:none;">
        <i class="fa-solid fa-circle-plus"></i> Add HR
    </a>
</div>

{{-- SUCCESS MESSAGE --}}
@if(session('success'))
<div style="padding:10px 14px; background:#22c55e; color:white; border-radius:8px; margin-bottom:12px;">
    {{ session('success') }}
</div>
@endif


{{-- ERROR MESSAGE --}}
@if(session('error'))
<div style="padding:10px 14px; background:#ef4444; color:white; border-radius:8px; margin-bottom:12px;">
    {{ session('error') }}
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
                <th style="padding:12px;">Name</th>
                <th style="padding:12px;">Mobile</th>
                <th style="padding:12px;">Email</th>
                <th style="padding:12px;">State</th>
                <th style="padding:12px;">Created At</th>
                <th style="padding:12px; text-align:center;">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($hrs as $index => $hr)
            <tr style="border-bottom:1px solid #f1f5f9; vertical-align:middle;">

                <td style="padding:12px;">{{ $index + 1 }}</td>

                <td style="padding:12px; font-weight:600; color:#111827;">
                    {{ $hr->name }}
                </td>

                <td style="padding:12px; color:#374151;">
                    {{ $hr->mobile }}
                </td>

                <td style="padding:12px; color:#6b7280;">
                    {{ $hr->email }}
                </td>

                <td style="padding:12px; color:#374151;">
                    {{ $hr->state }}
                </td>

                <td style="padding:12px; color:#6b7280;">
                    {{ $hr->created_at->format('d M Y') }}
                </td>

                {{-- ACTIONS --}}
                <td style="padding:12px; text-align:center; display:flex; gap:10px; justify-content:center;">

                    {{-- EDIT --}}
                    <a href="{{ route('hrs.edit', $hr->id) }}" title="Edit HR" style="color:#f59e0b;">
                        <i class="fa-solid fa-pen"></i>
                    </a>

                    <form method="POST" action="{{ route('hrs.destroy', $hr->id) }}"
                        onsubmit="return confirm('Delete this HR?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            title="{{ $hr->assignments()->exists() ? 'Cannot delete: assigned to assignment' : 'Delete HR' }}"
                            {{ $hr->assignments()->exists() ? 'disabled' : '' }} style="background:none;
               border:none;
               color:{{ $hr->assignments()->exists() ? '#9ca3af' : '#ef4444' }};
               cursor:{{ $hr->assignments()->exists() ? 'not-allowed' : 'pointer' }};">

                            <i class="fa-solid fa-trash"></i>

                        </button>
                    </form>

                </td>
            </tr>

            @empty
            <tr>
                <td colspan="7" style="padding:14px; text-align:center; color:#9ca3af;">
                    No HR records found
                </td>
            </tr>
            @endforelse
        </tbody>

    </table>
</div>

@endsection