@extends('layouts.app')

@section('content')

<div style="padding:10px; width:100%; max-width:900px; margin:auto;">

    {{-- Back Button --}}
    <div style="display:flex; justify-content:flex-end; margin-bottom:16px;">
        <a href="{{ route('activities.index') }}"
           style="padding:10px 18px;
                  background:#e5e7eb;
                  color:#374151;
                  font-weight:600;
                  border-radius:10px;
                  text-decoration:none;
                  transition:0.3s;"
           onmouseover="this.style.background='#d1d5db'"
           onmouseout="this.style.background='#e5e7eb'">
            ← Back
        </a>
    </div>

    <div style="background:rgba(255,255,255,0.85);
                padding:28px 34px;
                border-radius:18px;
                backdrop-filter:blur(14px);
                box-shadow:0 10px 30px rgba(0,0,0,0.05);">

        <h2 style="font-size:26px; font-weight:700; margin-bottom:20px;
                   text-align:center; color:#4f46e5;">
            Activity Details
        </h2>

        {{-- Activity Name --}}
        <div style="margin-bottom:18px;">
            <div style="font-size:13px; color:#6b7280; font-weight:600;">Activity Name</div>
            <div style="font-size:18px; font-weight:700; color:#111827;">
                {{ $activity->name }}
            </div>
        </div>

        {{-- Phase --}}
        <div style="margin-bottom:18px;">
            <div style="font-size:13px; color:#6b7280; font-weight:600;">Phase</div>
            @if($activity->phase)
                <span style="background:#eef2ff; color:#4338ca;
                             padding:6px 14px; border-radius:999px;
                             font-weight:600; font-size:14px;">
                    {{ $activity->phase->phase_name }}
                </span>
            @else
                <span style="color:#9ca3af;">—</span>
            @endif
        </div>

        {{-- Description --}}
        <div style="margin-bottom:18px;">
            <div style="font-size:13px; color:#6b7280; font-weight:600;">Description</div>
            <div style="font-size:15px; color:#374151; line-height:1.6;">
                {{ $activity->description ?? '—' }}
            </div>
        </div>

        {{-- Created Date --}}
        <div style="margin-bottom:18px;">
            <div style="font-size:13px; color:#6b7280; font-weight:600;">Created At</div>
            <div style="font-size:14px; color:#374151;">
                {{ $activity->created_at->format('d M Y, h:i A') }}
            </div>
        </div>

        {{-- Action Buttons --}}
        <div style="display:flex; justify-content:center; gap:12px; margin-top:26px;">

            <a href="{{ route('activities.edit', $activity->id) }}"
               style="padding:10px 20px;
                      background:#f59e0b;
                      color:#fff;
                      border-radius:10px;
                      font-weight:600;
                      text-decoration:none;">
                <i class="fa-solid fa-pen"></i> Edit
            </a>

            <a href="{{ route('activities.index') }}"
               style="padding:10px 20px;
                      background:#6366f1;
                      color:#fff;
                      border-radius:10px;
                      font-weight:600;
                      text-decoration:none;">
                <i class="fa-solid fa-list"></i> Back to List
            </a>

        </div>

    </div>
</div>

@endsection
