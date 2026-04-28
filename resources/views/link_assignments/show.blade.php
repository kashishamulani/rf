@extends('layouts.app')

@section('content')

<div style="max-width:1100px; margin:auto; background:white; padding:26px; border-radius:18px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
        <div>
            <h2 style="font-size:26px; font-weight:700; color:#4f46e5; margin-bottom:6px;">Linked Assignment Detail</h2>
            <p style="color:#6b7280; margin:0;">Assignment: <strong>{{ $link->assignment->assignment_name }}</strong> · Form: <strong>{{ $link->form->title }}</strong></p>
        </div>
        <a href="{{ route('link-assignments.index') }}" style="padding:10px 16px; background:#e5e7eb; border-radius:10px; color:#111827; text-decoration:none; font-weight:600;">← Back to linked assignments</a>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:24px;">
        <div style="padding:18px; background:#f8fafc; border-radius:14px; border:1px solid #e2e8f0;">
            <div style="font-size:12px; text-transform:uppercase; letter-spacing:0.08em; color:#64748b; margin-bottom:8px;">Total submissions</div>
            <div style="font-size:28px; font-weight:700; color:#0f172a;">{{ $responses->count() }}</div>
        </div>
        <div style="padding:18px; background:#f8fafc; border-radius:14px; border:1px solid #e2e8f0;">
            <div style="font-size:12px; text-transform:uppercase; letter-spacing:0.08em; color:#64748b; margin-bottom:8px;">Files uploaded</div>
            <div style="font-size:28px; font-weight:700; color:#0f172a;">{{ $totalFileUploads }}</div>
        </div>
        <div style="padding:18px; background:#f8fafc; border-radius:14px; border:1px solid #e2e8f0;">
            <div style="font-size:12px; text-transform:uppercase; letter-spacing:0.08em; color:#64748b; margin-bottom:8px;">Storage used</div>
            <div style="font-size:28px; font-weight:700; color:#0f172a;">
                {{ $totalFileSize ? round($totalFileSize / 1024, 2) : 0 }} KB
            </div>
        </div>
    </div>

    <div style="margin-bottom:24px;">
        <h3 style="font-size:20px; font-weight:700; color:#334155; margin-bottom:12px;">Form Information</h3>
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:18px;">
            <p style="margin:0 0 10px; color:#334155;"><strong>Title:</strong> {{ $link->form->title }}</p>
            <p style="margin:0 0 10px; color:#334155;"><strong>Description:</strong> {{ $link->form->description ?? 'No description provided' }}</p>
            <p style="margin:0; color:#334155;"><strong>Validity:</strong> {{ $link->form->valid_from ? \Carbon\Carbon::parse($link->form->valid_from)->format('d M Y') : 'N/A' }} – {{ $link->form->valid_to ? \Carbon\Carbon::parse($link->form->valid_to)->format('d M Y') : 'N/A' }}</p>
        </div>
    </div>

    <div style="margin-bottom:30px;">
        <h3 style="font-size:20px; font-weight:700; color:#334155; margin-bottom:12px;">Student Submissions</h3>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; background:white; border-radius:12px; overflow:hidden; box-shadow:0 4px 14px rgba(0,0,0,0.05);">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th style="padding:14px 16px; text-align:left; color:#334155;">#</th>
                        <th style="padding:14px 16px; text-align:left; color:#334155;">Candidate</th>
                        <th style="padding:14px 16px; text-align:left; color:#334155;">Mobile</th>
                        <th style="padding:14px 16px; text-align:left; color:#334155;">Submitted</th>
                        <th style="padding:14px 16px; text-align:left; color:#334155;">Files</th>
                        <th style="padding:14px 16px; text-align:left; color:#334155;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($responses as $index => $response)
                        @php
                            $fileCount = $response->values->whereNotNull('file_url')->count();
                        @endphp
                        <tr style="border-top:1px solid #e2e8f0;">
                            <td style="padding:14px 16px;">{{ $index + 1 }}</td>
                            <td style="padding:14px 16px;">{{ optional($response->mobilization)->name ?? 'Unknown' }}</td>
                            <td style="padding:14px 16px;">{{ optional($response->mobilization)->mobile ?? '—' }}</td>
                            <td style="padding:14px 16px;">{{ $response->created_at ? $response->created_at->format('d M Y h:i A') : '—' }}</td>
                            <td style="padding:14px 16px;">{{ $fileCount }}</td>
                            <td style="padding:14px 16px;"><a href="{{ route('responses.view', $response->id) }}" style="color:#2563eb; font-weight:600;">View Full Form</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:18px; text-align:center; color:#64748b;">No submissions found for this linked assignment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <h3 style="font-size:20px; font-weight:700; color:#334155; margin-bottom:12px;">Attached Files</h3>
        @if($fileValues->isEmpty())
            <div style="padding:18px; border:1px dashed #cbd5e1; border-radius:14px; color:#64748b;">No uploaded files found for this form assignment.</div>
        @else
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; background:white; border-radius:12px; overflow:hidden; box-shadow:0 4px 14px rgba(0,0,0,0.05);">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th style="padding:14px 16px; text-align:left;">#</th>
                            <th style="padding:14px 16px; text-align:left;">Candidate</th>
                            <th style="padding:14px 16px; text-align:left;">Field</th>
                            <th style="padding:14px 16px; text-align:left;">File</th>
                            <th style="padding:14px 16px; text-align:left;">Size</th>
                            <th style="padding:14px 16px; text-align:left;">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fileValues as $index => $value)
                            <tr style="border-top:1px solid #e2e8f0;">
                                <td style="padding:14px 16px;">{{ $index + 1 }}</td>
                                <td style="padding:14px 16px;">{{ optional($value->response->mobilization)->name ?? 'Unknown' }}</td>
                                <td style="padding:14px 16px;">{{ optional($value->field)->label ?? 'File' }}</td>
                                <td style="padding:14px 16px;">{{ basename($value->file_url) }}</td>
                                <td style="padding:14px 16px;">{{ $value->file_size ? round($value->file_size / 1024, 2) . ' KB' : 'N/A' }}</td>
                                <td style="padding:14px 16px;"><a href="{{ asset('storage/'.$value->file_url) }}" target="_blank" style="color:#2563eb; font-weight:600;">Open</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

@endsection
