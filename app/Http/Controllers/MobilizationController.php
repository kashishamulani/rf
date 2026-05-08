<?php

namespace App\Http\Controllers;
use ZipArchive;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Mobilization;
use App\Models\Role;
use App\Models\SubRole;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DB;
use App\Models\MobilizationRemark;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FlexibleMobilizationImport;
use App\Models\Assignment;
use Illuminate\Support\Facades\Http;

use App\Models\Form;
use App\Models\FormPrefillToken;
use App\Models\FormResponse;


class MobilizationController extends Controller
{


public function index(Request $request)
{


    $query = Mobilization::query()
        ->with([
            'assignments' => function ($q) {
                $q->withPivot(
                    'samarth_done',
                    'uan_done',
                    'documents_done',
                    'offer_letter_done',
                    'placement_company',
                    'date_of_placement'
                );
            },
            'latestFormResponse.form',
            'latestRemark'
        ])
        ->withCount([
            'formResponses as form_responses_count',
            'assignments as assignments_count'
        ]);

    // ================= FILTERS =================

    // 🔍 Global Search
    if ($request->filled('search')) {
        $search = trim($request->search);

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('mobile', 'like', "%{$search}%")
              ->orWhere('aadhar_number', 'like', "%{$search}%");
        });
    }

    // 👤 Name Filter
    if ($request->filled('name')) {
        $name = trim($request->name);
        $query->where('name', 'like', "%{$name}%");
    }

    // 📱 Mobile Filter
    if ($request->filled('mobile')) {
        $mobile = preg_replace('/\D/', '', $request->mobile); // numbers only
        $query->where('mobile', 'like', "%{$mobile}%");
    }

    // 🆔 Aadhar
    if ($request->filled('aadhar')) {
        $query->where('aadhar_number', 'like', "%" . trim($request->aadhar) . "%");
    }

    // 📅 Date
    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    // 📌 Assignment
    if ($request->filled('assignment')) {
        $query->whereHas('assignments', function ($q) use ($request) {
            $q->where('assignments.id', $request->assignment);
        });
    }

    // 🌍 State
    if ($request->filled('state')) {
        $query->where('state', $request->state);
    }

    // 🏙 District
    if ($request->filled('district')) {
        $query->where('city', $request->district);
    }

    // 📍 Location
    if ($request->filled('location')) {
        $query->where('location', 'like', "%" . trim($request->location) . "%");
    }

    // 🎂 Age Filter (IMPROVED 🔥)
    if ($request->filled('age')) {
        $age = (int) $request->age;

        $query->whereBetween('dob', [
            now()->subYears($age + 1)->addDay(),
            now()->subYears($age)
        ]);
    }

    // ✅ Samarth Status
    if ($request->filled('samarth_status')) {
        $query->whereHas('assignments', function ($q) use ($request) {
            $q->wherePivot('samarth_done', $request->samarth_status);
        });
    }

    // ================= RESULT =================

    $mobilizations = $query
        ->latest()
        ->paginate(20)
        ->withQueryString(); // 🔥 KEEP FILTERS ON PAGINATION

    $assignments = Assignment::orderBy('assignment_name')->get();

    return view('mobilizations.index', compact('mobilizations', 'assignments'));
}
public function create()
{
    $roles = Role::all();
    return view('mobilizations.create', compact('roles'));
}

