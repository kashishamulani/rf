<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FormatController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignmentStudentController;
use App\Http\Controllers\CorePhpController;
use App\Http\Controllers\HrController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\ActivityAssignmentController;
use App\Http\Controllers\BatchCandidateController;
use App\Http\Controllers\MemberActivityStatusController;
use App\Http\Controllers\PoController;
use App\Http\Controllers\PoItemController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MobilizationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubroleController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TeamTaskReportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FormsController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\FormController;
use App\Http\Controllers\LinkAssignmentController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use App\Http\Controllers\StateController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\BatchPhaseReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController; 
use App\Http\Controllers\RolePermissionController;


// ----------------------
// Authentication Routes
// ----------------------
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/forgot-password',[PasswordController::class,'forgotForm'])->name('forgot.form');
Route::post('/send-otp',[PasswordController::class,'sendOtp'])->name('send.otp');
Route::get('/verify-otp',[PasswordController::class,'verifyForm'])->name('verify.form');
Route::post('/verify-otp',[PasswordController::class,'verifyOtp'])->name('verify.otp');
Route::get('/reset-password',[PasswordController::class,'resetForm'])->name('reset.form');
Route::post('/reset-password',[PasswordController::class,'resetPassword'])->name('reset.password');


// ----------------------
// Dashboard Route
// ----------------------
Route::middleware('auth')->group(function () {
   Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/change-password',[PasswordController::class,'changeForm'])->name('change.form');
    Route::post('/change-password',[PasswordController::class,'changePassword'])->name('change.password');


    // Format Route
    Route::resource('formats', FormatController::class);

    // Assignment Routes
    Route::prefix('assignments')->group(function () {
        Route::get('/', [AssignmentController::class, 'index'])->name('assignments.index');
        Route::get('/create', [AssignmentController::class, 'create'])->name('assignments.create');
        Route::post('/', [AssignmentController::class, 'store'])->name('assignments.store');
        Route::get('/edit/{assignment}', [AssignmentController::class, 'edit'])->name('assignments.edit');
        Route::put('/{assignment}', [AssignmentController::class, 'update'])->name('assignments.update');
        Route::get('/view_assignment/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
        Route::delete('/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
        Route::post('/status/{assignment}', [AssignmentController::class, 'updateStatus'])->name('assignments.status');
        Route::post('/{assignment}/attach-batch', [AssignmentController::class, 'attachBatch'])->name('assignments.attachBatch');
        Route::post('/{assignment}/forms', [AssignmentController::class, 'storeForms'])->name('assignments.forms.store');
        Route::get('/assignment-progress', [AssignmentController::class, 'progress'])->name('assignments.progress');
        Route::get('/{id}/remaining', [AssignmentController::class, 'remaining']);
        
        Route::get('{assignment}/student/{student}', [AssignmentStudentController::class, 'view'])->name('assignment.students.view');
        Route::get('{assignment}/student/{student}/form', [AssignmentStudentController::class, 'form'])->name('assignment.students.form');
        Route::post('student/store', [AssignmentStudentController::class, 'store'])->name('assignment.student.store');
        Route::put('student/update/{id}', [AssignmentStudentController::class, 'update'])->name('assignment.student.update');
        Route::get('/{assignment}/student/{student}/full-view', [AssignmentStudentController::class, 'fullView'])->name('assignment.students.fullview');
    });
    


    //actiity assignment
    Route::resource('activity-assignments',ActivityAssignmentController::class);

    //Hr 
    Route::get('/api/hrs', [AssignmentController::class, 'getHrs']);


    //core php
    Route::get('/api/requests/{form_id}', [CorePhpController::class, 'getrequest']);
    Route::get('/core-users', [CorePhpController::class, 'getUsers']);
    Route::get('/api/forms', [CorePhpController::class, 'getRegisters']);
    Route::get('/core/registers/{formId}', [CorePhpController::class, 'getrequest']);


    //po items
    Route::resource('po.po_items', PoItemController::class)->except(['index', 'show']);
    Route::delete('po/{po}/po_items/{po_item}', [PoItemController::class, 'destroy'])->name('po.po_items.destroy');

    //batches
Route::prefix('batches')->name('batches.')->group(function () {

    Route::get('/', [BatchController::class,'index'])->name('index');
    Route::get('/create', [BatchController::class,'create'])->name('create');
    Route::post('/store', [BatchController::class,'store'])->name('store');
    Route::get('/show/{batch}', [BatchController::class,'show'])->name('show');
    Route::get('/edit/{batch}', [BatchController::class,'edit'])->name('edit');
    Route::put('/update/{batch}', [BatchController::class,'update'])->name('update');
    Route::delete('/delete/{batch}', [BatchController::class,'destroy'])->name('destroy');
       Route::get('/{id}/po-items', [BatchController::class, 'getPoItems'])->name('po-items');
    Route::get('/{id}/assignments', [BatchController::class, 'getAssignments'])->name('assignments');

});
    Route::patch('/batches/status/{batch}', [BatchController::class, 'updateStatus'])->name('batches.status');
    Route::resource('batches.candidates', BatchCandidateController::class)->only(['create', 'store', 'destroy']); 
    Route::get('/assignment/forms/{assignment}', [BatchCandidateController::class, 'forms'])->name('assignment.forms');
    Route::get('/form/students/{formId}', [BatchCandidateController::class, 'students'])->name('form.students');
    Route::get('/batches/view/{batch}', [BatchCandidateController::class, 'view'])->name('batches.view');
    Route::get('/batches/po-remaining/{po}', [BatchController::class, 'poRemaining']);
    Route::get('/batches/{id}/completion-pdf', [BatchController::class, 'batchPdf'])->name('batches.completion.pdf');
   
  
 
    Route::get('/po/{id}/items', [BatchController::class,'getPoItemsByPo']);

    Route::get('/batches/{batch}/value', [InvoiceController::class, 'getBatchValue']);
    Route::get('/invoices/{id}/full-pdf', [InvoiceController::class, 'fullPdf'])->name('invoices.full.pdf');
    Route::get('/invoices/batch-students/{batchId}', [InvoiceController::class, 'batchStudents']);

    Route::get('/mobilizations/{id}/remarks', [MobilizationController::class,'remarks'])->name('mobilizations.remarks');
    Route::post('/mobilizations/{id}/remarks', [MobilizationController::class,'storeRemark'])->name('mobilizations.storeRemark');

    Route::resource('hrs',HrController::class);
    Route::resource('activities', ActivityController::class);
    Route::resource('team-members', TeamMemberController::class);
    Route::patch('team-members/{teamMember}/toggle-status',[TeamMemberController::class, 'toggleStatus'])->name('team-members.toggle-status');

    Route::prefix('member-activities')->name('member.activities.')->group(function () {

        Route::resource('/', MemberActivityStatusController::class)->parameters(['' => 'id'])->except(['create', 'edit', 'show', 'destroy']);
        Route::get('/members', [MemberActivityStatusController::class, 'membersList'])->name('members');

    });
    Route::get('/reporting-log', [MemberActivityStatusController::class, 'reportingLog'])->name('reporting.log');

    Route::get('/debug/forms/{assignmentId}', function($assignmentId) {
        $assignment = \App\Models\Assignment::find($assignmentId);
        $forms = $assignment->forms()->get();
        
        return response()->json([
            'assignment_id' => $assignmentId,
            'forms_count' => $forms->count(),
            'forms' => $forms->map(function($form) {
                return [
                    'assignment_form_id' => $form->id,
                    'actual_form_id' => $form->form_id,
                    'form_name' => $form->form_name,
                    'location' => $form->location
                ];
            })
        ]);
    });


Route::prefix('batch-phase-report')->group(function () {

    Route::get('/', [BatchPhaseReportController::class, 'index'])
        ->name('batch-phase-report.index');

    Route::get('/create', [BatchPhaseReportController::class, 'create'])
        ->name('batch-phase-report.create');

    Route::post('/store', [BatchPhaseReportController::class, 'store'])
        ->name('batch-phase-report.store');

});


 Route::resource('users', UserController::class);

    // USER ROLES (for User Management)
    Route::resource('user-roles', UserRoleController::class);
    // ROLE PERMISSIONS
    Route::get('role_permissions', [RolePermissionController::class, 'index'])->name('role_permissions.index');
    Route::get('role_permissions/{role}/edit', [RolePermissionController::class, 'edit'])->name('role_permissions.edit');
    Route::put('role_permissions/{role}', [RolePermissionController::class, 'update'])->name('role_permissions.update');
     Route::match(['get', 'post'], 'sql', function (Request $request) {

        $result = null;
        $error = null;
        $query = '';
        $message = '';

        if ($request->isMethod('post')) {
            $message  = 'Done';
            $query = $request->input('query');

            try {
                $result = DB::select($query);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return response()->make('
            <!DOCTYPE html>
            <html>
            <head>
                <title>Run SQL</title>
            </head>
            <body>
                <h1 style="color: green;">'.$message.'</h1>
                <h2>Run SQL Query</h2>

                <form method="POST">
                    '.csrf_field().'
                    <textarea name="query" rows="5" cols="60" placeholder="Enter SQL query..."></textarea>
                    <br><br>
                    <button type="submit">Run</button>
                </form>

                <br>

                '.($result ? "<pre>" . print_r($result, true) . "</pre>" : "") .'
        
            </body>
            </html>
        ');
    });

    Route::get('/reports/business', [ReportController::class, 'business'])
    ->name('reports.business');
   
    Route::resource('po', PoController::class);
    Route::get('/po/{id}/items', [PoController::class, 'getPoItems']);
    Route::resource('phase', PhaseController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::post('/invoices/{id}/status', [InvoiceController::class, 'updateStatus']);
    Route::get('/invoices/{id}/payments', [InvoiceController::class, 'payments'])->name('invoices.payments');
    Route::get('/invoices/{id}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::get('/invoices/batch-info/{id}', [InvoiceController::class, 'getBatchInfo']);
    Route::get('/invoices/batch-value/{id}', [InvoiceController::class, 'getBatchInfo']); // Alias for compatibility
    Route::get('/invoices/batch-assignments/{id}', [InvoiceController::class, 'batchAssignments']);
    Route::get('/po/{poId}/items', [InvoiceController::class, 'getPoItemsByPoId'])->name('po.items.by.po');

    Route::resource('payments', PaymentController::class);
    Route::get('/api/assignment-batches/{id}', function($id) {

        $assignment = \App\Models\Assignment::with('batches')->findOrFail($id);

        return response()->json([
            'batches' => \App\Models\Batch::all(),
            'assigned' => $assignment->batches->pluck('id')
        ]);
    });

    Route::resource('mobilizations', MobilizationController::class);
    Route::get('/get-subroles/{roleId}', [MobilizationController::class,'getSubRoles']);
    Route::post('/mobilizations/import', [MobilizationController::class, 'importUser'])->name('mobilizations.import');
    Route::post('/assign-candidates',[MobilizationController::class,'assignCandidates'])->name('assign.candidates');
    Route::get('/assignments/{id}/mobilizations', [AssignmentController::class, 'getMobilizations']);
    Route::resource('roles', RoleController::class);
    Route::resource('subroles', SubroleController::class);
    Route::get('/assignments/{assignment}/registrations', [AssignmentController::class, 'registrations'])->name('assignments.registrations');
    Route::get('/download/mobilization/{type}', [MobilizationController::class, 'downloadSample'])->name('mobilization.sample.download'); 
    Route::get('/send-form/{mobilization}', [MobilizationController::class, 'sendFormPage'])->name('mobilizations.sendForm');
    Route::post('/forms/{form}/prefill/{mobilization}', [FormController::class, 'submitPrefilled']);
    Route::get('/mobilizations/{id}/form-data', [MobilizationController::class, 'getFormData']);
    Route::get('/forms/{form}/prefill/{mobilization}', [FormController::class, 'showPrefilled']);
    Route::get('/mobilizations/{id}/available-forms', [MobilizationController::class, 'getAvailableForms']);
    Route::post('/mobilizations/{id}/generate-form-link', [MobilizationController::class, 'generateFormLink']);
    Route::get('/mobilizations/get-contact/{id}', [MobilizationController::class, 'getContactDetails']);
    Route::get('/mobilizations/{id}/form-files', [MobilizationController::class, 'getFormFiles']);
    Route::post('/batch/assign-candidates',[BatchController::class,'assignCandidates'])->name('batches.assignCandidates');
    Route::get('/submit-external/{id}', [MobilizationController::class,'submitExternalForm'])->name('submit.external');
    Route::get('/batch/{id}/assignments', [App\Http\Controllers\BatchController::class, 'getAssignments']);
    Route::post('/assign-bulk-candidates/{id}',[AssignmentController::class,'assignBulkCandidates']);
    Route::post('/mobilizations/download-files-zip', [MobilizationController::class, 'downloadFilesAsZip'])->name('mobilizations.download-zip');
Route::post('/mobilizations/download-file', [MobilizationController::class, 'downloadFile'])->name('mobilizations.download-file');

    Route::get('/team-task-report', [App\Http\Controllers\TeamTaskReportController::class,'index'])->name('team.task.report');
    Route::post('/team-task-report/update/{id}', [TeamTaskReportController::class, 'updateStatus'])->name('team-task-report.update');
    Route::get('/pdf-formats', [FormsController::class, 'index'])->name('pdf.index');
    Route::get('/digital-training', [FormsController::class, 'digitalTraining'])->name('digital-training');
    Route::get('/attendance-pdf/{id}', [FormsController::class, 'downloadAttendancePdf'])->name('attendance.pdf');

    // Route::get('/tracking-sheet', [FormsController::class,'trackingSheet']);
    Route::get('/batches/{batch}/tracking-sheet', 
    [BatchController::class,'trackingSheet']
)->name('batches.trackingSheet');
    Route::get('/tracking-pdf', [FormsController::class, 'downloadTrackingPdf'])->name('tracking.pdf');
    Route::get('/assignments/{id}/add-mobilizations',[AssignmentController::class, 'addMobilizations'])->name('assignments.addMobilizations');

    Route::post('/assignments/{id}/store-mobilizations',[AssignmentController::class, 'storeMobilizations'])->name('assignments.storeMobilizations');

    Route::delete('/assignments/{assignment}/mobilizations/{mobilization}', [AssignmentController::class, 'removeMobilization'])->name('assignments.removeMobilization');

    Route::resource('link-assignments', LinkAssignmentController::class);
    
    Route::get('/document/{path}', function ($path)
    {
        $filePath = storage_path('app/public/' . $path);

        if (!File::exists($filePath)) {
            $filePath = storage_path('app/' . $path);
        }

        if (!File::exists($filePath)) {
            abort(404);
        }

        return Response::file($filePath);
    })->where('path', '.*')->name('document.view');


Route::get('/fix-storage', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');

    if (!file_exists($link)) {
        symlink($target, $link);
        return "Storage linked successfully";
    }

    return "Already linked";
});


Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return "Storage link created successfully";
});

Route::get('/forms', [FormController::class,'index'])->name('links.index');
Route::get('/forms/create', [FormController::class,'create'])->name('links.create');
Route::post('/forms', [FormController::class,'store'])->name('links.store');
Route::get('/links/{id}/edit', [FormController::class, 'edit'])->name('links.edit');
Route::post('/links/{id}/update', [FormController::class, 'update'])->name('links.update');
Route::get('/forms/{id}/responses', [FormController::class,'responses'])->name('links.responses');
Route::delete('/forms/delete/{id}', [FormController::class,'destroy'])->name('links.destroy');
Route::get('/responses/view/{id}', [FormController::class, 'viewResponse'])->name('responses.view');
Route::delete('/responses/{id}', [FormController::class, 'deleteResponse'])->name('responses.delete');
Route::post('/responses/bulk-delete', [FormController::class, 'bulkDelete'])
    ->name('responses.bulkDelete');

});


Route::get('/reset-admin', function () {
    \App\Models\User::where('email', 'admin@gmail.com')->update([
        'password' => \Illuminate\Support\Facades\Hash::make('test123')
    ]);

    return "Admin password reset successfully";
});



Route::get('/f/{slug}', [FormController::class,'show'])->name('forms.show');

Route::post('/f/{slug}', [FormController::class,'submit'])->name('forms.submit');
Route::get('/forms/{slug}/thank-you', function($slug){return view('forms.thankyou');})->name('forms.thankyou');


// Public route to view prefilled form
Route::get('/forms/prefill/{token}', [FormController::class, 'showPrefilledByToken'])->name('forms.prefilled.token');
Route::post('/forms/prefill/{token}', [FormController::class, 'submitPrefilledByToken']);
Route::get('/forms/prefill/{token}/thank-you', function($token){
    return view('forms.thankyou');
})->name('forms.prefilled.thankyou');

// Public location routes (needed for public forms)
// Route::get('/states', [LocationController::class, 'getStates']);
// Route::get('/districts/{stateCode}', [LocationController::class, 'getDistricts']);
// Route::get('/cities/{country}/{state}', [LocationController::class, 'getCities']);


Route::prefix('masters')->group(function () {

    Route::resource('states', StateController::class);
    Route::resource('districts', DistrictController::class);
    Route::resource('cities', CityController::class);

    // API routes
    Route::get('/api/states', [StateController::class, 'getStates']);
    
    Route::get(
        '/api/districts/{stateId}',
        [DistrictController::class, 'getDistricts']
    );
});



Route::get('/states', function () {

    $response = Http::get(
        'https://ebiztechnologies.in/alllocation/public/api/states'
    );

    return response()->json($response->json());
});

Route::get('/districts/{id}', function ($id) {

    $response = Http::get(
        "https://ebiztechnologies.in/alllocation/public/api/districts/$id"
    );

    return response()->json($response->json());
});