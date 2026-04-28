<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
<title>Batch Completion Report</title>

<style>

.batch-report-wrapper{
    font-family: DejaVu Sans, sans-serif;
    font-size:13px;
    color:#000;
}

.batch-report-wrapper .container{
    width:100%;
    border:1px solid #000;
}

/* HEADER */

.batch-report-wrapper .report-title{
    text-align:center;
    font-size:18px;
    font-weight:bold;
    padding:12px 0;
    border-bottom:1px solid #000;
}

/* HEADER INFO */

.batch-report-wrapper .header-info{
    padding:12px 15px;
    line-height:1.8;
    border-bottom:1px solid #000;
}

/* TABLE */

.batch-report-wrapper .report-table{
    width:100%;
    border-collapse:collapse;
}

.batch-report-wrapper .report-table td{
    padding:10px 12px;
    border-top:1px solid #000;
    vertical-align:middle;
}

/* LEFT COLUMN */

.batch-report-wrapper .report-table td:first-child{
    width:50%;
    border-right:1px solid #000;
    font-weight:600;
}

/* RIGHT COLUMN */

.batch-report-wrapper .report-table td:last-child{
    width:50%;
}

/* SIGNATURE */

.batch-report-wrapper .signature{
    margin-top:40px;
    padding:20px 15px;
    text-align:right;
}

.batch-report-wrapper .signature-line{
    margin-top:40px;
    font-weight:600;
}

</style>

</head>

<body>

<div class="batch-report-wrapper">

<div class="container">

    <div class="report-title">
        Batch Completion Report
    </div>

    <div class="header-info">
        <strong>Training Partner :</strong> {{ $training_partner }} <br>
        <strong>Vendor Code :</strong> {{ $vendor_code }}
    </div>

    <table class="report-table">

        <tr>
            <td>Training Location</td>
            <td>{{ $training_location }}</td>
        </tr>

        <tr>
            <td>Batch Code (as per Portal)</td>
            <td>{{ $batch_code }}</td>
        </tr>

        <tr>
            <td>Format / Business</td>
            <td>{{ $format }}</td>
        </tr>

        <tr>
            <td>Training Start Date</td>
            <td>{{ \Carbon\Carbon::parse($start_date)->format('d-M-Y') }}</td>
        </tr>

        <tr>
            <td>Training End Date</td>
            <td>{{ \Carbon\Carbon::parse($end_date)->format('d-M-Y') }}</td>
        </tr>

        <tr>
            <td>Training completed as per Reliance Content</td>
            <td>{{ $completed }}</td>
        </tr>

        <tr>
            <td>No. of Candidates Trained</td>
            <td>{{ $candidates }}</td>
        </tr>

        <tr>
            <td>No. of Placed Trainees</td>
            <td>{{ $placed }}</td>
        </tr>

        <tr>
            <td>Candidates Profile data Portal</td>
            <td>Yes</td>
        </tr>

        <tr>
            <td>Attendance in Portal</td>
            <td>Yes</td>
        </tr>

        <tr>
            <td>Placement data in Portal</td>
            <td>Yes</td>
        </tr>

    </table>

    <!-- <div class="signature">
        <div class="signature-line">
            Authorized Signatory
        </div>
    </div> -->

</div>

</div>

</body>
</html>