public function store(Request $request)
{
    // File validation rules
    $fileValidationRules = [
        'signature'              => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'photo'                  => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'passbook_photo'         => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'aadhar_front'           => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'aadhar_back'            => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'pan_card'               => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'driving_license'        => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'experience_letter'      => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'tenth_marksheet'        => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'twelfth_marksheet'      => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'graduation_marksheet'   => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'post_graduation_marksheet' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
    ];

    $request->validate(array_merge([
        'name'   => 'required|string|max:255',
        'mobile' => 'required|unique:mobilizations,mobile|max:15',
        'email' => 'nullable|email|max:255|unique:mobilizations,email,NULL,id',
          'pan_number' => ['nullable','regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
         'aadhar_number' => ['nullable','digits:12'],

          'tenth_passing_year' => ['nullable','digits:4'],
    'twelfth_passing_year' => ['nullable','digits:4'],
    'graduation_passing_year' => ['nullable','digits:4'],
    'post_graduation_passing_year' => ['nullable','digits:4'],
    ], $fileValidationRules));

    DB::transaction(function () use ($request) {

        $age = $request->dob ? Carbon::parse($request->dob)->age : null;

        $files = [
            'signature','photo','passbook_photo',
            'aadhar_front','aadhar_back',
            'pan_card','driving_license','experience_letter',
            'tenth_marksheet','twelfth_marksheet',
            'graduation_marksheet','post_graduation_marksheet'
        ];

        $uploadedFiles = [];

        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                // Use the new processing method
                $processedPath = $this->processUploadedFile(
                    $request->file($file), 
                    $file, 
                    '/mobilization_documents'
                );
                
                if ($processedPath) {
                    $uploadedFiles[$file] = $processedPath;
                }
            }
    }

        // =========================
        // 🧍 MAIN TABLE
        // =========================
        $languages = $request->languages;
        if (is_string($languages)) {
            $decodedLanguages = json_decode($languages, true);
            if (is_array($decodedLanguages)) {
                $languages = $decodedLanguages;
            } else {
                $languages = array_filter(array_map('trim', explode(',', $languages)));
            }
        }

        $mobilization = Mobilization::create([
            'identification_remark' => $request->identification_remark,

            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'whatsapp_number' => $request->whatsapp_number,

            'highest_qualification' => $request->highest_qualification,
            'dob' => $request->dob,
            'age' => $age,
            'gender' => $request->gender,
            'marital_status' => $request->marital_status,
            'state' => $request->state,
            'city' => $request->city,
            'location' => $request->location,

            'relocation' => $request->relocation ?? null,
            'languages' => $languages ?? [],

            'current_salary' => $request->current_salary,
            'preferred_salary' => $request->preferred_salary,

            'role_id' => $request->role_id,
            'sub_role_id' => $request->sub_role_id,

            // ✅ NEW FIELDS
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'pincode' => $request->pincode,
            'category' => $request->category,
            'religion' => $request->religion,
            'family_members' => $request->family_members,
            'dependents' => $request->dependents,
            'has_vehicle' => $request->has_vehicle ?? 0,
            'vehicle_details' => $request->vehicle_details,
            'has_smartphone' => $request->has_smartphone ?? 0,
        ]);

        // =========================
        // 📁 DOCUMENTS
        // =========================
        $mobilization->documents()->create([
            'photo' => $uploadedFiles['photo'] ?? null,
            'signature' => $uploadedFiles['signature'] ?? null,
            'aadhar_number' => $request->aadhar_number,
            'aadhar_front' => $uploadedFiles['aadhar_front'] ?? null,
            'aadhar_back' => $uploadedFiles['aadhar_back'] ?? null,
            'pan_number' => $request->pan_number,
            'pan_card' => $uploadedFiles['pan_card'] ?? null,
            'driving_license' => $uploadedFiles['driving_license'] ?? null,
            'experience_letter' => $uploadedFiles['experience_letter'] ?? null,
            'passbook_photo' => $uploadedFiles['passbook_photo'] ?? null,
        ]);

     
        $mobilization->education()->create([
            'tenth_passing_year' => $request->tenth_passing_year,
            'tenth_marksheet' => $uploadedFiles['tenth_marksheet'] ?? null,
            'twelfth_passing_year' => $request->twelfth_passing_year,
            'twelfth_marksheet' => $uploadedFiles['twelfth_marksheet'] ?? null,
            'graduation_passing_year' => $request->graduation_passing_year,
            'graduation_marksheet' => $uploadedFiles['graduation_marksheet'] ?? null,
            'post_graduation_passing_year' => $request->post_graduation_passing_year,
            'post_graduation_marksheet' => $uploadedFiles['post_graduation_marksheet'] ?? null,
        ]);

    
        $mobilization->bank()->create([
            'bank_account_number' => $request->bank_account_number,
            'ifsc_code' => $request->ifsc_code,
        ]);

        // =========================
        // 👥 REFERENCES (MULTIPLE)
        // =========================
        if ($request->reference_person) {
            foreach ($request->reference_person as $index => $person) {
                $mobilization->references()->create([
                    'reference_person' => $person,
                    'reference_mobile' => $request->reference_mobile[$index] ?? null,
                    'reference_email' => $request->reference_email[$index] ?? null,
                    'reference_detail' => $request->reference_detail[$index] ?? null,
                    'reference_designation' => $request->reference_designation[$index] ?? null,
                    'reference_organization' => $request->reference_organization[$index] ?? null,
                ]);
            }
        }

    });

    return redirect()->route('mobilizations.index')
        ->with('success','Mobilization added successfully.');
}


public function edit($id)
{
    $mobilization = Mobilization::with([
        'references',
        'experiences.role',
        'experiences.subRole',
        'documents',
        'education',
        'bank'
    ])->findOrFail($id);

    // ✅ ADD THIS LINE
    $roles = Role::all();

    $subRoles = SubRole::where('role_id', $mobilization->role_id)->get();

    return view('mobilizations.edit', compact('mobilization','roles','subRoles'));
}

public function show($id)
{
    $mobilization = Mobilization::with([
            'experiences.role',
            'experiences.subRole',
            'references',
            'documents',
            'education',
            'bank',
            'formResponses' => function($query) {
                $query->latest()->with(['form', 'values.field']);
            }
        ])
        ->withCount(['formResponses as form_responses_count'])
        ->findOrFail($id);
    // Normalize languages
    if (is_string($mobilization->languages)) {
        $mobilization->languages = json_decode($mobilization->languages, true) ?? [];
    }

    // Normalize relocation
    if (is_string($mobilization->relocation)) {
        $mobilization->relocation = json_decode($mobilization->relocation, true) ?? [$mobilization->relocation];
    }

    return view('mobilizations.show', compact('mobilization'));
}

