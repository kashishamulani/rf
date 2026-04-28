<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Placement Tracking Sheet</title>

    <style>
    body {
        font-family: DejaVu Sans, Arial, sans-serif;
        font-size: 12px;
    }

    .sheet {
        width: 100%;
    }

    .title {
        text-align: center;
        font-weight: bold;
        font-size: 14px;
        border: 1px solid #000;
        padding: 6px;
        margin-bottom: 8px;
    }

    .info {
        width: 420px;
        border: 1px solid #000;
        margin-bottom: 10px;
    }

    .info td {
        border: 1px solid #000;
        padding: 4px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        background: #f2f2f2;
    }

    .table th,
    .table td {
        border: 1px solid #000;
        padding: 4px;
        text-align: center;
    }

    .left {
        text-align: left;
    }

    .small {
        font-size: 11px;
    }
    </style>
</head>

<body>

    <div class="sheet">

        <div class="title">
            Skilling and Employment Programme - Placement Tracking Sheet - 2025-26
        </div>

        <table class="info">
            <tr>
                <td>Batch Code: {{ $batch->batch_code }}</td>
            </tr>

            <tr>
                <td>Training Date From: {{ $batch->start_date }}</td>
            </tr>

            <tr>
                <td>Training Date To: {{ $batch->end_date }}</td>
            </tr>

            <tr>
                <td>Name of the Partner: e-Biz Technocrats Pvt. Ltd.</td>
            </tr>

            <tr>
                <td>Location of Training: {{ $batch->location }}</td>
            </tr>
        </table>

        <table class="table">

            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Name of the Student</th>
                    <th>Candidate Code</th>
                    <th>Phone Number</th>
                    <th>Gender</th>
                    <th class="small">Training Status</th>
                    <th>Date of Placement</th>
                    <th>Placement Company</th>
                    <th>Salary (Rs)</th>
                    <th>Employee Code</th>
                </tr>
            </thead>

            <tbody>

                @foreach($students as $i => $s)

                <tr>

                    <td>{{ $i+1 }}</td>

                    <td class="left">
                        {{ strtoupper($s->name ?? '-') }}
                    </td>

                    <td>
                        {{ $s->registration_number ?? '-' }}
                    </td>

                    <td>
                        {{ $s->mobile ?? '-' }}
                    </td>

                    <td>
                        {{ $s->gender ?? '-' }}
                    </td>

                    <td>
                        {{ $s->samarth_done ? 'Completed' : 'Pending' }}
                    </td>

                    <td>
                        {{ $s->date_of_placement ?? '-' }}
                    </td>

                    <td>
                        {{ $s->placement_company ?? '-' }}
                    </td>

                    <td>
                        {{ $s->placement_offering ?? '-' }}
                    </td>

                    <td>
                        {{ $s->ec_number ?? '-' }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</body>

</html>