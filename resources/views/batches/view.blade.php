@extends('layouts.app')

@section('content')

<style>
.page-wrap {
    max-width: 1400px;
    margin: auto;
    padding: 24px;
}

.card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, .06);
    border: 1px solid #e5e7eb;
}

.badge {
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.badge-indigo {
    background: #e0e7ff;
    color: #3730a3;
}

.badge-green {
    background: #d1fae5;
    color: #065f46;
}

.badge-red {
    background: #fee2e2;
    color: #991b1b;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: .25s;
    cursor: pointer;
}

.btn-gray {
    background: #e5e7eb;
    color: #374151;
}

.btn-gray:hover {
    background: #d1d5db;
}

.btn-green {
    background: #22c55e;
    color: #fff;
}

.btn-green:hover {
    background: #16a34a;
}

.btn-red {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.btn-red:hover {
    background: #fecaca;
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #f9fafb;
    font-weight: 600;
    color: #374151;
}

th,
td {
    padding: 12px 16px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
}

.progress {
    width: 100%;
    height: 10px;
    background: #e5e7eb;
    border-radius: 999px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(135deg, #6366f1, #ec4899);
    transition: width .4s ease;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
}

.info-box {
    padding: 16px;
    border-radius: 14px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
}

.info-title {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 4px;
}

.info-value {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
}
</style>

<div class="page-wrap">

    {{-- ================= HEADER ================= --}}
    <div style="display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap;margin-bottom:28px">
        <div>
            <h1 style="font-size:28px;font-weight:800;color:#111827;margin-bottom:8px">
                <i class="fa-solid fa-layer-group" style="color:#6366f1"></i>
                Batch {{ $batch->batch_code }}
            </h1>

            <div style="display:flex;gap:14px;flex-wrap:wrap;color:#6b7280;font-size:14px">

                <span class="badge badge-indigo">{{ $batch->status }}</span>
                <span class="badge badge-green">{{ $totalCandidates }} Candidates</span>
            </div>
        </div>
        <!-- 
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <a href="{{ route('batches.trackingSheet',$batch->id) }}" class="btn btn-green" target="_blank">
                <i class="fa-solid fa-file-pdf"></i> Download Tracking Sheet
            </a>
              <a href="{{ route('batches.trackingSheet',$batch->id) }}" class="btn btn-primary" target="_blank"
                style="background:#16a34a;">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </a>

            <a href="{{ route('batches.index') }}" class="btn btn-gray">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
           
        </div> -->

        <div style="display:flex;gap:10px;flex-wrap:wrap">

            <a href="{{ route('batches.trackingSheet',$batch->id) }}" class="btn btn-primary" target="_blank"
                style="background:#16a34a;">
                <i class="fa-solid fa-file-pdf"></i> Tracking Sheet
            </a>

            <a href="{{ route('attendance.pdf',$batch->id) }}" class="btn btn-primary" target="_blank"
                style="background:#6366f1">
                <i class="fa-solid fa-file-pdf"></i> Attendance Sheet
            </a>

            <a href="{{ route('batches.index') }}" class="btn btn-gray">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>

        </div>
    </div>

    {{-- ================= CANDIDATES ================= --}}
    @if($totalCandidates)
    <div>
        <div class="section-title" style="margin-bottom:18px">
            <i class="fa-solid fa-users"></i>
            Candidates ({{ $totalCandidates }})
        </div>

        @foreach($candidatesByAssignment as $assignmentId => $candidates)
        <div class="card" style="margin-bottom:26px">
            <div style="padding:16px 20px;background:#f8fafc;border-bottom:1px solid #e5e7eb">
                <strong>{{ optional($assignments->firstWhere('id', $assignmentId))->assignment_name }}</strong>
                <span style="color:#6b7280;font-size:13px;margin-left:10px">
                    {{ $candidates->count() }} candidates
                </span>
            </div>

            <div style="overflow-x:auto">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Candidate</th>
                            <th>Assignment</th>
                            <th>Added On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($candidates as $i => $candidate)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td style="font-weight:600">
                                {{ $candidate->candidate_name }}
                                <br>
                                <small style="color:#6b7280">
                                    ID: {{ $candidate->student_id }}
                                </small>
                            </td>
                            <td>{{ $candidate->assignment_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($candidate->created_at)->format('d M Y') }}</td>
                            <td>
                                <form
                                    action="{{ route('batches.candidates.destroy', [$batch->id, $candidate->candidate_id]) }}"
                                    method="POST"
                                    onsubmit="return confirm('Remove candidate {{ $candidate->student_id }} ?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-red">
                                        <i class="fa-solid fa-trash"></i> Remove
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>



@endsection