public function update(Request $request, Mobilization $mobilization)
{

    // File validation rules
    $fileValidationRules = [
        'signature'              => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'photo'                  => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'passbook_photo'         => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'aadhar_front'           => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'aadhar_back'            => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'pan_card'               => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'driving_license'        => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'experience_letter'      => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'tenth_marksheet'        => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'twelfth_marksheet'      => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'graduation_marksheet'   => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        'post_graduation_marksheet' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
    ];

    $request->validate(array_merge([
        'name'   => 'required|string|max:255',
        'email' => 'nullable|email|max:255|unique:mobilizations,email,' . $mobilization->id,
        'mobile' => 'required|max:15|unique:mobilizations,mobile,' . $mobilization->id,
        'pan_number' => ['nullable','regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
        'aadhar_number' => ['nullable','digits:12'],
        'tenth_passing_year' => ['nullable','digits:4'],
        'twelfth_passing_year' => ['nullable','digits:4'],
        'graduation_passing_year' => ['nullable','digits:4'],
        'post_graduation_passing_year' => ['nullable','digits:4'],
    ], $fileValidationRules));

    DB::transaction(function () use ($request, $mobilization) {

        $age = $request->dob ? Carbon::parse($request->dob)->age : null;

        $languages = $request->languages;
        if (is_string($languages)) {
            $decodedLanguages = json_decode($languages, true);
            if (is_array($decodedLanguages)) {
                $languages = $decodedLanguages;
            } else {
                $languages = array_filter(array_map('trim', explode(',', $languages)));
            }
        }

        $mobilization->update([
            'identification_remark' => $request->identification_remark,

            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'whatsapp_number' => $request->whatsapp_number,

            'highest_qualification' => $request->highest_qualification,
            'dob' => $request->dob,
            'age' => $age,
            'gender' => $request->gender,
            'marital_status' => $request->marital_status,
            'state' => $request->state,
            'city' => $request->city,
            'location' => $request->location,

            'relocation' => $request->relocation ?? null,
            'languages' => $languages ?? [],

            'current_salary' => $request->current_salary,
            'preferred_salary' => $request->preferred_salary,

            'role_id' => $request->role_id,
            'sub_role_id' => $request->sub_role_id,

            // ✅ NEW FIELDS
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'pincode' => $request->pincode,
            'category' => $request->category,
            'religion' => $request->religion,
            'family_members' => $request->family_members,
            'dependents' => $request->dependents,
            'has_vehicle' => $request->has_vehicle ?? 0,
            'vehicle_details' => $request->vehicle_details,
            'has_smartphone' => $request->has_smartphone ?? 0,
        ]);

        // =========================
        // 📁 FILE UPLOAD
        // =========================
       // Replace the file upload section in update method (around line 450-460)

        $files = [
            'signature','photo','passbook_photo',
            'aadhar_front','aadhar_back',
            'pan_card','driving_license','experience_letter',
            'tenth_marksheet','twelfth_marksheet',
            'graduation_marksheet','post_graduation_marksheet'
        ];

        $uploadedFiles = [];

        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                // Use the new processing method
                $processedPath = $this->processUploadedFile(
                    $request->file($file), 
                    $file, 
                    '/mobilization_documents'
                );
                
                if ($processedPath) {
                    $uploadedFiles[$file] = $processedPath;
                }
            }
        }

        // =========================
        // 📁 DOCUMENTS
        // =========================
        $mobilization->documents()->updateOrCreate([], [
            'photo' => $uploadedFiles['photo'] ?? optional($mobilization->documents)->photo ?? null,
            'signature' => $uploadedFiles['signature'] ?? optional($mobilization->documents)->signature ?? null,
            'aadhar_number' => $request->aadhar_number,
            'aadhar_front' => $uploadedFiles['aadhar_front'] ?? optional($mobilization->documents)->aadhar_front ?? null,
            'aadhar_back' => $uploadedFiles['aadhar_back'] ?? optional($mobilization->documents)->aadhar_back ?? null,
            'pan_number' => $request->pan_number,
            'pan_card' => $uploadedFiles['pan_card'] ?? optional($mobilization->documents)->pan_card ?? null,
            'driving_license' => $uploadedFiles['driving_license'] ?? optional($mobilization->documents)->driving_license ?? null,
            'experience_letter' => $uploadedFiles['experience_letter'] ?? optional($mobilization->documents)->experience_letter ?? null,
            'passbook_photo' => $uploadedFiles['passbook_photo'] ?? optional($mobilization->documents)->passbook_photo ?? null,
        ]);

        // =========================
        // 🎓 EDUCATION
        // =========================
        $mobilization->education()->updateOrCreate([], [
            'tenth_passing_year' => $request->tenth_passing_year,
            'tenth_marksheet' => $uploadedFiles['tenth_marksheet'] ?? optional($mobilization->education)->tenth_marksheet ?? null,
            'twelfth_passing_year' => $request->twelfth_passing_year,
            'twelfth_marksheet' => $uploadedFiles['twelfth_marksheet'] ?? optional($mobilization->education)->twelfth_marksheet ?? null,
            'graduation_passing_year' => $request->graduation_passing_year,
            'graduation_marksheet' => $uploadedFiles['graduation_marksheet'] ?? optional($mobilization->education)->graduation_marksheet ?? null,
            'post_graduation_passing_year' => $request->post_graduation_passing_year,
            'post_graduation_marksheet' => $uploadedFiles['post_graduation_marksheet'] ?? optional($mobilization->education)->post_graduation_marksheet ?? null,
        ]);

        // =========================
        // 🏦 BANK
        // =========================
        $mobilization->bank()->updateOrCreate([], [
            'bank_account_number' => $request->bank_account_number,
            'ifsc_code' => $request->ifsc_code,
        ]);

        // =========================
        // 👥 REFERENCES (RESET & SAVE)
        // =========================
        $mobilization->references()->delete();

        if ($request->reference_person) {
            foreach ($request->reference_person as $index => $person) {
                $mobilization->references()->create([
                    'reference_person' => $person,
                    'reference_mobile' => $request->reference_mobile[$index] ?? null,
                    'reference_email' => $request->reference_email[$index] ?? null,
                    'reference_designation' => $request->reference_designation[$index] ?? null,
                    'reference_organization' => $request->reference_organization[$index] ?? null,
                    'reference_detail' => $request->reference_detail[$index] ?? null,
                ]);
            }
        }

        // =========================
        // 💼 EXPERIENCES (RESET & SAVE)
        // =========================
        $mobilization->experiences()->delete();

        if ($request->organization) {
            foreach ($request->organization as $index => $org) {
                if (!empty($org)) {
                    $mobilization->experiences()->create([
                        'organization' => $org,
                        'designation' => $request->designation[$index] ?? null,
                        'duration' => $request->duration[$index] ?? null,
                        'role_id' => $request->role_category[$index] ?? null,
                        'sub_role_id' => $request->sub_role[$index] ?? null,
                    ]);
                }
            }
        }

    });

    return redirect()->route('mobilizations.index')
        ->with('success','Mobilization updated successfully.');
}


