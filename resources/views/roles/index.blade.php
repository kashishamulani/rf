@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2 style="font-size:22px; font-weight:600;">
        <i class="fa-solid fa-user-shield" style="color:#6366f1;"></i> Roles
    </h2>

    <a href="{{ route('roles.create') }}" style="display:flex; align-items:center; gap:8px; padding:10px 16px;
              background:linear-gradient(135deg,#6366f1,#ec4899);
              color:#fff; border-radius:10px; font-weight:600; text-decoration:none;">
        <i class="fa-solid fa-circle-plus"></i> New Role
    </a>
</div>

{{-- SUCCESS / ERROR MESSAGES --}}
@if(session('success'))
<div style="padding:10px 14px; background:#22c55e; color:white; border-radius:8px; margin-bottom:12px;">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="padding:10px 14px; background:#ef4444; color:white; border-radius:8px; margin-bottom:12px;">
    {{ session('error') }}
</div>
@endif

{{-- TABLE WRAPPER --}}
<div style="overflow-x:auto; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05); background:white;">
    <table style="width:100%; border-collapse:collapse; min-width:700px;">
        <thead>
            <tr style="background:#f9fafb; border-bottom:2px solid #e5e7eb; text-align:left;">
                <th style="padding:12px;">#</th>
                <th style="padding:12px;">Role Name</th>
                <th style="padding:12px;">Subroles</th>
                <th style="padding:12px; text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $index => $role)
            <tr style="border-bottom:1px solid #f1f5f9; vertical-align:middle;">
                <td style="padding:12px;">{{ $index + 1 }}</td>
                <td style="padding:12px; font-weight:600; color:#111827;">{{ $role->name }}</td>

                {{-- Subroles Column with Delete --}}
                <td style="padding:12px; color:#374151;">
                    @if($role->subroles->count())
                        @foreach($role->subroles as $subrole)
                            <div style="display:flex; align-items:center; gap:6px; margin-bottom:4px;">
                                <span>{{ $subrole->name }}</span>

                                {{-- Delete Subrole --}}
                                <form method="POST" action="{{ route('subroles.destroy', $subrole->id) }}"
                                      onsubmit="return confirm('Delete this subrole?');" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none; border:none; color:#ef4444; cursor:pointer; font-size:14px;">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    @else
                        <span style="color:#9ca3af;">No subroles</span>
                    @endif
                </td>

                {{-- Actions for Role --}}
                <td style="padding:12px; text-align:center; display:flex; gap:12px; justify-content:center;">
                    {{-- Edit Role --}}
                    <a href="{{ route('roles.edit', $role->id) }}" title="Edit Role"
                        style="color:#f59e0b; font-size:16px;">
                        <i class="fa-solid fa-pen"></i>
                    </a>

                    {{-- Delete Role --}}
                    <form method="POST" action="{{ route('roles.destroy', $role->id) }}"
                        onsubmit="return confirm('Delete this role?');" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Delete Role"
                            style="background:none; border:none; color:#ef4444; cursor:pointer; font-size:16px;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>

                    {{-- Add Subrole --}}
                    <a href="{{ route('subroles.create') }}?role_id={{ $role->id }}" title="Add Subrole"
                        style="color:#10b981; font-size:16px;">
                        <i class="fa-solid fa-circle-plus"></i>
                    </a>

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding:14px; text-align:center; color:#9ca3af;">
                    No roles found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection