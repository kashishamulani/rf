@extends('layouts.app')

@section('content')


<style>
.disabled-row {
    background-color: #f1f3f5 !important;
    opacity: 0.7;
}

.phase-row {
    background: #f3f4f6;
}

.disabled-row {
    background: #e9ecef !important;
    opacity: 0.6;
}
</style>
<div class="container">

    {{-- SUCCESS --}}
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ERROR --}}
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- VALIDATION --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <b>Please fix the following:</b>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <form method="POST" action="{{ route('activity-assignments.update', $assignment->id) }}">
        @csrf
        @method('PUT')

        {{-- HEADER CARD --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Assignment</h4>
                <a href="{{ route('activity-assignments.index') }}" class="btn btn-secondary btn-sm">
                    ← Back
                </a>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label><b>Assignment</b></label>
                        <input type="text" class="form-control" value="{{ $assignment->assignment_name }}" readonly>
                        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                    </div>

                    <div class="col-md-6">
                        <label><b>Created Date</b></label>
                        <input type="text" class="form-control" value="{{ $assignment->created_at->format('d M Y') }}"
                            readonly>
                    </div>
                </div>
            </div>
        </div>


        {{-- TABLE CARD --}}
        <div class="card">
            <div class="card-body p-0">

                <table class="table table-bordered mb-0">
                    <thead>
                        <tr class="phase-row">
                            <th width="50">Assign</th>
                            <th>Phase / Activity</th>
                            <th width="250">Team Member(s)</th>
                            <th width="180">Start</th>
                            <th width="120">Days</th>
                            <th width="180">Target</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($phases as $phase)

                        @php
                        $phaseChecked = isset($phaseAssignments[$phase->id]);
                        $phaseData = $phaseChecked ? $phaseAssignments[$phase->id] : null;
                        $phaseMemberIds = $phaseChecked ? $phaseData->pluck('team_member_id')->toArray() : [];
                        $firstPhase = $phaseChecked ? $phaseData->first() : null;
                        @endphp

                        {{-- PHASE ROW --}}
                        <tr class="phase-row">
                            <td>
                                <input type="checkbox" class="phase-check" data-phase="{{ $phase->id }}"
                                    {{ $phaseChecked ? 'checked' : '' }}>
                            </td>

                            <td><b>{{ $phase->phase_name }}</b></td>

                            <td>
                                <select name="phase_member[{{ $phase->id }}][]" class="form-control phase-member">
                                    <option value="">Select Members</option>
                                    @foreach($members as $m)
                                    <option value="{{ $m->id }}"
                                        {{ in_array($m->id, $phaseMemberIds) ? 'selected' : '' }}>
                                        {{ $m->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="datetime-local" name="phase_start[{{ $phase->id }}]"
                                    class="form-control phase-start" value="{{ $firstPhase && $firstPhase->start_date
                                                ? \Carbon\Carbon::parse($firstPhase->start_date)->format('Y-m-d\TH:i')
                                                : now()->format('Y-m-d\TH:i') }}">
                            </td>

                            <td>
                                <input type="number" name="phase_days[{{ $phase->id }}]" class="form-control phase-days"
                                    value="{{ $firstPhase->days ?? '' }}">
                            </td>

                            <td>
                                <input type="date" name="phase_target[{{ $phase->id }}]"
                                    class="form-control phase-target" value="{{ $firstPhase && $firstPhase->target_date
                                                ? \Carbon\Carbon::parse($firstPhase->target_date)->format('Y-m-d')
                                                : '' }}">
                            </td>
                        </tr>



                        {{-- ACTIVITIES --}}
                        @foreach($phase->activities as $activity)

                        @php
                        $activityData = isset($activityAssignments[$activity->id])
                        ? $activityAssignments[$activity->id] : null;

                        $firstActivity = $activityData ? $activityData->first() : null;
                        @endphp

                        <tr data-phase="{{ $phase->id }}">
                            <td></td>

                            <td style="padding-left:30px;">
                                {{ $activity->name }}
                            </td>

                            <td>
                                <select name="activity_member[{{ $activity->id }}]"
                                    class="form-control activity-member">

                                    <option value="">Select Member</option>

                                    @foreach($members as $m)
                                    <option value="{{ $m->id }}"
                                        {{ $firstActivity && $firstActivity->team_member_id == $m->id ? 'selected' : '' }}>
                                        {{ $m->name }}
                                    </option>
                                    @endforeach

                                </select>
                            </td>

                            <td>
                                <input type="datetime-local" name="activity_start[{{ $activity->id }}]"
                                    class="form-control activity-start" value="{{ $firstActivity && $firstActivity->start_date