public function destroy(Mobilization $mobilization)
{
    $files = [
        'signature','photo','passbook_photo',
        'aadhar_front','aadhar_back',
        'pan_card','driving_license','experience_letter',
        'tenth_marksheet','twelfth_marksheet',
        'graduation_marksheet','post_graduation_marksheet'
    ];

    foreach ($files as $file) {
        if ($mobilization->$file) {
            // Storage::disk('public')->delete($mobilization->$file);
            if ($mobilization->$file && file_exists(public_path($mobilization->$file))) {
    unlink(public_path($mobilization->$file));
    }
        }
    }

    $mobilization->delete();

    return redirect()->route('mobilizations.index')
        ->with('success','Mobilization deleted successfully.');
}

    public function getSubRoles($roleId)
{
    return SubRole::where('role_id', $roleId)->get();
}

public function remarks($id)
{
    $mobilization = Mobilization::findOrFail($id);

    $remarks = MobilizationRemark::where('mobilization_id', $id)
                ->latest()
                ->get();

    return view('mobilizations.remarks', compact('mobilization','remarks'));
}   
public function storeRemark(Request $request, $id)
{
    $request->validate([
        'calling_date' => 'required|date',
        'calling_time' => 'required',
        'calling_mode' => 'required',
        'call_action' => 'required',
        'call_response' => 'required',
        'next_followup_date' => 'nullable|date',
        'tag' => 'nullable|string',
        'notes' => 'nullable|string',
        'status' => 'required|string|max:50'
    ]);

    MobilizationRemark::create([
        'mobilization_id' => $id,
        'calling_date' => $request->calling_date,
        'calling_time' => $request->calling_time,
        'calling_mode' => $request->calling_mode,
        'call_action' => $request->call_action,
        'call_response' => $request->call_response,
        'next_followup_date' => $request->next_followup_date,
        'tag' => $request->tag,
        'notes' => $request->notes,
        'status' => $request->status
    ]);

    return redirect()->back()->with('success', 'Call remark added successfully.');
}


public function importUser(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        'identification_remark' => 'nullable|string|max:255'
    ]);

    try {
        $file = $request->file('file');
        $remark = $request->identification_remark;

        // Create import instance
        $import = new FlexibleMobilizationImport($remark);

        // Import the file
        Excel::import($import, $file);

        // Get the statistics
        $successCount = $import->successCount;
        $failureCount = $import->failureCount;
        $errorMessages = $import->errorMessages;

        // Log the import result
        \Log::info("Import completed: {$successCount} successful, {$failureCount} failed");

        // Prepare response message
        if ($successCount === 0 && $failureCount === 0) {
            // No data was imported at all
            return redirect()->back()
                ->with('error', 'No valid data found in the file. Please check your file format.');
        }

        if ($successCount === 0) {
            // All rows failed
            $errorText = count($errorMessages) <= 5 
                ? implode("\n", $errorMessages)
                : implode("\n", array_slice($errorMessages, 0, 5)) . "\n... and " . (count($errorMessages) - 5) . " more errors";
            
            return redirect()->back()
                ->with('error', "Import failed. All {$failureCount} rows had errors:\n\n" . $errorText);
        }

        if ($failureCount > 0) {
            // Mixed success and failure
            $errorText = count($errorMessages) <= 5 
                ? implode("\n", $errorMessages)
                : implode("\n", array_slice($errorMessages, 0, 5)) . "\n... and " . (count($errorMessages) - 5) . " more errors";
            
            $message = "✅ {$successCount} mobilization(s) imported successfully!\n\n" .
                       "⚠️ {$failureCount} row(s) failed:\n\n" . $errorText;
            
            return redirect()->route('mobilizations.index')
                ->with('partial_success', $message)
                ->with('success_count', $successCount)
                ->with('failure_count', $failureCount);
        }

        // All successful
        return redirect()->route('mobilizations.index')
            ->with('success', "✅ {$successCount} mobilization(s) imported successfully!");

    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        $failures = $e->failures();
        $errorMessages = [];
        
        foreach (array_slice($failures, 0, 5) as $failure) {
            $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
        }
        
        $errorText = implode("\n", $errorMessages);
        if (count($failures) > 5) {
            $errorText .= "\n... and " . (count($failures) - 5) . " more errors";
        }
        
        \Log::error('Import validation error: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Validation errors in file:\n\n' . $errorText);
            
    } catch (\Exception $e) {
        \Log::error('Import error: ' . $e->getMessage() . '\n' . $e->getTraceAsString());
        
        return redirect()->back()
            ->with('error', 'Error importing file: ' . $e->getMessage());
    }
}

public function assignCandidates(Request $request)
{
    $request->validate([
        'assignment_id' => 'required|exists:assignments,id',
        'mobilization_ids' => 'required|array'
    ]);

    $assignment = Assignment::findOrFail($request->assignment_id);

    // attach candidates
    $assignment->mobilizations()->syncWithoutDetaching($request->mobilization_ids);

    return back()->with('success', 'Candidates assigned successfully!');
}


public function downloadSample($type)
{
    $files = [
        'basic' => public_path('samples/mobilization_sample_basic.xlsx'),
        'advanced' => public_path('samples/mobilization_sample_advanced.xlsx'),
        'csv' => public_path('samples/mobilization_sample.csv'),
    ];

    if (!isset($files[$type])) {
        abort(404);
    }

    return response()->download($files[$type]);
}

public function submitExternalForm($id)
{
    $candidate = Mobilization::findOrFail($id);

    $entryId = 75;

    $url = "http://localhost/reliance_core/default/registersaveril.php?entry_id=".$entryId;

    $request = Http::timeout(120);

    // PAN
    if(!empty($candidate->documents->pan_card) && file_exists(public_path($candidate->documents->pan_card))){
        $request = $request->attach(
            'pan',
            file_get_contents(storage_path('app/public/'.$candidate->documents->pan_card)),
            'pan.jpg'
        );
    }

    // Aadhar Front
    if(!empty($candidate->aadhar_front) && file_exists(public_path($candidate->aadhar_front))){
        $request = $request->attach(
            'adharp',
            file_get_contents(public_path($candidate->aadhar_front)),
            'aadhar_front.jpg'
        );
    }

    // Aadhar Back
    if(!empty($candidate->aadhar_back) && file_exists(public_path($candidate->aadhar_back))){
        $request = $request->attach(
            'adhab',
            file_get_contents(public_path($candidate->aadhar_back)),
            'aadhar_back.jpg'
        );
    }

    // 10th Marksheet
    if(!empty($candidate->tenth_marksheet) && file_exists(public_path($candidate->tenth_marksheet))){
        $request = $request->attach(
            'marksheet',
            file_get_contents(public_path($candidate->tenth_marksheet)),
            'marksheet.jpg'
        );
    }

    // Photo
    if(!empty($candidate->photo) && file_exists(public_path($candidate->photo))){
        $request = $request->attach(
            'pcphoto',
            file_get_contents(public_path($candidate->photo)),
            'photo.jpg'
        );
    }

    // Samarth Document
    if(!empty($candidate->samarth_doc) && file_exists(public_path($candidate->samarth_doc))){
        $request = $request->attach(
            'samarth',
            file_get_contents(public_path($candidate->samarth_doc)),
            'samarth.jpg'
        );
    }

    // Submit Form
    $response = $request->post($url, [
        'username' => $candidate->name,
        'number' => $candidate->mobile,
        'email' => $candidate->email,
        'city' => $candidate->city,
        'dob' => $candidate->dob,
        'flexCheckDefault' => 'on',
        'submitr' => 'Submit'
    ]);

    return $response->body();
}

