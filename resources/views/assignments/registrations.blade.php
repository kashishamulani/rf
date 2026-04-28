@extends('layouts.app')

@section('content')

    <style>
        .main-content {
            padding: 80px 15px 15px 210px;
        }

        .page-wrap {
            max-width: 100%;
            margin: auto;
        }

        /* PAGE TITLE */
        .page-title {
            font-size: 22px;
            font-weight: 700;
            background: linear-gradient(135deg, #303048, #ec4899);
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
            transition: .2s;
        }

        .btn-success {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: #fff;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #ec4899);
            color: #fff;
        }

        .btn-secondary {
            background: #6b7280;
            color: #fff;
        }

        .btn:hover {
            transform: translateY(-1px);
            opacity: .9;
        }

        /* FILTER FORM */

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .field {
            display: flex;
            flex-direction: column;
        }

        .field label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #374151;
        }

        .field input,
        .field select {
            padding: 9px 10px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            background: #fff;
            transition: .15s;
        }

        .field input:focus,
        .field select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15);
        }

        /* TABLE CARD */
        .table-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .06);
            overflow-x: auto;
            overflow-y: visible;
            margin-top: 15px;
        }

        /* TABLE */
        .table {
            width: 100%;
            min-width: 900px;
            border-collapse: collapse;
        }

        .table thead {
            background: #f8fafc;
        }

        .table th {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .table th,
        .table td {
            padding: 4px;
            font-weight: 300;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .table tr:hover {
            background: #f9fafb;
        }

        /* ACTION BUTTONS */
        .action-btn {
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin: 0 2px;
        }

        .view {
            background: #e0f2fe;
            color: #0369a1;
        }

        .edit {
            background: #fef9c3;
            color: #854d0e;
        }

        .data {
            background: #ede9fe;
            color: #5b21b6;
        }

        .delete {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            cursor: pointer;
        }

        .delete:hover {
            opacity: .85;
        }

        /* SUCCESS / ERROR */

        .success-message {
            background: #ecfdf5;
            border: 1px solid #10b981;
            color: #065f46;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .error-message {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        /* EMPTY STATE */

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }

        .assin_batch .table-card {
            overflow-x: visible;
        }

        .assin_batch .table-card .action-dropdown {
            z-index: auto;
        }


        .action-dropdown {}



        /* RESPONSIVE */

        @media(max-width:900px) {

            .grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media(max-width:500px) {

            .grid {
                grid-template-columns: 1fr;
            }

        }


        /* ACTION DROPDOWN */

        .action-dropdown {
            position: relative;
            display: inline-block;
        }

        .dots-btn {
            background: #f3f4f6;
            border: none;
            padding: 8px 10px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: .2s;
        }

        .dots-btn:hover {
            background: #e5e7eb;
        }

        /* MENU */

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 35px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, .08);
            min-width: 150px;


            display: none;
            z-index: 9999;
        }

        .table tr {
            position: relative;
        }

        .table td {
            overflow: visible;
        }

        .action-dropdown {
            position: relative;
            z-index: 1000;
        }

        /* LINKS */

        .dropdown-menu a,
        .dropdown-menu button {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: 10px 12px;
            font-size: 13px;
            color: #374151;
            background: none;
            border: none;
            text-decoration: none;
            cursor: pointer;
        }

        .dropdown-menu a:hover {
            background: #f3f4f6;
        }

        .dropdown-delete {
            color: #991b1b;
        }

        .dropdown-delete:hover {
            background: #fee2e2;
        }

        /* SHOW MENU */

        .action-dropdown.active .dropdown-menu {
            display: block;
        }

        .custom-modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: #fff;
            width: 600px;
            margin: 80px auto;
            border-radius: 12px;
            padding: 20px;
            animation: fadeIn .3s;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .close-btn {
            cursor: pointer;
            font-size: 20px;
        }

        .tabs {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }

        .tab-btn {
            padding: 8px 14px;
            border: none;
            background: #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
        }

        .tab-btn.active {
            background: #6366f1;
            color: white;
        }

        .tab-content {
            display: none;
            margin-top: 15px;
        }

        .tab-content.active {
            display: block;
        }

        .eye-btn {
            background: #fff;
            border: none;
            padding: 5px 8px;
            border-radius: 6px;
            cursor: pointer;
        }

        .eye-btn:hover {
            background: #bae6fd;
        }

        .hixu {
            padding: 6px !important;
            font-size: 14px;


        }

        .text-hixu {
            font-size: 12px;
        }
    </style>

    <div class="page-wrap">

        <div
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 15px;">
            <div>
                <h2 class="page-title" style="margin: 0;">Registered Candidates — {{ $assignment->assignment_name }}</h2>
                <span style="font-size: 14px; color: #6b7280;">
                    <!-- Total Candidates: <strong>{{ count($candidates) }}</strong> -->
                </span>
            </div>
            <div style="display: flex; gap: 4px; align-items: center;">

                <a href="{{ route('assignments.addMobilizations', $assignment->id) }}" class="btn btn-success text-hixu">
                    <i class="fa-solid fa-user-plus"></i> Add
                </a>
                <a href="{{ route('assignments.index') }}" class="btn btn-secondary text-hixu">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>

            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="success-message">
                <i class="fa-solid fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="error-message">
                <i class="fa-solid fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <form method="GET" action=""
            style="margin-bottom:8px; background:#fff; padding:12px; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,.05);">

            <div style="display: flex; gap: 6px; align-items: flex-end; flex-wrap: wrap;">

                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">Mob</label>
                    <input type="text" name="mobile" value="{{ request('mobile') }}" placeholder="Mobile"
                        style="padding: 8px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>

                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">Samarth</label>
                    <select name="samarth_done"
                        style="padding: 8px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                        <option value="">All</option>
                        <option value="1" {{ request('samarth_done') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ request('samarth_done') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">UAN</label>
                    <select name="uan_done"
                        style="padding: 8px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                        <option value="">All</option>
                        <option value="1" {{ request('uan_done') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ request('uan_done') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">Docs</label>
                    <select name="documents_done"
                        style="padding: 8px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                        <option value="">All</option>
                        <option value="1" {{ request('documents_done') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ request('documents_done') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">Offer</label>
                    <select name="offer_letter_done"
                        style="padding: 8px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                        <option value="">All</option>
                        <option value="1" {{ request('offer_letter_done') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ request('offer_letter_done') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">Company</label>
                    <input type="text" name="placement_company" value="{{ request('placement_company') }}"
                        placeholder="Company Name"
                        style="padding: 8px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>

                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">From</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                        style="padding: 7px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>

                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">To</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                        style="padding: 7px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>


            </div>
            
                <div style="display: flex; gap: 8px; align-items: center; padding-bottom: 2px; margin-top:4px;">
                    <button type="submit" class="btn btn-primary" style="padding: 8px 20px; height: 38px;">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>

                    <a href="{{ url()->current() }}" class="btn btn-secondary"
                        style="padding: 8px 20px; background: #9ca3af; height: 38px;">
                        <i class="fa-solid fa-rotate"></i> Reset
                    </a>
                </div>

        </form>

        <form id="moveToBatchForm ">


            <div>

            </div>

            <div style="display:flex; gap:10px; align-items:center; margin-bottom:4px; justify-content: space-between;">

                <div>
                    <select id="batch_id" class="field" style="padding:8px 10px; border-radius:8px; border:1px solid #ddd;">
                        <option value="">Select Batch</option>

                        @foreach($assignment->batches as $batch)
                            <option value="{{ $batch->id }}">
                                {{ $batch->batch_code }}
                            </option>
                        @endforeach

                    </select>

                    <button type="button " id="moveToBatchBtn" class="btn btn-primary hixu">
                        <i class="fa-solid fa-layer-group"></i>
                        Move To Batch
                    </button>
                </div>

                <div>
                    Total Candidates: <strong>{{ count($candidates) }}</strong>
                </div>
            </div>


            <div class="assin_batch">
                <div class="table-card">
                    @if(count($candidates) > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>#</th>
                                    <th>Name</th>
                                    <!-- <th>Email</th> -->
                                    <th>Mobile</th>
                                    <th>District</th>
                                    <th>State</th>
                                    <th>Batches</th>
                                    <th style="text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($candidates as $i => $c)
                                    <tr>

                                        <td>
                                            <!-- <input type="checkbox" class="candidate-checkbox" value="{{ $c->id }}"> -->
                                            <input type="checkbox" class="candidate-checkbox" value="{{ $c->id }}">
                                        </td>
                                        <td>{{ $i + 1 }}</td>
                                        <td
                                            style="font-weight: 300; display:flex; align-items:center; gap:8px; font-size:14px; justify-content:space-between;">
                                            {{ $c->name }}

                                            <button type="button" class="eye-btn" onclick="openCandidateModal({{ $c->id }})">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </td>
                                        <!-- <td>{{ $c->email }}</td> -->
                                        <td>{{ $c->mobile }}</td>
                                        <td>{{ $c->city ?? '-' }}</td>
                                        <td>{{ $c->state ?? '-' }}</td>
                                        <td>

                                            @if($c->assignmentBatches->count())

                                                @foreach($c->assignmentBatches as $abs)

                                                    <span
                                                        style="display:inline-flex;align-items:center;gap:6px;background:#ecfdf5;color:#065f46;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;margin-right:4px;">

                                                        <!-- <i class="fa-solid fa-check"></i> -->
                                                        {{ $abs->batch->batch_code }}

                                                    </span>

                                                @endforeach

                                            @else

                                                <span
                                                    style="background:#f3f4f6;color:#6b7280;padding:4px 10px;border-radius:20px;font-size:12px;">

                                                    Not Assigned

                                                </span>

                                            @endif

                                        </td>

                                        <td style="text-align:center; position:relative;">

                                            <div class="action-dropdown">

                                                <button type="button" class="dots-btn">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                </button>

                                                <div class="dropdown-menu">

                                                    <a href="{{ route('assignment.students.view', [$assignment->id, $c->id]) }}">
                                                        <i class="fa-solid fa-file-lines"></i> Data
                                                    </a>

                                                    <a href="{{ route('mobilizations.show', $c->id) }}">
                                                        <i class="fa-solid fa-eye"></i> View
                                                    </a>

                                                    <a href="{{ route('mobilizations.edit', $c->id) }}">
                                                        <i class="fa-solid fa-pen"></i> Edit
                                                    </a>

                                                    <form
                                                        action="{{ route('assignments.removeMobilization', [$assignment->id, $c->id]) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to remove this candidate from the assignment?')">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="dropdown-delete">
                                                            <i class="fa-solid fa-trash"></i> Remove
                                                        </button>

                                                    </form>

                                                </div>

                                            </div>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="empty-state">
                                                <i class="fa-solid fa-inbox"
                                                    style="font-size: 32px; color: #d1d5db; margin-bottom: 10px;"></i>
                                                <p style="margin: 0;">No candidates registered for this assignment yet.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <i class="fa-solid fa-inbox" style="font-size: 48px; color: #d1d5db; margin-bottom: 10px;"></i>
                            <h3 style="color: #374151;">No Candidates Yet</h3>
                            <p>Add some candidates to start tracking their assignment data.</p>
                            <a href="{{ route('assignments.addMobilizations', $assignment->id) }}" class="btn btn-success">
                                <i class="fa-solid fa-user-plus"></i>
                                Add First Candidate
                            </a>
                        </div>
                    @endif
                </div>
            </div>

    </div>


    <!-- CANDIDATE MODAL -->
    <div id="candidateModal" class="custom-modal">
        <div class="modal-content">

            <div class="modal-header">
                <h3>Candidate Details</h3>
                <span class="close-btn" onclick="closeModal()">×</span>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab-btn active" onclick="switchTab('basicTab')">Basic Info</button>
                <button class="tab-btn" onclick="switchTab('assignmentTab')">Assignment Data</button>
            </div>

            <!-- Tab Content -->
            <div id="basicTab" class="tab-content active"></div>
            <div id="assignmentTab" class="tab-content"></div>

        </div>
    </div>

    <script>
        if (window.jQuery && $.fn.select2) {
            $('#batch_id').select2({
                placeholder: 'Select Batches',
                padding: '9px 10px',
                border: '1px solid #e5e7eb',
                borderRadius: '8px',
                fontSize: '14px',
                background: '#fff',
                outline: 'none'
            });
        }

        document.getElementById('selectAll').addEventListener('change', function () {

            let checkboxes = document.querySelectorAll('.candidate-checkbox');

            checkboxes.forEach(cb => {
                cb.checked = this.checked;
            });

        });
        document.getElementById('moveToBatchBtn').addEventListener('click', function () {

            let batchIds = Array.from(document.getElementById('batch_id').selectedOptions).map(option => option.value);

            if (!batchIds.length) {
                alert("Please select at least one batch");
                return;
            }

            let selected = [];

            document.querySelectorAll('.candidate-checkbox:checked').forEach(cb => {
                selected.push(cb.value);
            });

            if (selected.length === 0) {
                alert("Please select candidates");
                return;
            }

            fetch("{{ route('batches.assignCandidates') }}", {

                method: "POST",

                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },

                body: JSON.stringify({
                    batch_ids: batchIds,
                    assignment_id: "{{ $assignment->id }}",
                    candidate_ids: selected
                })

            })
                .then(res => res.json())
                .then(data => {

                    if (data.success) {

                        alert(data.message);
                        location.reload();

                    } else {

                        alert("Something went wrong");

                    }
                });
        });
    </script>

    <script>
        document.querySelectorAll(".dots-btn").forEach(btn => {

            btn.addEventListener("click", function (e) {

                e.stopPropagation();

                document.querySelectorAll(".action-dropdown")
                    .forEach(d => d.classList.remove("active"));

                this.parentElement.classList.toggle("active");

            });

        });

        document.addEventListener("click", function () {

            document.querySelectorAll(".action-dropdown")
                .forEach(d => d.classList.remove("active"));

        });
    </script>
    <script>
        function openCandidateModal(id) {

            let candidates = @json($candidates);

            let candidate = candidates.find(c => c.id === id);

            if (!candidate) return;

            // BASIC DATA
            let basicHTML = `
                                                        <p><strong>Name:</strong> ${candidate.name}</p>
                                                        <p><strong>Mobile:</strong> ${candidate.mobile}</p>
                                                        <p><strong>City:</strong> ${candidate.city ?? '-'}</p>
                                                        <p><strong>State:</strong> ${candidate.state ?? '-'}</p>
                                                    `;

            // ASSIGNMENT DATA
            let data = candidate.assignment_data?.[0] || {};

            let assignmentHTML = `
                                                        <p><strong>Samarth Done:</strong> ${data.samarth_done ? 'Yes' : 'No'}</p>
                                                        <p><strong>UAN Done:</strong> ${data.uan_done ? 'Yes' : 'No'}</p>
                                                        <p><strong>Documents:</strong> ${data.documents_done ? 'Yes' : 'No'}</p>
                                                        <p><strong>Offer Letter:</strong> ${data.offer_letter_done ? 'Yes' : 'No'}</p>
                                                        <p><strong>Company:</strong> ${data.placement_company ?? '-'}</p>
                                                    `;

            document.getElementById('basicTab').innerHTML = basicHTML;
            document.getElementById('assignmentTab').innerHTML = assignmentHTML;

            document.getElementById('candidateModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('candidateModal').style.display = 'none';
        }

        // TAB SWITCH
        function switchTab(tabId) {

            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));

            document.getElementById(tabId).classList.add('active');

            event.target.classList.add('active');
        }

        // CLOSE ON OUTSIDE CLICK
        window.onclick = function (e) {
            let modal = document.getElementById('candidateModal');
            if (e.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
@endsection