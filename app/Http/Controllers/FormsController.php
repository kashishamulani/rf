<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Batch;
use Illuminate\Support\Facades\DB;


class FormsController extends Controller
{



public function index()
{
    $batch = (object)[
        'id' => 1,
        'code' => 'MH-PUNE-RET-EBL-265',
        'location' => 'Pune',
        'start_date' => '25/10/2025',
        'end_date' => '21/11/2025',
    ];

    return view('pdf.index', compact('batch'));
}
  
    public function digitalTraining()
    {
        // dummy batch info
        $batch = (object)[
            'id' => 1,
            'code' => 'MH-PUNE-RET-EBL-265',
            'location' => 'Pune',
            'start_date' => '25/10/2025',
            'end_date' => '21/11/2025',
            'hours' => '4 Hours'
        ];

        // dummy students
        $students = [
            (object)[
                'code' => '83380',
                'name' => 'Ravindutt',
                'phone' => '8295675670',
                'day1' => 'P',
                'day2' => 'P',
            ],
            (object)[
                'code' => '83381',
                'name' => 'Deepak Kumar',
                'phone' => '9306875046',
                'day1' => 'P',
                'day2' => 'A',
            ],
        ];

        return view('forms.digital-training', compact('students','batch'));
    }





// public function downloadAttendancePdf($batchId)
// {
//     $students = [
//         (object)[
//             'code' => '83380',
//             'name' => 'Ravindutt',
//             'phone' => '8295675670',
//             'day1' => 'P',
//             'day2' => 'P',
//         ],
//         (object)[
//             'code' => '83381',
//             'name' => 'Deepak Kumar',
//             'phone' => '9306875046',
//             'day1' => 'P',
//             'day2' => 'A',
//         ],
//     ];

//     $batch = (object)[
//         'code' => 'MH-PUNE-RET-EBL-265',
//         'location' => 'Pune',
//         'start_date' => '25/10/2025',
//         'end_date' => '21/11/2025',
//         'hours' => '4 Hours'
//     ];

//     $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
//         'pdf.attendance-sheet',
//         compact('students','batch')
//     )->setPaper('A4', 'landscape');

//     return $pdf->stream('attendance-sheet.pdf'); // 👈 show in browser
// }

public function downloadAttendancePdf($batchId)
{
    $batch = Batch::findOrFail($batchId);

    $students = DB::table('batch_assignment_students')
        ->join('mobilizations','mobilizations.id','=','batch_assignment_students.student_id')
        ->select(
            'mobilizations.id as code',
            'mobilizations.name',
            'mobilizations.mobile as phone'
        )
        ->where('batch_assignment_students.batch_id',$batchId)
        ->get()
        ->map(function($s){
            $s->day1 = '';
            $s->day2 = '';
            return $s;
        });

    $batchData = (object)[
        'code' => $batch->batch_code,
        'location' => $batch->district,
        'start_date' => date('d/m/Y', strtotime($batch->training_from)),
        'end_date' => date('d/m/Y', strtotime($batch->training_to)),
        'hours' => $batch->training_hours . ' Hours'
    ];

    $pdf = Pdf::loadView(
        'pdf.attendance-sheet',
        [
            'students' => $students,
            'batch' => $batchData
        ]
    )->setPaper('A4','portrait');

    return $pdf->stream('attendance-sheet.pdf');
}

public function downloadTrackingPdf()
{
    $batch = (object)[
        'code' => 'MH-PUNE-PUNE-RET-EBI-265',
        'location' => 'Virtual Pune',
        'start_date' => '25/10/2025',
        'end_date' => '21/11/2025',
    ];

    $students = [
        (object)[
            'name' => 'Tamanna',
            'code' => '83401',
            'phone' => '9991205743',
            'gender' => 'Female',
            'status' => 'Completed',
            'placement_date' => '24-Nov-25',
            'company' => 'Value Format',
            'salary' => '11625',
            'employee_code' => '61080716',
        ],
        (object)[
            'name' => 'Sandeep Kumar',
            'code' => '83403',
            'phone' => '9817128482',
            'gender' => 'Male',
            'status' => 'Completed',
            'placement_date' => '25-Nov-25',
            'company' => 'Value Format',
            'salary' => '11625',
            'employee_code' => '61082853',
        ],
    ];

    $pdf = Pdf::loadView('pdf.tracking-sheet', compact('students','batch'))
              ->setPaper('A4','landscape');

    return $pdf->stream('placement-tracking-sheet.pdf');
}
}