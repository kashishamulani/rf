@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="card">
         <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h3>Create New Assignment</h3>
            <!-- Back Button -->
            <a href="{{ url()->previous() }}" class="btn btn-light" style="border:1px solid #d1d5db; color:#374151;">
                &larr; Back
            </a>
        </div>



        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('activity-assignments.store') }}">
                @csrf

                <div class="mb-4">

                    <select name="assignment_id" class="form-control" required>
                        <option value="">Select Assignment</option>
                        @foreach($assignments as $assignment)
                            <option value="{{ $assignment->id }}">{{ $assignment->assignment_name }}</option>
                        @endforeach
                    </select>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="50">Assign</th>
                            <th>Phase / Activity</th>
                            <th width="220">Team Member</th>
                            <th width="180">Start</th>
                            <th width="120">Days</th>
                            <th width="180">Target</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($phases as $phase)
                            <tr style="background:#f3f4f6;">
                                <td>
                                    <input type="checkbox" class="phase-check" data-phase="{{ $phase->id }}">
                                </td>
                                <td><b>{{ $phase->phase_name }}</b></td>
                                <td>
                                    <select name="phase_member[{{ $phase->id }}]" class="form-control phase-member" disabled>
                                        <option value="">Select Member</option>
                                        @foreach($members as $m)
                                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="datetime-local" name="phase_start[{{ $phase->id }}]"
                                        class="form-control phase-start" value="{{ now()->format('Y-m-d\TH:i') }}">
                                </td>
                                <td>
                                    <input type="number" name="phase_days[{{ $phase->id }}]" class="form-control phase-days"
                                        disabled>
                                </td>
                                <td>
                                    <input type="date" name="phase_target[{{ $phase->id }}]" class="form-control phase-target">
                                </td>
                            </tr>

                            @foreach($phase->activities as $activity)
                                <tr data-phase="{{ $phase->id }}">
                                    <td></td>
                                    <td style="padding-left:30px;">{{ $activity->name }}</td>
                                    <td>
                                        <select name="activity_member[{{ $activity->id }}]" class="form-control activity-member">
                                            <option value="">Select Member</option>
                                            @foreach($members as $m)
                                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="datetime-local" name="activity_start[{{ $activity->id }}]"
                                            class="form-control activity-start" value="{{ now()->format('Y-m-d\TH:i') }}">
                                    </td>
                                    <td>
                                        <input type="number" name="activity_days[{{ $activity->id }}]"
                                            class="form-control activity-days">
                                    </td>
                                    <td>
                                        <input type="date" name="activity_target[{{ $activity->id }}]"
                                            class="form-control activity-target">
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary">Save Assignments</button>
                <a href="{{ route('activity-assignments.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    function formatDate(date) {
        let yyyy = date.getFullYear();
        let mm = String(date.getMonth() + 1).padStart(2, '0');
        let dd = String(date.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
    }

    function nowLocal() {
        let d = new Date();
        let yyyy = d.getFullYear();
        let mm = String(d.getMonth() + 1).padStart(2, '0');
        let dd = String(d.getDate()).padStart(2, '0');
        let hh = String(d.getHours()).padStart(2, '0');
        let mi = String(d.getMinutes()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}T${hh}:${mi}`;
    }

    // Smart calculation
    function smartCalc(row, type) {
        let startInput = row.querySelector('.phase-start, .activity-start');
        let daysInput = row.querySelector('.phase-days, .activity-days');
        let targetInput = row.querySelector('.phase-target, .activity-target');

        if (!startInput || !daysInput || !targetInput) return;

        let start = new Date(startInput.value);
        let days = parseInt(daysInput.value);
        let target = new Date(targetInput.value);

        if (type === 'days' && startInput.value && !isNaN(days)) {
            let t = new Date(start);
            t.setDate(t.getDate() + days);
            targetInput.value = formatDate(t);
        } else if (type === 'target' && startInput.value && targetInput.value) {
            let diff = Math.ceil((target - start) / (1000 * 60 * 60 * 24));
            daysInput.value = diff >= 0 ? diff : 0;
        } else if (type === 'start' && !isNaN(days)) {
            let t = new Date(start);
            t.setDate(t.getDate() + days);
            targetInput.value = formatDate(t);
        }
    }

    // Phase events
    document.querySelectorAll('.phase-start').forEach(el => {
        el.addEventListener('input', function() {
            smartCalc(this.closest('tr'), 'start');
        });
    });

    document.querySelectorAll('.phase-days').forEach(el => {
        el.addEventListener('input', function() {
            smartCalc(this.closest('tr'), 'days');
        });
    });

    document.querySelectorAll('.phase-target').forEach(el => {
        el.addEventListener('input', function() {
            smartCalc(this.closest('tr'), 'target');
        });
    });

    // Activity events
    document.querySelectorAll('.activity-start').forEach(el => {
        el.addEventListener('input', function() {
            smartCalc(this.closest('tr'), 'start');
        });
    });

    document.querySelectorAll('.activity-days').forEach(el => {
        el.addEventListener('input', function() {
            smartCalc(this.closest('tr'), 'days');
        });
    });

    document.querySelectorAll('.activity-target').forEach(el => {
        el.addEventListener('input', function() {
            smartCalc(this.closest('tr'), 'target');
        });
    });

    // Phase checkbox logic
    document.querySelectorAll('.phase-check').forEach(cb => {
        cb.addEventListener('change', function() {
            let phaseId = this.dataset.phase;
            let phaseMember = document.querySelector(`select[name="phase_member[${phaseId}]"]`);
            let phaseDays = document.querySelector(`input[name="phase_days[${phaseId}]"]`);
            let phaseStart = document.querySelector(`input[name="phase_start[${phaseId}]"]`);
            let phaseTarget = document.querySelector(`input[name="phase_target[${phaseId}]"]`);

            // Enable/disable phase inputs
            phaseMember.disabled = !this.checked;
            phaseDays.disabled = !this.checked;
            if (phaseStart) phaseStart.disabled = !this.checked;
            if (phaseTarget) phaseTarget.disabled = !this.checked;

            // Clear values if unchecked
            if (!this.checked) {
                phaseMember.value = '';
                phaseDays.value = '';
                if (phaseTarget) phaseTarget.value = '';
            }

            // Toggle activity rows
            let activityRows = document.querySelectorAll(`tr[data-phase="${phaseId}"]`);
            activityRows.forEach(row => {
                let member = row.querySelector('.activity-member');
                let start = row.querySelector('.activity-start');
                let days = row.querySelector('.activity-days');
                let target = row.querySelector('.activity-target');

                if (this.checked) {
                    // Disable activities
                    member.disabled = true;
                    if (start) start.disabled = true;
                    if (days) days.disabled = true;
                    if (target) target.disabled = true;
                    
                    // Clear values
                    member.value = '';
                    if (days) days.value = '';
                    if (target) target.value = '';
                } else {
                    // Enable activities
                    member.disabled = false;
                    if (start) start.disabled = false;
                    if (days) days.disabled = false;
                    if (target) target.disabled = false;
                    
                    // Reset start if empty
                    if (start && !start.value) start.value = nowLocal();
                }
            });
        });
    });
});











// Format date for datetime-local input
function formatDateTimeLocal(dateStr) {
    if (!dateStr) return '';
    let d = new Date(dateStr);
    let yyyy = d.getFullYear();
    let mm = String(d.getMonth() + 1).padStart(2, '0');
    let dd = String(d.getDate()).padStart(2, '0');
    let hh = String(d.getHours()).padStart(2, '0');
    let mi = String(d.getMinutes()).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}T${hh}:${mi}`;
}

