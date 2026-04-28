@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="font-size:22px; font-weight:600; margin:0;">
            <i class="fa-solid fa-list-check" style="color:#6366f1;"></i> Activities
        </h2>

        <a href="{{ route('activities.create') }}" style="display:flex; align-items:center; gap:8px;
          padding:10px 16px;
          background:linear-gradient(135deg,#6366f1,#ec4899);
          color:#fff; border-radius:10px;
          font-weight:600; text-decoration:none;">
            <i class="fa-solid fa-circle-plus"></i> Add Activity
        </a>
    </div>

    {{-- Success --}}
    @if(session('success'))
    <div style="background:#d1fae5; color:#065f46; padding:10px 14px;
                    border-radius:8px; margin-bottom:16px;">
        {{ session('success') }}
    </div>
    @endif

    {{-- Error --}}
    @if(session('error'))
    <div style="background:#fee2e2; color:#991b1b; padding:10px 14px;
                    border-radius:8px; margin-bottom:16px;">
        {{ session('error') }}
    </div>
    @endif

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse;
                      background:rgba(255,255,255,0.75);
                      backdrop-filter:blur(14px); border-radius:12px;">
            <thead>
                <tr style="border-bottom:2px solid rgba(99,102,241,0.2); text-align:left;">
                    <th style="padding:12px;">#</th>
                    <th style="padding:12px;">Activity</th>
                    <th style="padding:12px; text-align:center;">Used</th>
                    <th style="padding:12px;">Created</th>
                    <th style="padding:12px; text-align:center;">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($phases as $phase)

                {{-- Phase Row --}}
                <tr style="background:#eef2ff; border-top:2px solid #6366f1;">
                    <td colspan="6" style="padding:14px; font-weight:700; color:#4338ca;">
                        Phase {{ $phase->phase_order }}.{{ $phase->sequence }} —
                        {{ $phase->phase_name }}
                        ({{ $phase->activities->count() }} Activities)
                    </td>
                </tr>

                @if($phase->activities->count() > 0)

                @foreach($phase->activities as $activity)
                <tr style="background:#f9fafb; border-bottom:2px solid #e5e7eb;">

                    <td style="padding:12px;">{{ $loop->iteration }}</td>

                    <td style="padding:12px; font-weight:600; display:flex; align-items:center; gap:8px;">
                        {{ $activity->name }}

                        <button type="button"
                            onclick="showDescription(`{{ addslashes($activity->description ?? 'No description available') }}`)"
                            style="background:none;border:none;color:#6366f1;cursor:pointer;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </td>

                    {{-- Phase Column Empty (Since grouped) --}}
                    <!-- <td style="padding:12px; color:#6b7280;">
                        —
                    </td> -->

                    {{-- Used --}}
                    <td style="padding:12px; text-align:center;">
                        @if($activity->assignments_count > 0)
                        <span style="background:#0ea5e9; color:#fff;
                        padding:6px 14px; border-radius:999px;
                        font-weight:600; display:inline-block;">
                            {{ $activity->assignments_count }}
                        </span>
                        @else
                        <span style="background:#e5e7eb; color:#6b7280;
                        padding:6px 14px; border-radius:999px;
                        font-weight:600; display:inline-block;">
                            0
                        </span>
                        @endif
                    </td>

                    {{-- Created --}}
                    <td style="padding:12px;">
                        {{ $activity->created_at->format('d M Y') }}
                    </td>

                    {{-- Actions --}}
                    <td style="padding:12px; text-align:center; display:flex; gap:10px; justify-content:center;">

                        <a href="{{ route('activities.show', $activity->id) }}" style="color:#6366f1;">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <a href="{{ route('activities.edit', $activity->id) }}" style="color:#f59e0b;">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                       @if($activity->assignments_count > 0)

<button type="button"
        title="Activity is already assigned and cannot be deleted"
        style="
        background:#f3f4f6;
        border:none;
        color:#9ca3af;
        padding:6px 10px;
        border-radius:8px;
        cursor:not-allowed;
        display:flex;
        align-items:center;
        gap:5px;">
    <i class="fa-solid fa-lock"></i>
</button>

@else

<form action="{{ route('activities.destroy', $activity->id) }}"
      method="POST"
      onsubmit="return confirm('Delete this activity? This cannot be undone.')">

    @csrf
    @method('DELETE')

    <button type="submit"
        style="
        background:none;
        border:none;
        color:#ef4444;
        cursor:pointer;">
        <i class="fa-solid fa-trash"></i>
    </button>

</form>

@endif


                    </td>

                </tr>
                @endforeach

                @else

                <tr>
                    <td colspan="6" style="padding:12px; color:#6b7280;">
                        No activities under this phase.
                    </td>
                </tr>

                @endif

                @empty

                <tr>
                    <td colspan="6" style="padding:14px; text-align:center; color:#6b7280;">
                        No phases found.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
    </div>

    <!-- Description Modal -->
<div id="descModal" style="
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.45);
    backdrop-filter:blur(3px);
    justify-content:center;
    align-items:center;
    z-index:9999;
">

    <div style="
        background:#fff;
        width:90%;
        max-width:420px;
        border-radius:16px;
        padding:22px 24px;
        box-shadow:0 20px 40px rgba(0,0,0,0.15);
        animation:fadeIn 0.2s ease;
        position:relative;
    ">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
            <h3 style="margin:0; font-size:18px; color:#4f46e5;">Description</h3>

            <button onclick="closeDescription()" style="
                background:none;
                border:none;
                font-size:20px;
                cursor:pointer;
                color:#6b7280;">
                &times;
            </button>
        </div>

        <div id="descText" style="
            font-size:15px;
            color:#374151;
            line-height:1.6;
            padding-top:6px;">
        </div>

    </div>
</div>
</div>

<script>
function showDescription(text) {
    document.getElementById('descText').innerText = text;
    document.getElementById('descModal').style.display = 'flex';
}

function closeDescription() {
    document.getElementById('descModal').style.display = 'none';
}

// close when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('descModal');
    if (event.target === modal) {
        modal.style.display = "none";
    }
}
</script>

@endsection