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
    margin-bottom: 20px;
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
    min-width: 650px;
    border-collapse: collapse;
}

.table thead {
    background: #f8fafc;
}

.table th,
.table td {
    padding: 12px 14px;
    border-bottom: 1px solid #f1f5f9;
    text-align: left;
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

/* FILTER FORM STYLES */
.mobi_filter {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    margin-bottom: 20px;
}

.filter-row {
    display: flex;
    gap: 15px;
    align-items: flex-end;
    flex-wrap: wrap;
}

.filter-group {
    flex: 1 1 180px;
}

.filter-group label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 5px;
}

.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: 0.2s;
}

.form-control:focus {
    border-color: #6366f1;
    outline: none;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15);
}

.filter-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.pagination {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}

/* ALERT MESSAGES */
.alert-success {
    background: #ecfdf5;
    border: 1px solid #10b981;
    color: #065f46;
    padding: 12px 16px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.alert-error {
    background: #fee2e2;
    border: 1px solid #f87171;
    color: #991b1b;
    padding: 12px 16px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: #f9fafb;
    border-radius: 16px;
    color: #6b7280;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}

.badge-info {
    background: #e0f2fe;
    color: #0369a1;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
</style>

<div class="page-wrap">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if(session('error'))
    <div class="alert-error">
        {{ session('error') }}
    </div>
    @endif

    <div
        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap:10px;">

        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">

            {{-- BACK BUTTON --}}


            <h2 class="page-title" style="margin-bottom:0;">
                Add Candidates — {{ $assignment->assignment_name }}
            </h2>
        </div>

        <div>
            <span class="badge-info">
                Requirement: {{ $assignment->requirement ?? 'N/A' }}
            </span>
        </div>

        <a href="{{ route('assignments.registrations', $assignment->id) }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>

    {{-- FILTER FORM --}}
    <form method="GET" action="{{ route('assignments.addMobilizations', $assignment->id) }}" class="mobi_filter">
        <div class="filter-row">
            {{-- NAME --}}
            <div class="filter-group">
                <label>Name</label>
                <input type="text" name="name" value="{{ request('name') }}" class="form-control"
                    placeholder="Search by name">
            </div>

            {{-- MOBILE --}}
            <div class="filter-group">
                <label>Mobile</label>
                <input type="text" name="mobile" value="{{ request('mobile') }}" class="form-control"
                    placeholder="Search by mobile">
            </div>


          
            {{-- STATE --}}
            <div class="filter-group">
                <label>State</label>

                <select name="state" id="filterState" class="form-control">
                    <option value="">Select State</option>
                </select>
            </div>

            {{-- DISTRICT --}}
            <div class="filter-group">
                <label>District</label>

                <select name="city" id="filterDistrict"class="form-control">
               
                    <option value="">Select District</option>
                </select>
            </div>
            {{-- BUTTONS --}}
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter"></i> Apply Filters
                </button>

                <a href="{{ route('assignments.addMobilizations', $assignment->id) }}" class="btn btn-secondary">
                    <i class="fa-solid fa-rotate"></i> Reset
                </a>
            </div>
        </div>
    </form>

    {{-- ADD CANDIDATES FORM --}}
    <form method="POST" action="{{ route('assignments.storeMobilizations', $assignment->id) }}">
        @csrf

        @if($isFiltered)
        @if($mobilizations->count() > 0)
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
                            <input type="checkbox" name="mobilization_ids[]" value="{{ $m->id }}">
                        </td>
                        <td>{{ $mobilizations->firstItem() + $i }}</td>
                        <td>{{ $m->name }}</td>
                        <td>{{ $m->mobile }}</td>
                        <td>{{ $m->city ?? '—' }}</td>
                        <td>{{ $m->state ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 30px;">
                            No candidates found matching your filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if(method_exists($mobilizations, 'links'))
        <div class="pagination">
            {{ $mobilizations->appends(request()->query())->links() }}
        </div>
        @endif

        {{-- ACTION BUTTONS --}}
        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-user-plus"></i> Add Selected Candidates
            </button>

            <a href="{{ route('assignments.registrations', $assignment->id) }}" class="btn btn-secondary">
                Cancel
            </a>
        </div>
        @else
        <div class="empty-state">
            <i class="fa-solid fa-users-slash"></i>
            <p>No candidates found matching your search criteria.</p>
            <p style="font-size: 13px; margin-top: 10px;">Try different filters or <a
                    href="{{ route('mobilizations.create') }}">add a new candidate</a>.</p>
        </div>

        <div style="margin-top: 20px;">
            <a href="{{ route('assignments.registrations', $assignment->id) }}" class="btn btn-secondary">
                Back to Registrations
            </a>
        </div>
        @endif
        @else
        <div class="empty-state">
            <i class="fa-solid fa-filter-circle-xmark"></i>
            <p>Please apply filters above to view candidates</p>
            <p style="font-size: 13px; margin-top: 10px;">Search by name, mobile, state, or district to find candidates
                to add to this assignment.</p>
        </div>
        @endif

    </form>
</div>


<script>
    window.stateDropdownId = "filterState";
    window.cityDropdownId = "filterDistrict";

    window.selectedState = "{{ request('state') }}";
    window.selectedDistrict = "{{ request('district') }}";
</script>
<script src="{{ asset('js/state.js') }}"></script>
<script>
// ==================== CHECKALL FUNCTIONALITY ====================
document.getElementById('checkAll')?.addEventListener('click', function() {
    document.querySelectorAll('input[name="mobilization_ids[]"]')
        .forEach(cb => cb.checked = this.checked);
});
</script>

@endsection