// Format date for date input
function formatDate(dateStr) {
    if (!dateStr) return '';
    let d = new Date(dateStr);
    let yyyy = d.getFullYear();
    let mm = String(d.getMonth() + 1).padStart(2, '0');
    let dd = String(d.getDate()).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
}

// Copy phase values to activities and calculate target if days given
function propagatePhaseToActivities(phaseRow) {
    let phaseId = phaseRow.querySelector('.phase-check').dataset.phase;

    let phaseMember = phaseRow.querySelector('.phase-member').value;
    let phaseStart = phaseRow.querySelector('.phase-start').value;
    let phaseDays = phaseRow.querySelector('.phase-days').value;
    let phaseTarget = phaseRow.querySelector('.phase-target').value;

    let activityRows = document.querySelectorAll(`tr[data-phase="${phaseId}"]`);
    activityRows.forEach(row => {
        let member = row.querySelector('.activity-member');
        let start = row.querySelector('.activity-start');
        let days = row.querySelector('.activity-days');
        let target = row.querySelector('.activity-target');

        if (member) member.value = phaseMember;
        if (start) start.value = formatDateTimeLocal(phaseStart);
        if (days) days.value = phaseDays;

        // Calculate activity target if start + days provided
        if (start.value && days.value) {
            let s = new Date(start.value);
            s.setDate(s.getDate() + parseInt(days.value));
            if (target) target.value = formatDate(s);
        } else if (phaseTarget) {
            if (target) target.value = phaseTarget;
        }
    });
}

// Add listeners to phase inputs
document.querySelectorAll('tr').forEach(phaseRow => {
    if (phaseRow.querySelector('.phase-check')) {
        let inputs = phaseRow.querySelectorAll('.phase-member, .phase-start, .phase-days, .phase-target');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                propagatePhaseToActivities(phaseRow);
            });
        });
    }
});
</script>
@endsection