? \Carbon\Carbon::parse($firstActivity->start_date)->format('Y-m-d\TH:i')
: now()->format('Y-m-d\TH:i') }}">
                            </td>

                            <td>
                                <input type="number" name="activity_days[{{ $activity->id }}]"
                                    class="form-control activity-days" value="{{ $firstActivity->days ?? '' }}">
                            </td>

                            <td>
                                <input type="date" name="activity_target[{{ $activity->id }}]"
                                    class="form-control activity-target" value="{{ $firstActivity && $firstActivity->target_date
? \Carbon\Carbon::parse($firstActivity->target_date)->format('Y-m-d')
: '' }}">
                            </td>

                        </tr>

                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Update Assignments</button>
            <a href="{{ route('activity-assignments.index') }}" class="btn btn-secondary">Cancel</a>
        </div>

    </form>
</div>


{{-- JS --}}
<script>
document.addEventListener("DOMContentLoaded", function() {

            function formatDate(date) {
                return date.toISOString().split('T')[0];
            }

            function smartCalc(row, type) {

                let start = row.querySelector('.phase-start, .activity-start');
                let days = row.querySelector('.phase-days, .activity-days');
                let target = row.querySelector('.phase-target, .activity-target');

                if (!start || !days || !target) return;

                let s = new Date(start.value.replace('T',' '));
                let d = parseInt(days.value);
                let t = new Date(target.value);

                if (type === 'days' && start.value && !isNaN(d)) {
                    let temp = new Date(s);
                    temp.setDate(temp.getDate() + d);
                    target.value = formatDate(temp);
                } else if (type === 'target' && start.value && target.value) {
                    let diff = Math.ceil((t - s) / (1000 * 60 * 60 * 24));
                    days.value = diff >= 0 ? diff : 0;
                } else if (type === 'start' && !isNaN(d)) {
                    let temp = new Date(s);
                    temp.setDate(temp.getDate() + d);
                    target.value = formatDate(temp);
                }
            }

            document.querySelectorAll('.phase-start, .activity-start')
                .forEach(el => el.addEventListener('input',
                    () => smartCalc(el.closest('tr'), 'start')));

            document.querySelectorAll('.phase-days, .activity-days')
                .forEach(el => el.addEventListener('input',
                    () => smartCalc(el.closest('tr'), 'days')));

            document.querySelectorAll('.phase-target, .activity-target')
                .forEach(el => el.addEventListener('input',
                    () => smartCalc(el.closest('tr'), 'target')));


            // 🔥 PHASE TOGGLE CONTROL
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // function calculateTargetDate(row) {
    //     const startInput = row.querySelector(".activity-start");
    //     const daysInput = row.querySelector(".activity-days");
    //     const targetInput = row.querySelector(".activity-target");

    //     if (!startInput || !daysInput || !targetInput) return;

    //     const startValue = startInput.value;
    //     const daysValue = parseInt(daysInput.value);

    //     if (!startValue || isNaN(daysValue)) {
    //         targetInput.value = "";
    //         return;
    //     }

    //     let startDate = new Date(startValue);
    //     startDate.setDate(startDate.getDate() + daysValue);

    //     const yyyy = startDate.getFullYear();
    //     const mm = String(startDate.getMonth() + 1).padStart(2, '0');
    //     const dd = String(startDate.getDate()).padStart(2, '0');

    //     targetInput.value = `${yyyy}-${mm}-${dd}`;
    // }

    // listen for changes
    document.querySelectorAll("tr[data-phase]").forEach(row => {

        const startInput = row.querySelector(".activity-start");
        const daysInput = row.querySelector(".activity-days");

        if (startInput) {
            startInput.addEventListener("change", () => calculateTargetDate(row));
        }

        if (daysInput) {
            daysInput.addEventListener("input", () => calculateTargetDate(row));
        }
    });

});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('.phase-check').forEach(function (checkbox) {

        function togglePhase(phaseId, checked) {

    const phaseRow = document.querySelector(
        '.phase-check[data-phase="'+phaseId+'"]'
    ).closest('tr');

    const activityRows = document.querySelectorAll(
        'tr[data-phase="' + phaseId + '"]'
    );

    if (checked) {

        // enable phase inputs
        phaseRow.querySelectorAll('select, input:not([type=checkbox])')
            .forEach(el => el.disabled = false);

        // disable activities BUT keep values visible
        activityRows.forEach(row => {
            row.classList.add('disabled-row');
            row.querySelectorAll('select, input')
                .forEach(el => el.readOnly = true);   // ⭐ change here
        });

    } else {

        // disable phase inputs
        phaseRow.querySelectorAll('select, input:not([type=checkbox])')
            .forEach(el => el.disabled = true);

        // enable activities
        activityRows.forEach(row => {
            row.classList.remove('disabled-row');
            row.querySelectorAll('select, input')
                .forEach(el => el.readOnly = false);  // ⭐ change here
        });
    }
}

        checkbox.addEventListener('change', function () {
            togglePhase(this.dataset.phase, this.checked);
        });

        // run on page load
        togglePhase(checkbox.dataset.phase, checkbox.checked);
    });

});
</script>
@endsection