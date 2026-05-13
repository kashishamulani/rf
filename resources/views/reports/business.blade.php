@extends('layouts.app')

@section('content')

<style>
.report-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.report-table th {
    padding: 14px;
    background: linear-gradient(135deg, #eef2ff, #fdf2f8);
    font-size: 14px;
    font-weight: 700;
    color: #374151;
    text-align: center;
}

.report-table td {
    padding: 14px;
    border-bottom: 1px solid #f1f5f9;
    text-align: center;
}

.clickable-count {
    cursor: pointer;
    font-weight: 700;
    transition: 0.3s;
}

.clickable-count:hover {
    transform: scale(1.08);
}

.assignment-count {
    color: #4338ca;
}

.batch-count {
    color: #059669;
}

/* MODAL */
.custom-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
}

.custom-modal-content {
    background: white;
    width: 500px;
    max-width: 95%;
    border-radius: 20px;
    padding: 24px;
    animation: popup 0.25s ease;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
}

@keyframes popup {
    from {
        transform: scale(0.8);
        opacity: 0;
    }

    to {
        transform: scale(1);
        opacity: 1;
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.modal-title {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
}

.close-modal {
    cursor: pointer;
    font-size: 22px;
    color: #6b7280;
}

.modal-list {
    max-height: 400px;
    overflow-y: auto;
}

.modal-item {
    padding: 10px 14px;
    margin-bottom: 10px;
    background: #f9fafb;
    border-radius: 10px;
    font-size: 14px;
    color: #374151;
}
</style>

<div class="p-6">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="font-size:22px; font-weight:600; margin:0; color:#111827;">
            <i class="fa-solid fa-chart-line" style="background:linear-gradient(135deg,#6366f1,#ec4899);
               -webkit-background-clip:text;
               -webkit-text-fill-color:transparent;"></i>
            Business Report
        </h2>
    </div>

    {{-- FILTER --}}
    <form method="GET" style="margin-bottom:20px; display:flex; gap:10px; flex-wrap:wrap; align-items:end;">

        <div>
            <label style="font-size:12px;">State</label><br>

            <select name="state" id="stateDropdown" style="padding:8px 12px;
                   border-radius:10px;
                   border:1px solid #e5e7eb;
                   min-width:180px;">

                <option value="">All States</option>

            </select>

        </div>

        <div>
            <label style="font-size:12px;">HR Name</label><br>
            <input type="text" name="hr" value="{{ request('hr') }}" placeholder="HR Name"
                style="padding:8px 12px;border-radius:10px;border:1px solid #e5e7eb;">
        </div>

        <div>
            <label style="font-size:12px;">From Date</label><br>
            <input type="date" name="from_date" value="{{ request('from_date') }}"
                style="padding:8px 12px;border-radius:10px;border:1px solid #e5e7eb;">
        </div>

        <div>
            <label style="font-size:12px;">To Date</label><br>
            <input type="date" name="to_date" value="{{ requlsest('to_date') }}"
                style="padding:8px 12px;border-radius:10px;border:1px solid #e5e7eb;">
        </div>

        <div style="display:flex; gap:10px;">

            <button type="submit" style="padding:10px 18px;
                   background:#6366f1;
                   color:white;
                   border:none;
                   border-radius:10px;
                   height:42px;
                   cursor:pointer;
                   font-weight:600;">
                Filter
            </button>

            <a href="{{ url()->current() }}" style="padding:10px 18px;
              background:#ef4444;
              color:white;
              text-decoration:none;
              border-radius:10px;
              height:42px;
              display:flex;
              align-items:center;
              justify-content:center;
              font-weight:600;">

                Reset

            </a>

        </div>

    </form>

    @php
    $grandAssignments = 0;
    $grandBatches = 0;
    $grandValue = 0;
    $grandPayment = 0;
    @endphp

    <div style="overflow-x:auto;">

        <table class="report-table">

            <thead>
                <tr>
                    <th>#</th>
                    <th>State</th>
                    <th>Assignments</th>
                    <th>Batches</th>
                    <th>Total Value</th>
                    <th>Total Payment</th>
                </tr>
            </thead>

            <tbody>

                @forelse($data as $row)

                @php
                $grandAssignments += $row->total_assignments;
                $grandBatches += $row->total_batches;
                $grandValue += $row->total_value;
                $grandPayment += $row->total_payment;
                @endphp

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td style="font-weight:700;">
                        {{ $row->state }}
                    </td>

                    {{-- ASSIGNMENT COUNT --}}
                    <td>

                        <span class="clickable-count assignment-count" onclick="openModal(
                                    'Assignments - {{ $row->state }}',
                                    `@foreach(explode(',', $row->assignment_names) as $assignment)
                                        <div class='modal-item'>{{ trim($assignment) }}</div>
                                     @endforeach`
                                  )">

                            {{ $row->total_assignments }}

                        </span>

                    </td>

                    {{-- BATCH COUNT --}}
                    <td>

                        <span class="clickable-count batch-count" onclick="openModal(
                                    'Batches - {{ $row->state }}',
                                    `@foreach(explode(',', $row->batch_names) as $batch)
                                        <div class='modal-item'>{{ trim($batch) }}</div>
                                     @endforeach`
                                  )">

                            {{ $row->total_batches }}

                        </span>

                    </td>

                    <td style="font-weight:700; color:#4338ca;">
                        ₹{{ number_format($row->total_value ?? 0,2) }}
                    </td>

                    <td style="font-weight:700; color:#059669;">
                        ₹{{ number_format($row->total_payment ?? 0,2) }}
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" style="padding:20px; color:#9ca3af;">
                        No report data found.
                    </td>
                </tr>

                @endforelse

                {{-- TOTAL --}}
                <tr style="background:#f9fafb; font-weight:700;">

                    <td colspan="2" style="text-align:right;">
                        TOTAL
                    </td>

                    <td>
                        {{ $grandAssignments }}
                    </td>

                    <td>
                        {{ $grandBatches }}
                    </td>

                    <td style="color:#4338ca;">
                        ₹{{ number_format($grandValue,2) }}
                    </td>

                    <td style="color:#059669;">
                        ₹{{ number_format($grandPayment,2) }}
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