public function getFormData($id)
{
    $mobilization = Mobilization::with(['formResponses.values.field', 'formResponses.form'])->findOrFail($id);
    
    // Get latest form response with values and form details
    $latestResponse = $mobilization->formResponses()
        ->with(['form' => function($query) {
            $query->with('fields');
        }, 'values' => function($query) {
            $query->with('field');
        }])
        ->latest()
        ->first();
    
    if (!$latestResponse) {
        return response()->json([
            'success' => false,
            'message' => 'No form data available'
        ]);
    }
    
    $formData = [];
    
    // Get all form fields to ensure we show all fields even if no response value
    if ($latestResponse->form && $latestResponse->form->fields) {
        foreach ($latestResponse->form->fields as $field) {
            // Find the corresponding response value
            $responseValue = $latestResponse->values->firstWhere('field_id', $field->id);
            
            $formData[] = [
                'field_name' => $field->label,
                'field_type' => $field->type,
                'value' => $responseValue ? $responseValue->value : null,
                'file_url' => $responseValue ? $responseValue->file_url : null,
                'file_type' => $responseValue ? $responseValue->file_type : null,
            ];
        }
    } else {
        // Fallback: just use the response values
        foreach ($latestResponse->values as $value) {
            if ($value->field) {
                $formData[] = [
                    'field_name' => $value->field->label,
                    'field_type' => $value->field->type,
                    'value' => $value->value,
                    'file_url' => $value->file_url,
                    'file_type' => $value->file_type,
                ];
            }
        }
    }
    
    return response()->json([
        'success' => true,
        'formData' => $formData,
        'candidateName' => $mobilization->name,
        'formTitle' => $latestResponse->form?->title ?? 'Unknown Form'
    ]);
}

public function getAvailableForms($id)
{
    $mobilization = Mobilization::with('assignments')->findOrFail($id);
    
    // Check if student has any assignments
    $assignmentIds = $mobilization->assignments->pluck('id')->toArray();
    
    if (empty($assignmentIds)) {
        return response()->json([
            'success' => true,
            'has_assignments' => false,
            'forms' => [],
            'message' => 'No assignment assigned yet'
        ]);
    }
    
    // Get form IDs linked to student's assignments via LinkAssignment table
    $linkedFormIds = \App\Models\LinkAssignment::whereIn('assignment_id', $assignmentIds)
        ->pluck('form_id')
        ->unique()
        ->toArray();
    
    if (empty($linkedFormIds)) {
        return response()->json([
            'success' => true,
            'has_assignments' => true,
            'has_forms' => false,
            'forms' => [],
            'message' => 'No forms linked to your assignments'
        ]);
    }
    
    // Get all active forms that are linked to student's assignments
    $today = now()->toDateString();
    
    $forms = Form::whereIn('id', $linkedFormIds)
        ->where(function($query) use ($today) {
            $query->whereNull('valid_from')
                  ->orWhere('valid_from', '<=', $today);
        })->where(function($query) use ($today) {
            $query->whereNull('valid_to')
                  ->orWhere('valid_to', '>=', $today);
        })->get(['id', 'title', 'description', 'valid_to']);
    
    return response()->json([
        'success' => true,
        'has_assignments' => true,
        'has_forms' => $forms->count() > 0,
        'forms' => $forms,
        'message' => $forms->count() > 0 ? null : 'No active forms available for your assignments'
    ]);
}

public function generateFormLink(Request $request, $mobilizationId)
{
    try {
        $request->validate([
            'form_id' => 'required|exists:forms,id'
        ]);

        $mobilization = Mobilization::findOrFail($mobilizationId);
        $form = Form::findOrFail($request->form_id);

        // Check if form is active
        $today = now()->toDateString();

        if (
            ($form->valid_from && $today < $form->valid_from) ||
            ($form->valid_to && $today > $form->valid_to)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'This form is not active'
            ], 400);
        }

        // Generate token
        $token = Str::random(64);

        // Prepare prefill data safely
        $prefillData = $this->preparePrefillData($mobilization);

        // Store token
        FormPrefillToken::create([
            'token' => $token,
            'mobilization_id' => $mobilization->id,
            'form_id' => $form->id,
            'expires_at' => now()->addDays(7),
            'prefill_data' => json_encode($prefillData ?? [])
        ]);

        // Generate link
        $link = route('forms.prefilled.token', ['token' => $token]);

        return response()->json([
            'success' => true,
            'link' => $link,
            'mobilization_name' => $mobilization->name ?? '',
            'form_title' => $form->title ?? '',
            'token' => $token
        ]);

    } catch (\Throwable $e) {

        \Log::error('Generate Form Link Error:', [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ], 500);
    }
}

private function preparePrefillData($mobilization)
{
    try {
        return [
            'name' => $mobilization->name ?? '',
            'email' => $mobilization->email ?? '',
            'mobile' => $mobilization->mobile ?? '',
            'whatsapp_number' => $mobilization->whatsapp_number ?? '',
            'highest_qualification' => $mobilization->highest_qualification ?? '',
            'dob' => $mobilization->dob ?? null,
            'age' => $mobilization->age ?? null,
            'gender' => $mobilization->gender ?? '',
            'marital_status' => $mobilization->marital_status ?? '',
            'state' => $mobilization->state ?? '',
            'city' => $mobilization->city ?? '',
            'location' => $mobilization->location ?? '',
            'aadhar_number' => $mobilization->aadhar_number ?? '',
            'pan_number' => $mobilization->pan_number ?? '',
            'relocation' => $mobilization->relocation ?? '',
            'languages' => is_array($mobilization->languages)
                ? $mobilization->languages
                : json_decode($mobilization->languages ?? '[]', true),

            'current_salary' => $mobilization->current_salary ?? 0,
            'preferred_salary' => $mobilization->preferred_salary ?? 0,
            'role_id' => $mobilization->role_id ?? null,
            'sub_role_id' => $mobilization->sub_role_id ?? null,
        ];
    } catch (\Throwable $e) {
        \Log::error('Prefill Data Error:', [
            'error' => $e->getMessage(),
            'mobilization_id' => $mobilization->id ?? null
        ]);

        return []; // never break API
    }
}

public function getContactDetails($id)
{
    $mobilization = Mobilization::findOrFail($id);
    
    return response()->json([
        'mobile' => $mobilization->mobile,
        'whatsapp_number' => $mobilization->whatsapp_number,
        'email' => $mobilization->email,
        'name' => $mobilization->name
    ]);
}

public function getFormFiles($id)
{
    try {
        $mobilization = Mobilization::with(['documents', 'education'])->findOrFail($id);

        $files = [];

        // Get files from form responses
        $formResponses = FormResponse::with([
            'values.field',
            'form'
        ])
        ->where('mobilization_id', $id)
        ->latest()
        ->get();

        $formTitle = 'Form';

        foreach ($formResponses as $formResponse) {
            if ($formResponse->form) {
                $formTitle = $formResponse->form->title ?? 'Form';
            }

            foreach ($formResponse->values as $value) {
                if ($value->file_url) {
                    $files[] = [
                        'field_label'   => $value->field ? $value->field->label : 'File',
                        'file_url'      => $value->file_url,
                        'file_name'     => basename($value->file_url),
                        'file_type'     => $value->file_type,
                        'file_size'     => $value->file_size,
                        'file_extension'=> $value->file_extension
                    ];
                }
            }
        }

        // Mobilization documents
        if ($mobilization->documents) {
            $docFiles = [
                'photo' => $mobilization->documents->photo,
                'signature' => $mobilization->documents->signature,
                'aadhar_front' => $mobilization->documents->aadhar_front,
                'aadhar_back' => $mobilization->documents->aadhar_back,
                'pan_card' => $mobilization->documents->pan_card,
                'driving_license' => $mobilization->documents->driving_license,
                'experience_letter' => $mobilization->documents->experience_letter,
                'passbook_photo' => $mobilization->documents->passbook_photo,
            ];

            foreach ($docFiles as $label => $file) {
                if ($file) {
                    $files[] = [
                        'field_label'   => ucfirst(str_replace('_', ' ', $label)),
                        'file_url'      => $file,
                        'file_name'     => basename($file),
                        'file_type'     => 'document',
                        'file_size'     => null,
                        'file_extension'=> pathinfo($file, PATHINFO_EXTENSION)
                    ];
                }
            }
        }

        // Education files
        if ($mobilization->education) {
            $eduFiles = [
                'tenth_marksheet' => $mobilization->education->tenth_marksheet,
                'twelfth_marksheet' => $mobilization->education->twelfth_marksheet,
                'graduation_marksheet' => $mobilization->education->graduation_marksheet,
                'post_graduation_marksheet' => $mobilization->education->post_graduation_marksheet,
            ];

            foreach ($eduFiles as $label => $file) {
                if ($file) {
                    $files[] = [
                        'field_label'   => ucfirst(str_replace('_', ' ', $label)),
                        'file_url'      => $file,
                        'file_name'     => basename($file),
                        'file_type'     => 'document',
                        'file_size'     => null,
                        'file_extension'=> pathinfo($file, PATHINFO_EXTENSION)
                    ];
                }
            }
        }

        // Remove duplicates
        $uniqueFiles = [];
        $seenUrls = [];

        foreach ($files as $file) {
            if (!in_array($file['file_url'], $seenUrls)) {
                $uniqueFiles[] = $file;
                $seenUrls[] = $file['file_url'];
            }
        }

        return response()->json([
            'success' => true,
            'files' => $uniqueFiles,
            'mobilization_name' => $mobilization->name,
            'form_title' => $formTitle
        ]);

    } catch (\Exception $e) {
        \Log::error("getFormFiles error: " . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong'
        ], 500);
    }
}


