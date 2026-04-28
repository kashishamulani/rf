<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Placement Tracking Sheet</title>

<style>
body{ font-family: Arial, sans-serif; }

.sheet{
    width:100%;
    border:2px solid #555;
    padding:10px;
}

.title{
    text-align:center;
    font-weight:700;
    border:1px solid #555;
    padding:6px;
    font-size:14px;
    margin-bottom:8px;
}

.info-box{
    width:420px;
    border:1px solid #555;
    margin-bottom:10px;
}

.info-box table{
    width:100%;
    border-collapse: collapse;
}

.info-box td{
    border:1px solid #555;
    padding:4px 6px;
    font-size:12px;
}

.table{
    width:100%;
    border-collapse: collapse;
    font-size:12px;
}

.table th, .table td{
    border:1px solid #555;
    padding:4px;
    text-align:center;
}

.left{ text-align:left; }
.small{ font-size:11px; }
</style>
</head>
<body>

<div class="sheet">

<div class="title">
Skilling and Employment Programme - Placement Tracking Sheet - 2025-26
</div>

<div class="info-box">
<table>
<tr><td>Batch Code: {{ $batch->code }}</td></tr>
<tr><td>Training Date From: {{ $batch->start_date }}</td></tr>
<tr><td>Training Date To: {{ $batch->end_date }}</td></tr>
<tr><td>Name of the Partner: e-Biz Technocrats Pvt. Ltd.</td></tr>
<tr><td>Location of Training: {{ $batch->location }}</td></tr>
</table>
</div>

<table class="table">
<thead>
<tr>
<th>Sr. No</th>
<th>Name of the Student</th>
<th>Candidate Code</th>
<th>Phone Number</th>
<th>Gender</th>
<th class="small">Training Status</th>
<th>Date of Placement</th>
<th>Placement Company</th>
<th class="small">Salary (Rs)</th>
<th>Employee Code</th>
</tr>
</thead>

<tbody>
@foreach($students as $i => $s)
<tr>
<td>{{ $i+1 }}</td>
<td class="left">{{ strtoupper($s->name) }}</td>
<td>{{ $s->code }}</td>
<td>{{ $s->phone }}</td>
<td>{{ $s->gender }}</td>
<td>{{ $s->status }}</td>
<td>{{ $s->placement_date }}</td>
<td>{{ $s->company }}</td>
<td>{{ $s->salary }}</td>
<td>{{ $s->employee_code }}</td>
</tr>
@endforeach
</tbody>
</table>

</div>
</body>
</html>