{{-- MODAL --}}
<div class="custom-modal" id="customModal">

    <div class="custom-modal-content">

        <div class="modal-header">

            <div class="modal-title" id="modalTitle">
                Details
            </div>

            <div class="close-modal" onclick="closeModal()">
                &times;
            </div>

        </div>

        <div class="modal-list" id="modalBody"></div>

    </div>

</div>

<script>
function openModal(title, content) {
    document.getElementById('modalTitle').innerHTML = title;
    document.getElementById('modalBody').innerHTML = content;

    document.getElementById('customModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('customModal').style.display = 'none';
}

window.onclick = function(event) {
    let modal = document.getElementById('customModal');

    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>


<script>
document.addEventListener("DOMContentLoaded", function() {

    let dropdown = document.getElementById('stateDropdown');

    let selectedState = "{{ request('state') }}";

    fetch("{{ url('/states') }}")

        .then(response => response.json())

        .then(data => {

            console.log("States API Response:", data);

            // Handle object wrapped response
            if (data.states) {
                data = data.states;
            }

            // Handle direct array response
            if (Array.isArray(data)) {
                data.forEach(function(state) {

                    let option = document.createElement('option');

                    let value = '';

                    // STRING ARRAY
                    if (typeof state === 'string') {
                        value = state;
                    } else {
                        // OBJECT ARRAY
                        value =
                            state.name ||
                            state.state_name ||
                            state.state ||
                            '';
                    }

                    option.value = value;
                    option.textContent = value;

                    if (value == selectedState) {
                        option.selected = true;
                    }

                    dropdown.appendChild(option);

                });
            }

        })
        .catch(error => {

            console.error('State fetch error:', error);

        });

});
</script>

@endsection