private function processUploadedFile($file, $fileType, $folder)
{
    try {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = time() . '_' . uniqid() . '.' . $extension;

        // 🔥 FORCE correct folder
        $folder = 'mobilization_documents';

        // final path inside storage/app/public/
        $storagePath = $folder . '/' . $filename;

        // ================= IMAGE PROCESSING =================
        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {

            try {
                $response = Http::timeout(30)
                    ->withHeaders([
                        'X-Api-Key' => env('REMOVE_BG_API_KEY')
                    ])
                    ->attach(
                        'image_file',
                        file_get_contents($file->getRealPath()),
                        $filename
                    )
                    ->post('https://api.remove.bg/v1.0/removebg', [
                        'size' => 'auto'
                    ]);

                if ($response->successful()) {
                    Storage::disk('public')->put($storagePath, $response->body());
                    return $storagePath;
                }

            } catch (\Exception $e) {
                \Log::warning("Remove.bg failed: " . $e->getMessage());
            }

            // fallback
            Storage::disk('public')->putFileAs($folder, $file, $filename);
            return $storagePath;
        }

        // ================= PDF =================
        if ($extension === 'pdf') {

            try {
                $response = Http::timeout(60)
                    ->withHeaders([
                        'x-api-key' => env('PDFCO_API_KEY')
                    ])
                    ->attach(
                        'file',
                        file_get_contents($file->getRealPath()),
                        $filename
                    )
                    ->post('https://api.pdf.co/v1/pdf/optimize');

                if ($response->successful() && isset($response['url'])) {

                    $compressedFile = Http::get($response['url'])->body();
                    Storage::disk('public')->put($storagePath, $compressedFile);

                    return $storagePath;
                }

            } catch (\Exception $e) {
                \Log::warning("PDF compression failed: " . $e->getMessage());
            }

            // fallback
            Storage::disk('public')->putFileAs($folder, $file, $filename);
            return $storagePath;
        }

        // ================= DEFAULT =================
        Storage::disk('public')->putFileAs($folder, $file, $filename);

        return $storagePath;

    } catch (\Exception $e) {
        \Log::error("File Processing Error: " . $e->getMessage());
        return null;
    }
}

 public function downloadFile(Request $request)
    {
        $request->validate([
            'file_url' => 'required|string',
            'file_name' => 'nullable|string'
        ]);
        
        $filePath = $request->file_url;
        $fileName = $request->file_name ?? basename($filePath);
        
        // Decode URL if needed
        $filePath = urldecode($filePath);
        
        // Try different storage paths
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath, $fileName);
        }
        
        if (Storage::exists($filePath)) {
            return Storage::download($filePath, $fileName);
        }
        
        return response()->json(['error' => 'File not found'], 404);
    }

    /**
     * Download multiple files as ZIP
     */
public function downloadFilesAsZip(Request $request)
{
    $request->validate([
        'file_urls' => 'required|array',
        'file_urls.*' => 'string'
    ]);

    $fileUrls = $request->file_urls;

    if (empty($fileUrls)) {
        return response()->json(['error' => 'No files selected'], 400);
    }

    $zip = new \ZipArchive();

    // Create temp file in system temp (NO permission issue)
    $tempFile = tempnam(sys_get_temp_dir(), 'zip');

    if ($zip->open($tempFile, \ZipArchive::CREATE) !== TRUE) {
        return response()->json(['error' => 'Could not create ZIP'], 500);
    }

    $added = 0;

    foreach ($fileUrls as $fileUrl) {

        $filePath = ltrim(urldecode($fileUrl), '/');

        // Try from storage
        if (\Storage::disk('public')->exists($filePath)) {
            $fullPath = \Storage::disk('public')->path($filePath);
        } 
        // Try from public
        elseif (file_exists(public_path($filePath))) {
            $fullPath = public_path($filePath);
        } 
        else {
            continue;
        }

        if (!file_exists($fullPath)) {
            continue;
        }

        $fileName = basename($filePath);
        $fileName = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $fileName);

        if ($zip->locateName($fileName) !== false) {
            $fileName = time() . '_' . $fileName;
        }

        // Add file directly
        $zip->addFile($fullPath, $fileName);
        $added++;
    }

    $zip->close();

    if ($added === 0) {
        return response()->json(['error' => 'No valid files found'], 404);
    }

    return response()->download($tempFile, 'documents.zip')->deleteFileAfterSend(true);
}
}


