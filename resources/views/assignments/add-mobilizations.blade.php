    @extends('layouts.app')

    @section('content')

    <style>
    /* PAGE WRAPPER */
    .page-wrap {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }

    /* PAGE TITLE */
    .page-title {
        font-size: 22px;
        font-weight: 700;
        background: linear-gradient(135deg, #6366f1, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* BUTTONS */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6366f1, #ec4899);
        color: #fff;
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
    }

    .btn:hover {
        transform: translateY(-1px);
        opacity: 0.9;
    }

    /* TABLE CARD */
    .table-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
        overflow-x: auto;
        margin-top: 15px;
    }

    .table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
    }

    .table thead {
        background: #f8fafc;
    }

    .table th,
    .table td {
        padding: 14px;
        border-bottom: 1px solid #f1f5f9;
    }

    .table tr:hover {
        background: #f9fafb;
    }

    /* CHECKBOX */
    input[type="checkbox"] {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }
    </style>

    <div class="page-wrap">

        <h2 class="page-title">
            Add Candidates — {{ $assignment->assignment_name }}
        </h2>

        <form method="POST"
            action="{{ route('assignments.storeMobilizations', $assignment->id) }}">
            @csrf

            <div class="table-card">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="checkAll">
                            </th>
                            <th>#</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>District</th>
                            <th>State</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($mobilizations as $i => $m)
                        <tr>
                            <td>
                                <input type="checkbox"
                                    name="mobilization_ids[]"
                                    value="{{ $m->id }}">
                            </td>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $m->name }}</td>
                            <td>{{ $m->mobile }}</td>
                            <td>{{ $m->city }}</td>
                            <td>{{ $m->state }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:20px;">
                                No candidates available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top:18px; display:flex; gap:10px;">
                <button class="btn btn-primary">
                    ✔ Add Selected Candidates
                </button>

                <a href="{{ route('assignments.registrations', $assignment->id) }}"
                class="btn btn-secondary">
                Cancel
                </a>
            </div>

        </form>

    </div>

    <script>
    document.getElementById('checkAll').onclick = function() {
        document.querySelectorAll('input[name="mobilization_ids[]"]')
            .forEach(cb => cb.checked = this.checked);
    };
    </script>

    @endsection