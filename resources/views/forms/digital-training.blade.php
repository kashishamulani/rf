@extends('layouts.app')

@section('content')

<style>
body {
    font-family: Arial, Helvetica, sans-serif;
    background: #e5e5e5;
}

.page-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    padding: 30px 0;
}

.sheet {
    width: 1100px;
    background: #fff;
    border: 2px solid #000;
    /* ONLY OUTER BORDER */
}

table {
    width: 100%;
    border-collapse: collapse;
}

/* Header + Info Table */
.info-table td {
    border: 1px solid #000;
    padding: 6px 8px;
    font-size: 13px;
}

.info-row {
    text-align: center;
    font-weight: bold;
    font-size: 14px;
}

/* Attendance Table */
.attendance-table th,
.attendance-table td {
    border: 1px solid #000;
    padding: 6px;
    font-size: 12.5px;
    text-align: center;
}

.attendance-table th {
    font-weight: bold;
}

.attendance-table td.left {
    text-align: left;
}

.signature {
    padding: 25px 15px 35px 15px;
    text-align: right;
    font-size: 13px;
}

@media print {
    body {
        background: white;
    }

    .page-wrapper {
        padding: 0;
    }
}
</style>

<div class="page-wrapper">
    <div class="sheet">
        <a href="{{ route('attendance.pdf', $batch->id) }}" class="btn btn-primary">
            Download PDF
        </a>
        <!-- HEADER + DETAILS -->
        <table class="info-table">
            <tr>
                <td colspan="2" class="info-row">
                    Reliance Foundation Skilling and Employment Programme - 2025-26
                </td>
            </tr>
            <tr>
                <td colspan="2" class="info-row">
                    Digital Training Attendance Sheet
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Name of The Training Partner :</strong>
                    e-Biz Technocrats Pvt. Ltd.
                </td>
            </tr>
            <tr>
                <td><strong>Batch Code :</strong> MH-PUNE-RET-EBL-265</td>
                <td><strong>Location of Requirement :</strong> Pune</td>
            </tr>
            <tr>
                <td><strong>Start Date :</strong> 25/10/2025</td>
                <td><strong>End Date :</strong> 21/11/2025</td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Number of Training Hours :</strong> 4 Hours
                </td>
            </tr>
        </table>
        <br>
        <!-- ATTENDANCE TABLE -->
        <table class="attendance-table">
            <thead>
                <tr>
                    <th style="width:50px;">Sr No.</th>
                    <th style="width:120px;">Candidate Code</th>
                    <th>Candidate Name</th>
                    <th style="width:130px;">Contact</th>

                    <th>Training Date</th>
                    <th>Date</th>
                    <th>Date</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>

                    <th>Training Day</th>
                    <th>Day 1</th>
                    <th>Day 2</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->code }}</td>
                    <td class="left">{{ strtoupper($student->name) }}</td>
                    <td>{{ $student->phone }}</td>

                    <td>Block A</td>
                    <td>{{ $student->day1 ?? '' }}</td>
                    <td>{{ $student->day2 ?? '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- SIGNATURE -->
        <div class="signature">
            For<br>
            <strong>e-Biz Technocrats Pvt. Ltd.</strong>
            <br><br><br>
            ___________________________<br>
            Authorized Signatory
        </div>

    </div>
</div>

@endsection