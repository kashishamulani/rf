@extends('layouts.app')

@section('content')

    <style>
        /* PAGE WRAPPER */
        /* .page-wrap {
                            width: 100%;
                            max-width: 100%;
                            margin: 0;
                            padding: 24px;
                            box-sizing: border-box;
                        } */



        .main-content {
            padding: 80px 15px 15px 210px;
        }

        /* HEADER */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

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

        .btn-success {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: #fff;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #ec4899);
            color: #fff;
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
            /* allow scroll ONLY if absolutely needed */
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }

        /* HEADER */
        .table thead {
            background: #f8fafc;
        }

        .table th {
            padding: 12px 10px;
            font-weight: 600;
            text-align: left;
            border-bottom: 2px solid #e5e7eb;
            white-space: normal;
            word-break: break-word;
        }

        /* BODY */
        .table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f1f5f9;
            color: #374151;
            white-space: normal;
            word-break: break-word;
        }

        /* ROW HOVER */
        .table tr:hover {
            background: #f9fafb;
        }

        /* KEEP ACTION BUTTONS INLINE */
        .table td:last-child {
            white-space: nowrap;
        }

        /* ACTION BUTTONS */
        .action-btn {
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 13px;
            text-decoration: none;
        }

        .view {
            background: #e0f2fe;
            color: #0369a1;
        }

        .edit {
            background: #fef9c3;
            color: #854d0e;
        }

        .delete {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            cursor: pointer;
        }

        /* FILTER INPUT */
        .filter-input {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 5px;
        }


        .filter-input:focus {
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15);
        }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            align-items: center;
            justify-content: center;
            z-index: 999;
        }

        .modal-box {
            background: #fff;
            padding: 24px;
            border-radius: 14px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.2);
        }

        /* PAGINATION */
        nav[role="navigation"] {
            margin-top: 25px;
        }

        nav[role="navigation"] a,
        nav[role="navigation"] span {
            border-radius: 8px !important;
            font-weight: 600;
        }

        nav[role="navigation"] a {
            color: #6366f1;
        }

        nav[role="navigation"] a:hover {
            background: #eef2ff;
            color: #ec4899;
        }

        nav[role="navigation"] span[aria-current="page"] span {
            background: linear-gradient(135deg, #6366f1, #ec4899);
            color: white;
            border-color: transparent;
        }

        nav[role="navigation"] span[aria-disabled="true"] span {
            color: #9ca3af;
        }

        nav[role="navigation"] svg {
            width: 14px;
            height: 14px;
            fill: #6366f1;
        }

        nav[role="navigation"] a:hover svg {
            fill: #ec4899;
        }

        .pg-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            font-size: 18px;
            font-weight: 700;
            border-radius: 10px;
            text-decoration: none;
            background: #fff;
            border: 1px solid #e5e7eb;
            color: #6366f1;
            transition: 0.2s;
        }

        .pg-btn:hover {
            background: linear-gradient(135deg, #6366f1, #ec4899);
            color: #fff;
            border-color: transparent;
            transform: translateY(-1px);
        }

        .pg-btn.disabled {
            color: #cbd5e1;
            cursor: not-allowed;
            background: #f9fafb;
        }

        /* Hem Css */

        .mobi_table table thead tr th,
        .mobi_table table tbody tr {
            font-size: 12px;
        }

        /* .mobi_filter{
                               margin-bottom: 18px;
                            background: #fff;
                            padding: 14px;
                            border-radius: 12px;
                            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);


                            flex-wrap: wrap;
                            display: grid;
                            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                            gap: 14px;
                        } */

        /* ACTION BUTTON AREA */
        .filter-actions {
            display: flex;
            align-items: flex-end;
            gap: 10px;
        }

        /* Make buttons align nicely in grid */
        .mobi_filter .filter-actions {
            grid-column: span 2;
        }

        /* Reset button style */
        .btn-reset {
            background: #e5e7eb;
            color: #111827;
        }


        .mobi_filter {
            margin-bottom: 18px;
            background: #fff;
            padding: 16px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);

            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 6px;
        }

        /* .mobi_filter .filter-input{
                            width: calc(20% - 8px);
                        } */



        /* MOBILE */
        @media(max-width:768px) {

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            table {
                font-size: 13px;
            }

            th,
            td {
                padding: 10px 6px;
            }

            /* allow horizontal scroll ONLY on very small screens */
            .table-card {
                overflow-x: auto;
            }
        }



        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }


        @media (max-width: 768px) {
            .table-wrapper {
                overflow-x: auto;
            }

            table {
                font-size: 13px;
            }
        }

        /* MODAL BACKDROP */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(6px);
            align-items: center;
            justify-content: center;
            z-index: 999;
            padding: 20px;
        }

        /* SHOW MODAL */
        .modal.show {
            display: flex;
        }

        /* MODAL BOX */
        .modal-box {
            background: #ffffff;
            width: 100%;
            max-width: 460px;
            border-radius: 16px;
            padding: 26px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25);
            animation: modalFade .25s ease;
        }

        /* TITLE */
        .modal-box h3 {
            margin: 0 0 12px;
            font-size: 18px;
            font-weight: 700;
        }

        /* CONTENT */
        .modal-box p {
            font-size: 14px;
            color: #6b7280;
        }

        /* BUTTON AREA */
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        /* CANCEL BUTTON */
        .btn-cancel {
            background: #e5e7eb;
            color: #111827;
        }

        /* ANIMATION */
        @keyframes modalFade {
            from {
                opacity: 0;
                transform: translateY(-12px) scale(.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }


        /* NAME CELL FIX */
        .name-cell {

            gap: 10px;
            font-weight: 600;
        }

        /* Candidate Name */
        .candidate-name {
            white-space: nowrap;
        }

        /* NEW BADGE */
        .new-badge {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 30px;
            letter-spacing: .6px;
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(34, 197, 94, 0.3);
            animation: fadeInBadge 0.4s ease-in-out;
        }

        /* Smooth animation */
        @keyframes fadeInBadge {
            from {
                opacity: 0;
                transform: translateY(-4px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* EMAIL ALWAYS LOWERCASE */
        .email-cell {
            text-transform: lowercase;
        }

        .action-dropdown {
            position: relative;
            display: inline-block;
        }

        .dots-btn {
            background: #f3f4f6;
            border: none;
            font-size: 18px;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 36px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            min-width: 150px;
            z-index: 9999;
            /* increase */
            overflow: visible;
        }

        .dropdown-menu a,
        .dropdown-menu button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 8px 12px;
            background: none;
            border: none;
            text-decoration: none;
            color: #333;
            cursor: pointer;
        }

        .dropdown-menu a:hover,
        .dropdown-menu button:hover {
            background: #f3f4f6;
        }

        /* ACTION CELL */
        .action-cell {
            text-align: center;
            vertical-align: middle;
        }

        /* DROPDOWN WRAPPER */
        .action-dropdown {
            position: relative;
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }

        /* 3 DOT BUTTON */
        .dots-btn {
            background: #f3f4f6;
            border: none;
            padding: 8px 10px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dots-btn:hover {
            background: #e5e7eb;
        }

        /* DROPDOWN MENU */
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 36px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            min-width: 150px;
            z-index: 999;
            overflow: hidden;
        }

        /* ITEMS */
        .dropdown-menu a,
        .dropdown-menu button {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: 10px 12px;
            font-size: 13px;
            background: none;
            border: none;
            text-decoration: none;
            color: #374151;
            cursor: pointer;
        }

        .dropdown-menu a:hover,
        .dropdown-menu button:hover {
            background: #f3f4f6;
        }

        /* DELETE COLOR */
        .dropdown-menu button {
            color: #b91c1c;
        }
    </style>

    <div class="page-wrap">


        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div style="margin-bottom:20px; padding:12px 16px; 
                                                            background:#ecfdf5; border:1px solid #10b981; 
                                                            color:#065f46; border-radius:10px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- PARTIAL SUCCESS MESSAGE (Some imported, some failed) --}}
        @if(session('partial_success'))
            <div style="margin-bottom:20px; padding:14px 16px; 
                                                            background:#fef3c7; border:1px solid #fbbf24; 
                                                            color:#78350f; border-radius:10px; line-height:1.6;">
                {!! nl2br(session('partial_success')) !!}
            </div>
        @endif

        {{-- ERROR / VALIDATION MESSAGES --}}
        @if(session('error'))
            <div style="margin-bottom:20px; padding:14px 16px; 
                                                            background:#fee2e2; border:1px solid #f87171; 
                                                            color:#991b1b; border-radius:10px; line-height:1.6; 
                                                            max-height:400px; overflow-y:auto;">
                {!! nl2br(session('error')) !!}
            </div>
        @endif

        @if($errors->any())
            <div style="margin-bottom:20px; padding:12px 16px; 
                                                            background:#fee2e2; border:1px solid #f87171; 
                                                            color:#991b1b; border-radius:10px;">
                <ul style="margin:0;padding-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- HEADER --}}
        <div
            style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;flex-wrap:wrap;gap:10px;">

            <h2 style="font-size:22px; font-weight:600; margin:0;
                                background:linear-gradient(135deg,#6366f1,#ec4899);
                                -webkit-background-clip:text;
                                -webkit-text-fill-color:transparent;">
                <i class="fa-solid fa-users"></i>
                All Mobilizations
            </h2>

            <div style="display:flex; gap:4px;">

                {{-- Excel Import Button --}}
                <button onclick="document.getElementById('excelModal').style.display='flex'" style="display:flex;         align-items:center; gap:8px;
                                        padding:10px 18px;
                                        background:linear-gradient(135deg,#16a34a,#22c55e);
                                        color:#fff;
                                        border-radius:12px;
                                        font-weight:600;
                                        font-size:12px;
                                        border:none;
                                        cursor:pointer;
                                        box-shadow:0 8px 20px rgba(34,197,94,0.35);">

                    <i class="fa-solid fa-file-excel"></i>
                    Import
                </button>

                {{-- Add Mobilization --}}
                <a href="{{ route('mobilizations.create') }}" style="display:flex; align-items:center; gap:8px;
                                        padding:10px 18px;
                                        background:linear-gradient(135deg,#6366f1,#ec4899);
                                        color:#fff;
                                        border-radius:12px;
                                        font-size:12px;
                                        font-weight:600;
                                        text-decoration:none;
                                        box-shadow:0 8px 20px rgba(99,102,241,0.35);">

                    <i class="fa-solid fa-circle-plus"></i>
                    Add
                </a>

            </div>
        </div>
        {{-- ASSIGN CANDIDATES --}}


        <!-- <div style="display:flex; gap:12px; align-items:center; margin-bottom:15px; flex-wrap:wrap;">

                        </div> -->


        <form id="filterForm" class="mobi_filter" method="GET" action="{{ route('mobilizations.index') }}"
            style="display:flex; flex-direction:column; margin-bottom:8px; background:#fff; padding:12px; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,.05);">

            <div style="display: flex; gap: 6px; align-items: flex-end; flex-wrap: wrap;">

                {{-- NAME --}}
                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">Name</label>
                    <input type="text" name="name" value="{{ request('name') }}" placeholder="Name"
                        style="padding: 6px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>

                {{-- MOBILE --}}
                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">Mobile</label>
                    <input type="text" name="mobile" value="{{ request('mobile') }}" placeholder="Mobile"
                        style="padding: 8px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>

                {{-- AADHAR --}}
                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">Aadhar</label>
                    <input type="text" name="aadhar" value="{{ request('aadhar') }}" placeholder="Aadhar"
                        style="padding: 8px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>

                {{-- DATE --}}
                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}"
                        style="padding: 7px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>

                {{-- ASSIGNMENT --}}
                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">Assignment</label>
                    <select name="assignment"
                        style="padding: 8px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                        <option value="">All Assignments</option>
                        @foreach(\App\Models\Assignment::orderBy('assignment_name')->get() as $a)
                            <option value="{{ $a->id }}" {{ request('assignment') == $a->id ? 'selected' : '' }}>
                                {{ $a->assignment_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- STATE --}}
                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">State</label>
                    <select name="state" id="filterState"
                        style="padding: 8px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                        <option value="">Select State</option>
                    </select>
                </div>

                {{-- DISTRICT --}}
                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">District</label>
                    <select name="district" id="filterDistrict"
                        style="padding: 8px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                        <option value="">Select District</option>
                    </select>
                </div>

                {{-- LOCATION --}}
                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">Location</label>
                    <input type="text" name="location" value="{{ request('location') }}" placeholder="Location"
                        style="padding: 8px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>

                {{-- AGE --}}
                <div class="field" style="flex: 1 1 80px;">
                    <label style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">Age</label>
                    <input type="number" name="age" value="{{ request('age') }}" placeholder="Age"
                        style="padding: 8px; font-size: 13px; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>



            </div>
            <div style="display: flex; gap: 8px; align-items: center; padding-bottom: 2px;">
                <button type="submit" class="btn btn-primary" style="padding: 8px 20px; height: 38px;">
                    <i class="fa fa-filter"></i> Apply
                </button>

                <a href="{{ route('mobilizations.index') }}" class="btn btn-secondary"
                    style="padding: 8px 16px; background: #9ca3af; height: 38px; text-decoration: none; display: flex; align-items: center; justify-content: center; color: white; border-radius: 8px; font-weight: 600; font-size: 16px;">
                    <i class="fa-solid fa-rotate"></i> Reset
                </a>
            </div>

        </form>
        {{-- TABLE --}}
        <div class="mobi_table"
            style="width:100%;background:#fff;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,0.08);padding:10px;overflow:visible;">

            <table style="width:100%;border-collapse:collapse;background:#fff;table-layout:auto;">

                <thead>
                    <tr style="background:linear-gradient(135deg,#eef2ff,#fdf2f8); border-bottom:2px solid #e5e7eb;">
                        <!-- <th style="padding:10px;">
                                                <input type="checkbox" id="checkAll">
                                            </th> -->
                        <th style="padding:6px;">#</th>
                        <th style="padding:6px;">Name</th>
                        <th style="padding:6px;">Profile</th>
                        <th style="padding:6px; text-align: start;">Form Status</th>
                        <!-- <th style="padding:10px;">Form Data</th> -->
                        <th style="padding:6px;">Samarth</th>
                        <th style="padding:6px;">Location</th>

                        <th style="padding:6px;">Ass</th>
                        <th style="padding:6px;">Status</th>
                        <th style="padding:6px;">Added On</th>
                        <th style="padding:6px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($mobilizations as $m)
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <!-- <td style="padding:10px;">
                                                                            <input type="checkbox" form="assignForm" name="mobilization_ids[]" value="{{ $m->id }}">
                                                                        </td> -->

                            <td style="padding:6px;">
                                {{ $mobilizations->firstItem() + $loop->index }}
                            </td>

                            <td class="name-cell" style="padding: 6px;">

                                <div>
                                    <div style="display:flex; font-size: 14px; align-items:center; gap:8px; font-weight: 300;">
                                        <span class="candidate-name">{{ $m->name }}</span>

                                        @if($m->created_at && $m->created_at->diffInDays(now()) <= 7) <span class="new-badge">
                                            NEW</span>
                                        @endif
                                    </div>

                                    @if($m->identification_remark)
                                        <div style="font-size:10px; color:#6b7280; ">
                                            {{ $m->identification_remark }}
                                        </div>
                                    @endif
                                </div>

                            </td>



                            <td style="padding:6px; min-width:140px;">

                                @php
                                    $total = $m->total_fields_count;
                                    $filled = $m->filled_fields_count;
                                    $percent = $total ? round(($filled / $total) * 100) : 0;
                                @endphp


                                <div style="display:flex; gap:12px;">

                                    <div style="font-size:14px; font-weight:300;">
                                        {{ $filled }} / {{ $total }}
                                    </div>

                                    <!-- <div style="height:6px; background:#e5e7eb; border-radius:6px; margin-top:6px;">
                                            <div style="
                                                                width:{{ $percent }}%;
                                                                height:100%;
                                                                background:linear-gradient(135deg,#6366f1,#ec4899);
                                                                border-radius:6px;">
                                            </div>
                                        </div> -->

                                    <div style="font-size:11px; color:#9ca3af; margin-top:4px;">
                                        {{ $percent }}%
                                    </div>
                                </div>

                            </td>


                            <!-- <td style="padding:10px;">

                                                                            @if($m->form_responses_count > 0)

                                                                            <div style="color:#16a34a; font-weight:600;">
                                                                                ✅ 
                                                                            </div>

                                                                            @if($m->latestFormResponse && $m->latestFormResponse->form)
                                                                            <div style="font-size:12px; color:#6b7280; margin-bottom: 6px;">
                                                                                {{ $m->latestFormResponse->form->title }}
                                                                            </div>

                                                                            {{-- Download Files Button --}}
                                                                            <button onclick="downloadFormFiles({{ $m->id }})" 
                                                                                    style="padding: 6px 12px; background: linear-gradient(135deg, #6366f1, #ec4899); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                                                                <i class="fa-solid fa-download"></i>
                                                                              Files
                                                                            </button>
                                                                            @endif

                                                                            @else

                                                                            <div style="color:#dc2626; font-weight:600;">
                                                                                ❌ 
                                                                            </div>

                                                                            @endif

                                                                        </td> -->


                            <td style="padding:6px;">
                                <div style="display:flex; align-items:center; gap:6px;">
                                    @if($m->form_responses_count > 0 || ($m->latestFormResponse && $m->latestFormResponse->form))
                                        <span style="color:#16a34a; font-weight:600;">✅</span>
                                        @if($m->latestFormResponse && $m->latestFormResponse->form)
                                            <div style="font-size:14px; color:#6b7280; max-width: 80px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                                title="{{ $m->latestFormResponse->form->title }}">
                                                {{ $m->latestFormResponse->form->title }}
                                            </div>
                                            <button onclick="downloadFormFiles({{ $m->id }})"
                                                style="padding: 4px 6px; background: linear-gradient(135deg, #6366f1, #ec4899); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 10px;">
                                                <i class="fa-solid fa-download"></i>
                                            </button>
                                        @endif
                                    @else
                                        <div style="color:#dc2626; font-weight:600;">❌</div>
                                    @endif
                                </div>
                            </td>


                            <td style="padding:6px;">
                                @php
                                    $samarthDone = false;
                                    $samarthDate = null;

                                    foreach ($m->assignments as $assignment) {
                                        if ($assignment->pivot && $assignment->pivot->samarth_done == 1) {
                                            $samarthDone = true;
                                            $samarthDate = $assignment->pivot->date_of_placement;
                                            break;
                                        }
                                    }
                                @endphp

                                @if($samarthDone)
                                    <div style="color: #16a34a; font-weight: 600;">
                                        ✅
                                    </div>
                                    @if($samarthDate)
                                        <div style="font-size: 11px; color: #6b7280;">
                                            {{ \Carbon\Carbon::parse($samarthDate)->format('d M Y') }}
                                        </div>
                                    @endif
                                @else
                                    <div style="color: #f59e0b; font-weight: 600;">
                                        ⏳
                                    </div>
                                @endif
                            </td>


                            <td style="padding:6px; max-width:120px;">
                                <div style="font-size:14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                    title="{{ implode(' • ', array_filter([$m->state, $m->city, $m->location])) }}">
                                    {{ implode(' • ', array_filter([$m->state, $m->city, $m->location])) ?: '—' }}
                                </div>
                            </td>

                            <td style="padding:6px; text-align:center;">

                                @if($m->assignments_count > 0)

                                    <span onclick="showAssignments({{ $m->id }})" style="
                                                                                                        background:#eef2ff;
                                                                                                        color:#4f46e5;
                                                                                                        padding:6px 10px;
                                                                                                        border-radius:8px;
                                                                                                        font-weight:600;
                                                                                                        cursor:pointer;
                                                                                                        display:inline-block;
                                                                                                      ">
                                        {{ $m->assignments_count }}
                                    </span>


                                    <div id="assignments-{{ $m->id }}" style="display:none;">
                                        @foreach($m->assignments as $a)
                                            {{ $a->assignment_name }}<br>
                                        @endforeach
                                    </div>

                                @else
                                    <span style="color:#9ca3af;">0</span>
                                @endif

                            </td>


                            <td style="padding:6px">

                                @if($m->latestRemark)

                                    <span>
                                        {{ $m->latestRemark->status }}
                                    </span>

                                @else

                                    <span style="color:#9ca3af;">—</span>

                                @endif

                            </td>

                            <td style="padding:6px; font-size: 14px;">
                                {{ $m->created_at->format('d M Y') }}
                                <br>

                            </td>


                            <td style="padding:6px;white-space:nowrap;position:relative;">

                                <div class="action-dropdown">

                                    <button type="button" class="dots-btn" onclick="toggleMenu(this)">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>

                                    <div class="dropdown-menu">

                                        <button onclick="openFormSelection({{ $m->id }})" style="cursor:pointer;">
                                            <i class="fa-solid fa-paper-plane"></i> Send Form
                                        </button>

                                        <a href="{{ route('mobilizations.remarks', $m->id) }}">
                                            <i class="fa-solid fa-comment"></i> Remark
                                        </a>

                                        <a href="{{ route('mobilizations.show', $m->id) }}">
                                            <i class="fa-solid fa-eye"></i> View
                                        </a>

                                        @if(
                                                $m->form_responses_count > 0 || ($m->latestFormResponse &&
                                                    $m->latestFormResponse->form)
                                            )
                                            <button onclick="viewFormData({{ $m->id }})" style="cursor:pointer;">
                                                <i class="fa-solid fa-table"></i> View Form Data
                                            </button>
                                        @endif

                                        <a href="{{ route('mobilizations.edit', $m->id) }}">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>

                                        <form action="{{ route('mobilizations.destroy', $m->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this record?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding:16px; text-align:center; color:#9ca3af;">
                                No mobilization records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div style="margin-top:25px; display:flex; justify-content:center;">
            {{ $mobilizations->links() }}
        </div>
    </div>

    </div>




    {{-- ================= EXCEL IMPORT MODAL ================= --}}


    <div id="excelModal" class="modal">

        <div class="modal-box">

            <h3 style="font-size:20px; font-weight:700; margin-bottom:12px;">
                📥 Import Mobilization Excel
            </h3>

            <p style="font-size:13px; color:#6b7280; margin-bottom:12px;">
                Upload Excel file (.xlsx, .xls, .csv)
            </p>

            <!-- ================= DOWNLOAD SAMPLE FILES ================= -->
            <div style="background:#f9fafb; padding:12px; border-radius:10px; margin-bottom:15px;">
                <p style="font-size:13px; font-weight:600; margin-bottom:8px; color:#111827;">
                    📄 Download Sample Format:
                </p>

                <div style="display:flex; flex-direction:column; gap:6px;">

                    <a href="{{ asset('samples/basic_candidate_data.xlsx') }}" download
                        style="color:#2563eb; font-size:13px; text-decoration:none;">
                        ⬇ Download Basic Sample (Excel)
                    </a>

                    <a href="{{ asset('samples/candidate_profile_data1.xlsx') }}" download
                        style="color:#2563eb; font-size:13px; text-decoration:none;">
                        ⬇ Download Advanced Sample (CSV)
                    </a>

                    <a href="{{ asset('samples/candidate_profile_data.csv') }}" download
                        style="color:#2563eb; font-size:13px; text-decoration:none;">
                        ⬇ Download CSV Format
                    </a>

                </div>
            </div>
            <!-- =========================================================== -->

            @if ($errors->any())
                <div
                    style="margin-bottom:15px; color:#991b1b; background:#fee2e2; padding:12px; border-radius:8px; line-height:1.6; max-height:300px; overflow-y:auto;">
                    <strong>Validation Errors:</strong>
                    <ul style="margin:8px 0 0 0; padding-left:20px;">
                        @foreach ($errors->all() as $error)
                            <li style="font-size:13px;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div
                    style="margin-bottom:15px; color:#991b1b; background:#fee2e2; padding:12px; border-radius:8px; line-height:1.6; max-height:300px; overflow-y:auto;">
                    {!! nl2br(session('error')) !!}
                </div>
            @endif

            <form action="{{ route('mobilizations.import') }}" method="POST" enctype="multipart/form-data">


                @csrf


                <div style="margin-bottom:14px;">
                    <label style="font-size:13px;font-weight:600;color:#374151;">
                        Identification Remark (Applied to all imported students)
                    </label>

                    <input type="text" name="identification_remark" maxlength="255"
                        placeholder="Example: Retail Drive - Bhopal Batch"
                        style="width:100%;padding:10px;border:1px solid #e5e7eb;border-radius:8px;margin-top:5px;">
                </div>
                <div style="margin-bottom:14px;">
                    <input type="file" id="excelFile" name="file" accept=".xlsx,.xls,.csv" required>
                    <div id="fileError" style="color:red; font-size:12px; margin-top:5px; display:none;">
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:10px;">

                    <button type="button" onclick="closeModal()" style="padding:8px 16px; border-radius:8px; border:none;
                                                       background:#e5e7eb; cursor:pointer; font-weight:600;">
                        Cancel
                    </button>

                    <button id="importBtn" type="submit"
                        style="margin-top:10px;padding:8px 16px;background:#6366f1;color:#fff;border:none;border-radius:6px;">
                        Import File
                    </button>

                </div>

            </form>
        </div>
    </div>

    <div id="assignmentModal" class="modal">
        <div class="modal-box">

            <h3>Assigned To</h3>

            <div id="assignmentList" style="max-height:260px; overflow:auto; padding:10px 0; line-height:1.7;">
            </div>

            <div class="modal-actions">
                <button onclick="closeAssignmentModal()" class="btn btn-primary">
                    Close
                </button>
            </div>

        </div>
    </div>


    <div id="remarkModal" class="modal">

        <div class="modal-box">

            <h3>Add Remark</h3>

            <form id="remarkForm" method="POST">
                @csrf

                <textarea name="remark" style="width:100%;padding:10px;border:1px solid #e5e7eb;border-radius:8px;"
                    placeholder="Enter remark"></textarea>

                <button type="submit" class="btn btn-primary" style="margin-top:10px;">
                    Save Remark
                </button>

            </form>

            <hr style="margin:20px 0">

            <h4>Remark History</h4>

            <div id="remarkHistory" style="max-height:200px;overflow:auto;font-size:13px;color:#374151;">
            </div>

            <button onclick="closeRemarkModal()" class="btn btn-cancel" style="margin-top:10px;">
                Close
            </button>

        </div>

    </div>




    <script>
        function closeModal() {
            document.getElementById('excelModal').style.display = 'none';
            document.getElementById('excelFile').value = ''; // Clear file input
            document.getElementById('fileError').style.display = 'none';
        }

        document.getElementById('excelFile').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const fileError = document.getElementById('fileError');
            const importBtn = document.getElementById('importBtn');

            if (file) {
                const validTypes = ['application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'
                ];
                const validExtensions = ['.xlsx', '.xls', '.csv'];
                const fileExtension = '.' + file.name.split('.').pop().toLowerCase();

                if (!validExtensions.includes(fileExtension)) {
                    fileError.textContent = 'Please select a valid Excel file (.xlsx, .xls, .csv)';
                    fileError.style.display = 'block';
                    importBtn.disabled = true;
                    this.value = ''; // Clear the file
                } else {
                    fileError.style.display = 'none';
                    importBtn.disabled = false;
                }
            }
        });


        window.onclick = function (event) {
            const modal = document.getElementById('excelModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        const checkAll = document.getElementById('checkAll');
        if (checkAll) {
            checkAll.addEventListener('change', function () {
                const checked = this.checked;
                document.querySelectorAll('input[name="mobilization_ids[]"]').forEach(ch => {
                    ch.checked = checked;
                });
            });
        }
    </script>


    <script>


        document.getElementById('filterForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const params = new URLSearchParams();

            formData.forEach((value, key) => {
                if (value) {
                    params.append(key, value);
                }
            });

            window.location.href = form.action + '?' + params.toString();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const stateSelect = document.getElementById("filterState");
            const districtSelect = document.getElementById("filterDistrict");

            const selectedState = "{{ request('state') }}";
            const selectedDistrict = "{{ request('district') }}";

            // load states
            fetch("/states")
                .then(res => res.json())
                .then(states => {
                    stateSelect.innerHTML = '<option value="">State</option>';

                    states.forEach(state => {
                        const selected = state.iso2 === selectedState ? 'selected' : '';
                        stateSelect.innerHTML +=
                            `<option value="${state.iso2}" ${selected}>${state.name}</option>`;
                    });

                    if (selectedState) {
                        loadDistricts(selectedState, selectedDistrict);
                    }
                });

            // load districts when state changes
            stateSelect.addEventListener("change", function () {
                loadDistricts(this.value, null);
            });

            function loadDistricts(stateCode, selectedDistrict = null) {
                districtSelect.innerHTML = '<option>Loading...</option>';

                if (!stateCode) {
                    districtSelect.innerHTML = '<option value="">District</option>';
                    return;
                }

                fetch(`/districts/${stateCode}`)
                    .then(res => res.json())
                    .then(cities => {
                        districtSelect.innerHTML = '<option value="">District</option>';

                        cities.forEach(city => {
                            const selected = city.name === selectedDistrict ? 'selected' : '';
                            districtSelect.innerHTML +=
                                `<option value="${city.name}" ${selected}>${city.name}</option>`;
                        });
                    });
            }

        });



        window.addEventListener('pageshow', function (e) {
            if (performance.navigation.type === 1) {
                window.location.href = "{{ route('mobilizations.index') }}";
            }
        });

        function showAssignments(id) {
            const html = document.getElementById('assignments-' + id).innerHTML;
            document.getElementById('assignmentList').innerHTML = html;
            document.getElementById('assignmentModal').style.display = 'flex';
        }

        function closeAssignmentModal() {
            document.getElementById('assignmentModal').style.display = 'none';
        }



        function openRemarkModal(id) {
            document.getElementById('remarkModal').style.display = 'flex';

            document.getElementById('remarkForm').action =
                '/mobilizations/' + id + '/remark';

            fetch('/mobilizations/' + id + '/remarks')
                .then(res => res.json())
                .then(data => {

                    let html = '';

                    if (data.length === 0) {
                        html = 'No remarks yet';
                    }

                    data.forEach(r => {

                        html += `
                                        <div style="border-bottom:1px solid #eee;padding:6px 0;">
                                            <div>${r.remark}</div>
                                            <small style="color:#9ca3af;">
                                                ${new Date(r.created_at).toLocaleString()}
                                            </small>
                                        </div>
                                        `;

                    });

                    document.getElementById('remarkHistory').innerHTML = html;

                });
        }

        function closeRemarkModal() {
            document.getElementById('remarkModal').style.display = 'none';
        }
    </script>

    <script>
        function toggleMenu(btn) {

            let dropdown = btn.closest('.action-dropdown');
            let menu = dropdown.querySelector('.dropdown-menu');

            document.querySelectorAll('.dropdown-menu').forEach(m => {
                if (m !== menu) {
                    m.style.display = 'none';
                }
            });

            menu.style.display = menu.style.display === "block" ? "none" : "block";
        }

        document.addEventListener('click', function (e) {

            if (!e.target.closest('.action-dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
            }

        });

        document.addEventListener('click', function (e) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (!menu.parentElement.contains(e.target)) {
                    menu.style.display = 'none';
                }
            });
        });

        // Download form files function
        function downloadFormFiles(mobilizationId) {
            // Show loading modal
            showLoadingModal();

            // Fetch form response with files
            fetch(`/mobilizations/${mobilizationId}/form-files`)
                .then(response => response.json())
                .then(data => {
                    closeLoadingModal();
                    if (data.success && data.files.length > 0) {
                        showFilesModal(data.files, data.mobilization_name);
                    } else if (data.success) {
                        alert('No files found for this form submission');
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    closeLoadingModal();
                    console.error('Error:', error);
                    alert('Failed to load files');
                });
        }

        function showFilesModal(files, mobilizationName) {
            const existingModal = document.getElementById('filesModal');
            if (existingModal) existingModal.remove();

            const modalHtml = `
                                <div id="filesModal" class="modal" style="display:flex;">
                                    <div class="modal-box" style="max-width: 650px;">
                                        <h3 style="margin-bottom: 10px;">📁 Download Files - ${escapeHtml(mobilizationName)}</h3>
                                        <p style="margin-bottom: 20px; color: #6b7280;">
                                            Select files to download or download all
                                        </p>

                                        <div style="max-height: 400px; overflow-y: auto; margin-bottom: 20px;">
                                            ${files.map((file, index) => `
                                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 10px; background: white;">
                                                    <div style="flex: 1;">
                                                        <div style="font-weight: 600; font-size: 14px; margin-bottom: 4px;">${escapeHtml(file.field_label)}</div>
                                                        <div style="font-size: 12px; color: #6b7280;">
                                                            <i class="fa-solid fa-file"></i> ${escapeHtml(file.file_name)} 
                                                            ${file.file_size ? `(${(file.file_size / 1024).toFixed(2)} KB)` : ''}
                                                        </div>
                                                    </div>
                                                    <div style="display: flex; gap: 8px;">
                                                        <button onclick="downloadSingleFile(${JSON.stringify(file.file_url)}, ${JSON.stringify(file.file_name)})" 
                                                                style="padding: 6px 12px; background: #6366f1; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                                            <i class="fa-solid fa-download"></i> Download
                                                        </button>
                                                    </div>
                                                </div>
                                            `).join('')}
                                        </div>

                                        <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                            <button onclick="closeFilesModal()" class="btn btn-cancel" style="padding: 8px 16px;">
                                                Close
                                            </button>
                                            <button onclick='downloadAllFiles(...${JSON.stringify(files.map(f => f.file_url))})' 
                                                    style="padding: 8px 20px; background: linear-gradient(135deg, #16a34a, #22c55e); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                                                <i class="fa-solid fa-file-zipper"></i> Download All as ZIP
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }

        function closeFilesModal() {
            const modal = document.getElementById('filesModal');
            if (modal) modal.remove();
        }

        function encodeStoragePath(path) {
            return path.split('/').map(encodeURIComponent).join('/');
        }

        function downloadSingleFile(fileUrl, fileName) {
            const link = document.createElement('a');
            link.href = `/document/${encodeStoragePath(fileUrl)}`;
            link.download = fileName;
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function downloadAllFiles(...fileUrls) {
            fileUrls.forEach((url, index) => {
                setTimeout(() => {
                    const link = document.createElement('a');
                    link.href = `/document/${encodeStoragePath(url)}`;
                    link.target = '_blank';
                    link.download = '';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }, index * 300);
            });
        }
    </script>

    <script>
        // Form modal functions
        function openFormWithData(mobilizationId) {
            // Fetch mobilization data
            fetch(`/mobilizations/${mobilizationId}/form-data`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showFormModal(data.mobilization, data.forms);
                    } else {
                        alert('Error loading form data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load form data');
                });
        }

        function showFormModal(mobilization, forms) {
            // Create modal HTML
            const modalHtml = `
                                <div id="formSelectionModal" class="modal" style="display:flex;">
                                    <div class="modal-box" style="max-width: 600px;">
                                        <h3>Select Form for ${mobilization.name}</h3>
                                        <p style="margin-bottom: 20px; color: #6b7280;">
                                            Choose a form to fill with prefilled data
                                        </p>

                                        <div id="formsList" style="margin-bottom: 20px;">
                                            ${forms.map(form => `
                                                <div style="margin-bottom: 10px;">
                                                    <button onclick="openPrefilledForm(${form.id}, ${mobilization.id})" 
                                                            style="width: 100%; text-align: left; padding: 12px; 
                                                                   border: 1px solid #e5e7eb; border-radius: 8px;
                                                                   background: white; cursor: pointer;">
                                                        <strong>${form.title}</strong>
                                                        ${form.description ? `<br><small style="color: #6b7280;">${form.description}</small>` : ''}
                                                    </button>
                                                </div>
                                            `).join('')}
                                        </div>

                                        <div class="modal-actions">
                                            <button onclick="closeFormModal()" class="btn btn-cancel">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;

            // Remove existing modal if any
            const existingModal = document.getElementById('formSelectionModal');
            if (existingModal) {
                existingModal.remove();
            }

            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }

        function closeFormModal() {
            const modal = document.getElementById('formSelectionModal');
            if (modal) {
                modal.remove();
            }
        }

        function openPrefilledForm(formId, mobilizationId) {
            // Close the selection modal
            closeFormModal();
            showLoadingModal();

            fetch(`/mobilizations/${mobilizationId}/generate-form-link`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    form_id: formId
                })
            })
                .then(async response => {
                    const text = await response.text();
                    try {
                        return JSON.parse(text);
                    } catch (err) {
                        console.error('Expected JSON response, got:', text);
                        throw err;
                    }
                })
                .then(data => {
                    closeLoadingModal();
                    if (data.success) {
                        window.open(data.link, '_blank');
                    } else {
                        alert('Error opening form: ' + data.message);
                    }
                })
                .catch(error => {
                    closeLoadingModal();
                    console.error('Error:', error);
                    alert('Failed to open the student form. Please try again.');
                });
        }
    </script>

    <script>
        function openFormSelection(mobilizationId) {
            // Store current mobilization ID for contact fetching
            currentMobilizationId = mobilizationId;

            // Show loading state
            showLoadingModal();

            // Fetch available forms
            fetch(`/mobilizations/${mobilizationId}/available-forms`)
                .then(response => response.json())
                .then(data => {
                    closeLoadingModal();
                    if (data.success) {
                        showFormSelectionModal(mobilizationId, data);
                    } else {
                        alert('Error loading forms: ' + data.message);
                    }
                })
                .catch(error => {
                    closeLoadingModal();
                    console.error('Error:', error);
                    alert('Failed to load forms');
                });
        }

        function showLoadingModal() {
            const modalHtml = `
                                <div id="loadingModal" class="modal" style="display:flex;">
                                    <div class="modal-box" style="text-align: center;">
                                        <div style="margin: 20px;">
                                            <div class="spinner"></div>
                                            <p style="margin-top: 15px;">Loading forms...</p>
                                        </div>
                                    </div>
                                </div>
                            `;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }

        function closeLoadingModal() {
            const modal = document.getElementById('loadingModal');
            if (modal) modal.remove();
        }

        function showFormSelectionModal(mobilizationId, data) {
            // Remove existing modal if any
            const existingModal = document.getElementById('formSelectionModal');
            if (existingModal) existingModal.remove();

            // Handle case when student has no assignments
            if (!data.has_assignments) {
                const modalHtml = `
                                    <div id="formSelectionModal" class="modal" style="display:flex;">
                                        <div class="modal-box" style="max-width: 500px; text-align: center;">
                                            <div style="margin-bottom: 20px;">
                                                <i class="fa-solid fa-circle-exclamation" style="font-size: 48px; color: #f59e0b;"></i>
                                            </div>
                                            <h3 style="margin-bottom: 10px; color: #374151;">No Assignment Assigned Yet</h3>
                                            <p style="margin-bottom: 20px; color: #6b7280;">
                                                This student is not assigned to any assignment yet.<br>
                                                Please assign the student to an assignment first before sending forms.
                                            </p>
                                            <div class="modal-actions" style="justify-content: center;">
                                                <button onclick="closeFormSelectionModal()" class="btn btn-cancel" style="padding: 10px 24px;">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                `;
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                return;
            }

            // Handle case when student has assignments but no forms linked
            if (!data.has_forms) {
                const modalHtml = `
                                    <div id="formSelectionModal" class="modal" style="display:flex;">
                                        <div class="modal-box" style="max-width: 500px; text-align: center;">
                                            <div style="margin-bottom: 20px;">
                                                <i class="fa-solid fa-file-circle-xmark" style="font-size: 48px; color: #9ca3af;"></i>
                                            </div>
                                            <h3 style="margin-bottom: 10px; color: #374151;">No Forms Available</h3>
                                            <p style="margin-bottom: 20px; color: #6b7280;">
                                                ${data.message || 'No forms are linked to the assignments this student is assigned to.'}
                                            </p>
                                            <div class="modal-actions" style="justify-content: center;">
                                                <button onclick="closeFormSelectionModal()" class="btn btn-cancel" style="padding: 10px 24px;">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                `;
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                return;
            }

            const forms = data.forms;
            const modalHtml = `
                                <div id="formSelectionModal" class="modal" style="display:flex;">
                                    <div class="modal-box" style="max-width: 600px;">
                                        <h3 style="margin-bottom: 10px;">📋 Select Form to Send</h3>
                                        <p style="margin-bottom: 20px; color: #6b7280;">
                                            Choose a form to generate a prefilled link for this candidate<br>
                                            <small style="color: #6366f1;">Showing forms linked to this student's assignments</small>
                                        </p>

                                        <div id="formsList" style="max-height: 400px; overflow-y: auto;">
                                            ${forms.length === 0 ?
                    '<p style="text-align: center; color: #9ca3af; padding: 20px;">No active forms available</p>' :
                    forms.map(form => `
                                                    <div class="form-item" style="margin-bottom: 12px; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">
                                                        <div style="padding: 15px; background: white;">
                                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                                <div style="flex: 1;">
                                                                    <strong style="font-size: 16px;">${escapeHtml(form.title)}</strong>
                                                                    ${form.description ? `<p style="margin-top: 5px; color: #6b7280; font-size: 13px;">${escapeHtml(form.description)}</p>` : ''}
                                                                    ${form.valid_to ? `<small style="color: #f59e0b;">Valid until: ${new Date(form.valid_to).toLocaleDateString()}</small>` : ''}
                                                                </div>
                                                                <button onclick="generateFormLink(${mobilizationId}, ${form.id})" 
                                                                        style="padding: 8px 16px; background: linear-gradient(135deg,#6366f1,#ec4899); 
                                                                               color: white; border: none; border-radius: 6px; cursor: pointer;
                                                                               font-weight: 600; margin-left: 10px;">
                                                                    Generate Link
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                `).join('')
                }
                                        </div>

                                        <div class="modal-actions" style="margin-top: 20px;">
                                            <button onclick="closeFormSelectionModal()" class="btn btn-cancel" style="padding: 8px 16px;">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }

        function closeFormSelectionModal() {
            const modal = document.getElementById('formSelectionModal');
            if (modal) modal.remove();
        }

        // function generateFormLink(mobilizationId, formId) {
        //     // Close the selection modal
        //     closeFormSelectionModal();

        //     // Show loading
        //     showLoadingModal();

        //     // Generate the prefilled link
        //     fetch(`/mobilizations/${mobilizationId}/generate-form-link`, {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        //             },
        //             body: JSON.stringify({
        //                 form_id: formId
        //             })
        //         })
        //         .then(response => response.json())
        //         .then(data => {
        //             closeLoadingModal();
        //             if (data.success) {
        //                 showLinkModal(data.link, data.mobilization_name, data.form_title);
        //             } else {
        //                 alert('Error generating link: ' + data.message);
        //             }
        //         })
        //         .catch(error => {
        //             closeLoadingModal();
        //             console.error('Error:', error);
        //             alert('Failed to generate link');
        //         });
        // }

        function generateFormLink(mobilizationId, formId) {
            closeFormSelectionModal();
            showLoadingModal();

            fetch(`/mobilizations/${mobilizationId}/generate-form-link`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json', // IMPORTANT
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    form_id: formId
                })
            })
                .then(async (response) => {
                    const text = await response.text();

                    try {
                        return JSON.parse(text);
                    } catch (err) {
                        console.error("Raw response (NOT JSON):", text);
                        throw new Error("Server returned HTML instead of JSON");
                    }
                })
                .then(data => {
                    closeLoadingModal();

                    if (data.success) {
                        showLinkModal(data.link, data.mobilization_name, data.form_title);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    closeLoadingModal();
                    console.error('Error:', error);
                    alert('Something went wrong. Check console.');
                });
        }

        function showLinkModal(link, candidateName, formTitle) {
            const modalHtml = `
                                <div id="linkModal" class="modal" style="display:flex;">
                                    <div class="modal-box" style="max-width: 550px;">
                                        <h3 style="margin-bottom: 15px;">🔗 Form Link Generated</h3>
                                        <p style="margin-bottom: 10px; color: #374151;">
                                            <strong>Candidate:</strong> ${escapeHtml(candidateName)}<br>
                                            <strong>Form:</strong> ${escapeHtml(formTitle)}
                                        </p>

                                        <div style="background: #f3f4f6; padding: 12px; border-radius: 8px; margin: 15px 0;">
                                            <label style="font-size: 12px; color: #6b7280; margin-bottom: 5px; display: block;">
                                                Share this link with the candidate:
                                            </label>
                                            <div style="display: flex; gap: 8px;">
                                                <input type="text" id="formLink" value="${link}" readonly 
                                                       style="flex: 1; padding: 8px; border: 1px solid #e5e7eb; border-radius: 6px; 
                                                              background: white; font-size: 12px;">
                                                <button onclick="copyLink()" style="padding: 8px 12px; background: #6366f1; 
                                                        color: white; border: none; border-radius: 6px; cursor: pointer;">
                                                    Copy
                                                </button>
                                            </div>
                                        </div>

                                        <div style="margin-bottom: 15px;">
                                            <label style="font-size: 13px; font-weight: 600; margin-bottom: 8px; display: block;">
                                                Or send via:
                                            </label>
                                            <div style="display: flex; gap: 10px;">
                                                <button onclick="sendViaWhatsApp('${link}', '${escapeHtml(candidateName)}')" 
                                                        style="flex: 1; padding: 8px; background: #25D366; color: white; 
                                                               border: none; border-radius: 6px; cursor: pointer;">
                                                    📱 WhatsApp
                                                </button>
                                                <button onclick="sendViaEmail('${link}', '${escapeHtml(candidateName)}')" 
                                                        style="flex: 1; padding: 8px; background: #EA4335; color: white; 
                                                               border: none; border-radius: 6px; cursor: pointer;">
                                                    📧 Email
                                                </button>
                                                <button onclick="sendViaSMS('${link}', '${escapeHtml(candidateName)}')" 
                                                        style="flex: 1; padding: 8px; background: #34B7F1; color: white; 
                                                               border: none; border-radius: 6px; cursor: pointer;">
                                                    💬 SMS
                                                </button>
                                            </div>
                                        </div>

                                        <div class="modal-actions" style="margin-top: 20px;">
                                            <button onclick="closeLinkModal()" class="btn btn-cancel">Close</button>
                                        </div>
                                    </div>
                                </div>
                            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }

        function closeLinkModal() {
            const modal = document.getElementById('linkModal');
            if (modal) modal.remove();
        }

        function copyLink() {
            const linkInput = document.getElementById('formLink');
            linkInput.select();
            document.execCommand('copy');
            alert('Link copied to clipboard!');
        }

        function sendViaWhatsApp(link, candidateName) {
            // You'll need to fetch the candidate's WhatsApp number
            fetch(`/mobilizations/get-contact/${getCurrentMobilizationId()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.whatsapp_number) {
                        const message = encodeURIComponent(
                            `Dear ${candidateName},\n\nPlease fill out this form: ${link}\n\nThank you!`);
                        window.open(`https://wa.me/${data.whatsapp_number}?text=${message}`, '_blank');
                    } else {
                        alert('WhatsApp number not available for this candidate');
                    }
                });
        }

        function sendViaEmail(link, candidateName) {
            fetch(`/mobilizations/get-contact/${getCurrentMobilizationId()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.email) {
                        const subject = encodeURIComponent(`Form Submission Request - ${candidateName}`);
                        const body = encodeURIComponent(
                            `Dear ${candidateName},\n\nPlease click the link below to fill out the form:\n\n${link}\n\nThank you!`
                        );
                        window.location.href = `mailto:${data.email}?subject=${subject}&body=${body}`;
                    } else {
                        alert('Email address not available for this candidate');
                    }
                });
        }

        function sendViaSMS(link, candidateName) {
            fetch(`/mobilizations/get-contact/${getCurrentMobilizationId()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.mobile) {
                        const message = encodeURIComponent(`Dear ${candidateName}, please fill this form: ${link}`);
                        window.location.href = `sms:${data.mobile}?body=${message}`;
                    } else {
                        alert('Mobile number not available for this candidate');
                    }
                });
        }

        let currentMobilizationId = null;

        function getCurrentMobilizationId() {
            return currentMobilizationId;
        }

        // View Form Data Modal
        function viewFormData(mobilizationId) {
            fetch(`/mobilizations/${mobilizationId}/form-data`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.formData && data.formData.length > 0) {
                        showFormDataModal(data.formData, data.candidateName, data.formTitle);
                    } else {
                        alert('No form data available');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading form data');
                });
        }

        function showFormDataModal(formData, candidateName, formTitle) {
            if (!formData || formData.length === 0) {
                alert('No form data available');
                return;
            }

            const modalHtml = `
                                <div id="formDataModal" class="modal" style="display:flex;">
                                    <div class="modal-box" style="max-width: 900px; max-height: 85vh; overflow-y: auto;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px;">
                                            <div>
                                                <h3 style="margin: 0; font-size: 18px; color: #111;">📋 ${escapeHtml(formTitle || 'Form Data')}</h3>
                                                <p style="margin: 5px 0 0 0; color: #6b7280; font-size: 13px;">Candidate: <strong>${escapeHtml(candidateName)}</strong></p>
                                            </div>
                                        </div>

                                        <div style="margin-bottom: 20px;">
                                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 15px;">
                                                ${formData.map((item, index) => `
                                                    <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; background: #f9fafb; break-inside: avoid;">
                                                        <div style="font-size: 12px; font-weight: 600; color: #6366f1; text-transform: uppercase; margin-bottom: 6px;">
                                                            ${escapeHtml(item.field_name)}
                                                        </div>
                                                        <div style="word-break: break-word;">
                                                            ${item.file_url ?
                    `<a href="/document/${encodeStoragePath(item.file_url)}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; color: #6366f1; text-decoration: none; font-weight: 500; padding: 8px 12px; background: #e0e7ff; border-radius: 6px;">
                                                                    <i class="fa-solid fa-download"></i> 
                                                                    <span>${item.file_type && item.file_type.includes('image') ? 'View Image' : 'Download File'}</span>
                                                                </a>` :
                    `<p style="margin: 0; color: #374151; font-size: 14px; line-height: 1.5;">${escapeHtml(item.value || '-') || '<em style="color: #9ca3af;">Not provided</em>'}</p>`
                }
                                                        </div>
                                                    </div>
                                                `).join('')}
                                            </div>
                                        </div>

                                        <div class="modal-actions" style="margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 15px; display: flex; gap: 10px; justify-content: flex-end;">
                                            <button onclick="closeFormDataModal()" style="padding: 10px 20px; background: #f3f4f6; color: #374151; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Close</button>
                                        </div>
                                    </div>
                                </div>
                            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }

        function closeFormDataModal() {
            const modal = document.getElementById('formDataModal');
            if (modal) modal.remove();
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function (m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }
    </script>
@endsection