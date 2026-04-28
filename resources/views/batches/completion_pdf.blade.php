    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <title>Batch Completion Report</title>
        <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .container {
            width: 100%;
            border: 1px solid #000;
            /* Outer Border */
            padding: 0;
        }

        h2 {
            text-align: center;
            padding: 10px 0;
            margin: 0;
        }

        .header-info {
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 8px 10px;
            border-top: 1px solid #000;
            /* Horizontal lines */
        }

        /* Vertical center line */
        td:first-child {
            width: 50%;
            border-right: 1px solid #000;
        }

        .stamp {
            padding: 40px 10px 20px 10px;
            text-align: right;
        }
        </style>
    </head>

    <body>

        <div class="container">

            <h2>Batch Completion Report</h2>

            <div class="header-info">
                <strong>Training Partner :</strong> {{ $training_partner }} <br>
                <strong>Vendor Code :</strong> {{ $vendor_code }}
            </div>

            <table>
                <tr>
                    <td>Training Location</td>
                    <td>{{ $training_location }}</td>
                </tr>
                <tr>
                    <td>Batch Code (as per Portal)</td>
                    <td>{{ $batch_code }}</td>
                </tr>
                <tr>
                    <td>Format/ Business</td>
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
                    <td>Nos. of placed trainees</td>
                    <td>{{ $placed }}</td>
                </tr>
                 <tr>
                    <td></td>
                    <td></td>
                </tr>
               
                <tr>
                    <td style="padding: 8px 10px;">Candidates Profile data Portal</td>
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

        
        </div>

            <div class="stamp">
                <strong>Authorized Signatory</strong>
            </div>

    </body>

    </html>