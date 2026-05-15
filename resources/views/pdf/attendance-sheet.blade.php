<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Attendance Sheet</title>

<style>
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 12px;
}

.sheet {
    width: 100%;
    border: 2px solid #000;
}

table {
    width: 100%;
    border-collapse: collapse;
}

.info-table td {
    border: 1px solid #000;
    padding: 6px;
    font-size: 12px;
}

.info-row {
    text-align: center;
    font-weight: bold;
}

.attendance-table th,
.attendance-table td {
    border: 1px solid #000;
    padding: 5px;
    text-align: center;
}

.attendance-table td.left {
    text-align: left;
}

.signature {
    margin-top: 30px;
    text-align: right;
}
</style>
</head>
<body>

<div class="sheet">

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
<td><strong>Batch Code :</strong> {{ $batch->code }}</td>
<td><strong>Location :</strong> {{ $batch->location }}</td>
</tr>

<tr>
<td><strong>Start Date :</strong> {{ $batch->start_date }}</td>
<td><strong>End Date :</strong> {{ $batch->end_date }}</td>
</tr>

<tr>
<td colspan="2">
<strong>Training Hours :</strong> {{ $batch->hours }}
</td>
</tr>
</table>

<br>

<table class="attendance-table">
<thead>
<tr>
<th>Sr</th>
<th>Candidate Code</th>
<th>Name</th>
<th>Contact</th>
<th>Training</th>
<th>Day 1</th>
<th>Day 2</th>
</tr>
</thead>

<tbody>
@foreach($students as $i => $s)
<tr>
<td>{{ $i+1 }}</td>
<!-- <td>{{ $s->code }}</td> -->
<td></td>
<td class="left">{{ strtoupper($s->name) }}</td>
<td>{{ $s->phone }}</td>
<!-- <td>Block A</td> -->
 <td></td>
<td>{{ $s->day1 }}</td>
<td>{{ $s->day2 }}</td>
</tr>
@endforeach
</tbody>
</table>

<div class="signature">
For<br>
<strong>e-Biz Technocrats Pvt. Ltd.</strong><br><br><br>
_________________________<br>
Authorized Signatory
</div>

</div>

</body>
</html>