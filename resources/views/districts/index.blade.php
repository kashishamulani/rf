@extends('layouts.app')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">

    <h2 style="font-size:22px; font-weight:600;">
        🏙 District Master
    </h2>

    <a href="{{ route('districts.create') }}"
        style="display:flex;
        align-items:center;
        gap:8px;
        padding:10px 16px;
        background:linear-gradient(135deg,#6366f1,#ec4899);
        color:#fff;
        border-radius:10px;
        font-weight:600;
        text-decoration:none;">

        + Add District
    </a>

</div>

@if(session('success'))

<div style="padding:10px 14px;
    background:#22c55e;
    color:white;
    border-radius:8px;
    margin-bottom:12px;">

    {{ session('success') }}

</div>

@endif

<div style="overflow-x:auto;">

    <table style="width:100%;
        border-collapse:collapse;
        background:white;
        border-radius:12px;
        box-shadow:0 4px 12px rgba(0,0,0,0.05);">

        <thead>

            <tr style="background:#f9fafb;">

                <th style="padding:12px;">#</th>
                <th style="padding:12px;">State</th>
                <th style="padding:12px;">District</th>
                <th style="padding:12px;">Status</th>
                <th style="padding:12px;">Action</th>

            </tr>

        </thead>

        <tbody>

            @forelse($districts as $key => $district)

            <tr style="border-bottom:1px solid #f1f5f9;">

                <td style="padding:12px;">
                    {{ $key + 1 }}
                </td>

                <td style="padding:12px;">
                    {{ $district->state->name ?? '-' }}
                </td>

                <td style="padding:12px;">
                    {{ $district->name }}
                </td>

                <td style="padding:12px;">
                    {{ $district->status ? 'Active' : 'Inactive' }}
                </td>

                <td style="padding:12px; display:flex; gap:10px;">

                    <a href="{{ route('districts.edit',$district->id) }}"
                        style="color:#f59e0b;">

                        ✏️
                    </a>

                    <form action="{{ route('districts.destroy',$district->id) }}"
                        method="POST"
                        onsubmit="return confirm('Delete District?')">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            style="border:none;
                            background:none;
                            color:red;">

                            🗑️
                        </button>

                    </form>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="5"
                    style="padding:12px; text-align:center;">

                    No Data Found

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection