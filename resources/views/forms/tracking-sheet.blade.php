<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Placement Tracking Sheet</title>

<style>
body{
    font-family: Arial, sans-serif;
    background:#fff;
}

.sheet{
    width: 1000px;
    margin: 20px auto;
    border: 2px solid #555;
    padding: 10px;
}

/* HEADER TITLE */
.title{
    text-align:center;
    font-weight:700;
    border:1px solid #555;
    padding:6px;
    font-size:14px;
    margin-bottom:8px;
}

/* INFO BOX */
.info-box{
    width: 420px;
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

/* MAIN TABLE */
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

.table th{
    font-weight:700;
}

.left{
    text-align:left;
}

.small{
    font-size:11px;
}

/* FOOTER SPACE */
.footer{
    height:60px;
}

@media print{
    body{ margin:0; }
    .sheet{ margin:0; width:100%; }
}
</style>
</head>
<body>

<div class="sheet">

    <div class="title">
        Skilling and Employment Programme - Placement Tracking Sheet - 2025-26
    </div>

    <!-- Batch Info -->
    <div class="info-box">
        <table>
            <tr>
                <td>Batch Code: MH-PUNE-PUNE-RET-EBI-265</td>
            </tr>
            <tr>
                <td>Training Date From: 25/10/2025</td>
            </tr>
            <tr>
                <td>Training Date To: 21/11/2025</td>
            </tr>
            <tr>
                <td>Name of the Partner: eBiz Technocrats Pvt. Ltd.</td>
            </tr>
            <tr>
                <td>Location of Training: Virtual Pune</td>
            </tr>
        </table>
    </div>

    <!-- Placement Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Sr. No</th>
                <th>Name of the Student</th>
                <th>Candidate Code as per portal</th>
                <th>Phone Number</th>
                <th>Gender</th>
                <th class="small">Training<br>(completed/Not Completed)</th>
                <th>Date of Placement<br><span class="small">&lt;dd month year&gt;</span></th>
                <th>Placement Company</th>
                <th class="small">Placement Offering<br>(Rs per month)<br>enter only numbers</th>
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
                <td>Completed</td>
                <td>{{ $s->placement_date }}</td>
                <td>Value Format</td>
                <td>{{ $s->salary }}</td>
                <td>{{ $s->employee_code }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer"></div>

</div>

</body>
</html>