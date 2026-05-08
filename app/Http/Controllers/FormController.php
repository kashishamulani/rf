<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Models\Form;
use App\Models\FormField;
use App\Models\FormResponse;
use App\Models\Mobilization;
use App\Models\MobilizationReference;
use App\Models\MobilizationExperience;
use App\Models\Role;
use App\Models\SubRole;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\FormResponseValue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\FormPrefillToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::withCount('fields')->latest()->get();
        return view('links.index', compact('forms'));
    }

    public function create()
    {
        $mobilizationFields = [
            // Personal
            ['label' => 'Full Name', 'type' => 'text'],
            ['label' => 'Email', 'type' => 'email'],
            ['label' => 'Mobile', 'type' => 'text'],
            ['label' => 'WhatsApp Number', 'type' => 'text'],
            ['label' => 'Highest Qualification', 'type' => 'text'],
            ['label' => 'Date of Birth', 'type' => 'date'],
            ['label' => 'Gender', 'type' => 'select'],
            ['label' => 'Marital Status', 'type' => 'select'],
            ['label' => 'State', 'type' => 'select'],
            ['label' => 'City', 'type' => 'select'],
            ['label' => 'Address', 'type' => 'text'],
            ['label' => 'Identification Remark', 'type' => 'text'],
            ['label' => 'Languages', 'type' => 'text'],

            // Salary & Bank
            ['label' => 'Current Salary', 'type' => 'text'],
            ['label' => 'Preferred Salary', 'type' => 'text'],
            ['label' => 'Bank Account Number', 'type' => 'text'],
            ['label' => 'IFSC Code', 'type' => 'text'],

            // Experience
            ['label' => 'Organization', 'type' => 'text'],
            ['label' => 'Designation', 'type' => 'text'],
            ['label' => 'Duration', 'type' => 'text'],
            ['label' => 'Role Category', 'type' => 'select'],
            ['label' => 'Sub Role', 'type' => 'select'],

            // Documents - ID Proof
            ['label' => 'PAN Number', 'type' => 'text'],
            ['label' => 'PAN Card', 'type' => 'file'],
            ['label' => 'Aadhar Number', 'type' => 'text'],
            ['label' => 'Aadhar Front', 'type' => 'file'],
            ['label' => 'Aadhar Back', 'type' => 'file'],

            // Documents - Photos
            ['label' => 'Photo', 'type' => 'file'],
            ['label' => 'Signature', 'type' => 'file'],
            ['label' => 'Passbook', 'type' => 'file'],
            ['label' => 'Driving License', 'type' => 'file'],
            ['label' => 'Experience Letter', 'type' => 'file'],

            // Documents - Education
            ['label' => '10th Passing Year', 'type' => 'text'],
            ['label' => '10th Marksheet', 'type' => 'file'],
            ['label' => '12th Passing Year', 'type' => 'text'],
            ['label' => '12th Marksheet', 'type' => 'file'],
            ['label' => 'Graduation Passing Year', 'type' => 'text'],
            ['label' => 'Graduation Marksheet', 'type' => 'file'],
            ['label' => 'Post Graduation Passing Year', 'type' => 'text'],
            ['label' => 'Post Graduation Marksheet', 'type' => 'file'],
        ];

        return view('links.create', compact('mobilizationFields'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable',
            'selected_fields' => 'nullable|string'
        ]);

        $instructions = $request->input('instructions', []);
        if (is_array($instructions)) {
            $instructions = array_values(array_filter($instructions, fn($i) => trim($i) !== ''));
        }

        $form = Form::create([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::random(10),
            'instructions' => json_encode($instructions),
        ]);

        $fields = json_decode($request->selected_fields, true);

        if (!empty($fields) && is_array($fields)) {
            foreach ($fields as $field) {
                if (!isset($field['label']) || !isset($field['type'])) {
                    continue;
                }
                
                $options = null;
                if ($field['type'] === 'select') {
                    $options = $this->getSelectOptions($field['label']);
                    Log::info('Saving select field: ' . $field['label'], ['options' => $options]);
                    
                    if (!empty($options)) {
                        $options = json_encode($options);
                    }
                }
                
                FormField::create([
                    'form_id' => $form->id,
                    'label' => $field['label'],
                    'type' => $field['type'],
                    'options' => $options,
                    'is_required' => isset($field['is_required']) && $field['is_required'] ? 1 : 0,
                ]);
            }
        }

        return redirect()
            ->route('links.index')
            ->with('success', 'Form Created Successfully');
    }

    public function submit(Request $request, $slug)
    {
        $form = Form::with('fields')->where('slug', $slug)->firstOrFail();

        $rules = [];
        foreach ($form->fields as $field) {
            // Only add required validation if is_required is true
            if ($field->is_required) {
                $rule = ['required'];
            } else {
                $rule = ['nullable'];
            }
            
            if ($field->type === 'email') {
                $rule[] = 'email';
            }
            if ($field->type === 'file') {
                $rule[] = 'file';
                $rule[] = 'mimes:jpg,jpeg,png,pdf';
                $rule[] = 'max:7168';
            }
            $rules['field_' . $field->id] = $rule;
        }
        $request->validate($rules);

        // Document/file field mapping (form label => mobilization column)
        $documentMapping = [
            'pan card' => 'pan_card',
            'aadhar front' => 'aadhar_front',
            'aadhar back' => 'aadhar_back',
            'photo' => 'photo',
            'signature' => 'signature',
            'passbook photo' => 'passbook_photo',
            'passbook' => 'passbook_photo',
            '10th marksheet' => 'tenth_marksheet',
            'tenth marksheet' => 'tenth_marksheet',
            '12th marksheet' => 'twelfth_marksheet',
            'twelfth marksheet' => 'twelfth_marksheet',
            'graduation marksheet' => 'graduation_marksheet',
            'post graduation marksheet' => 'post_graduation_marksheet',
            'driving license' => 'driving_license',
            'experience letter' => 'experience_letter',
        ];

        $fieldMap = [
            'full name' => 'name',
            'father name' => 'father_name',
            'mother name' => 'mother_name',
            'email' => 'email',
            'mobile' => 'mobile',
            'whatsapp number' => 'whatsapp_number',
            'highest qualification' => 'highest_qualification',
            'date of birth' => 'dob',
            'gender' => 'gender',
            'marital status' => 'marital_status',
            'state' => 'state',
            'city' => 'city',
            'district' => 'city',
            'address' => 'location',
            'pincode' => 'pincode',
            'category' => 'category',
            'religion' => 'religion',
            'family members' => 'family_members',
            'dependents' => 'dependents',
            'has vehicle' => 'has_vehicle',
            'vehicle details' => 'vehicle_details',
            'has smartphone' => 'has_smartphone',
            'pan number' => 'pan_number',
            'aadhar number' => 'aadhar_number',
            'identification remark' => 'identification_remark',
            'languages' => 'languages',
            'relocation' => 'relocation',
            'current salary' => 'current_salary',
            'preferred salary' => 'preferred_salary',
            'bank account number' => 'bank_account_number',
            'ifsc code' => 'ifsc_code',
            '10th passing year' => 'tenth_passing_year',
            '12th passing year' => 'twelfth_passing_year',
            'graduation passing year' => 'graduation_passing_year',
            'post graduation passing year' => 'post_graduation_passing_year',
        ];

        // Extract data for mobilization
        $data = [];
        foreach ($form->fields as $field) {
            $label = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
            if (isset($fieldMap[$label])) {
                $data[$fieldMap[$label]] = $request->input('field_' . $field->id);
            }
        }

        if (!empty($data['dob'])) {
            $data['age'] = \Carbon\Carbon::parse($data['dob'])->age;
        }

        // Create or update mobilization first
        $mobilization = null;
        if (!empty($data['mobile'])) {
            $mobilization = Mobilization::updateOrCreate(
                ['mobile' => $data['mobile']],
                $data
            );
        }

        // Create form response with mobilization_id
        $responseData = [
            'form_id' => $form->id,
            'mobilization_id' => $mobilization ? $mobilization->id : null,
        ];
        
        // Only add uuid if the column exists
        if (Schema::hasColumn('form_responses', 'uuid')) {
            $responseData['uuid'] = (string) \Str::uuid();
        }
        
        $response = FormResponse::create($responseData);

        // Update mobilization with form_response_id for backward compatibility
        if ($mobilization) {
            $mobilization->update(['form_response_id' => $response->id]);
        }

        $mobilizationUpdateData = [];

        foreach ($form->fields as $field) {
            $inputName = 'field_' . $field->id;
            $label = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));

            if ($field->type === 'file' && $request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $path = $file->store('uploads/mobilization_docs', 'public');
                $fullPath = storage_path('app/public/' . $path);
                $mime = $file->getMimeType();
                $finalPath = $path;

                // ✅ PDF Compression
                if (str_contains($mime, 'pdf')) {
                    \Log::info('Attempting PDF compression', ['label' => $label, 'file' => $fullPath]);
                    try {
                        $compressedPath = $this->compressPdf($fullPath);
                        if ($compressedPath) {
                            Storage::disk('public')->delete($path);
                            $finalPath = $compressedPath;
                            $fullPath = storage_path('app/public/' . $finalPath);
                            \Log::info('PDF compression successful', ['finalPath' => $finalPath]);
                        }
                    } catch (\Exception $e) {
                        \Log::warning('PDF compression failed: ' . $e->getMessage());
                    }
                }
                $isDocumentImage = 
                    str_contains($label, 'photo') || 
                    str_contains($label, 'signature') || 
                    str_contains($label, 'aadhar') || 
                    str_contains($label, 'pan') || 
                    str_contains($label, 'marksheet');
                
                \Log::info('Public form file upload debug', ['label' => $label, 'mime' => $mime, 'isDocumentImage' => $isDocumentImage, 'fullPath' => $fullPath]);

                if (str_contains($mime, 'image') && $isDocumentImage) {
                    try {
                        \Log::info('Attempting background removal', ['label' => $label, 'file' => $fullPath]);
                        $nobgPath = $this->removeBackground($fullPath);
                        if ($nobgPath) {
                
                            if ($finalPath !== $path) {
                                Storage::disk('public')->delete($finalPath);
                            }
                            $finalPath = $nobgPath;
                            \Log::info('Background removal successful', ['finalPath' => $finalPath]);
                        }
                    } catch (\Exception $e) {
                        \Log::warning('Background removal failed: ' . $e->getMessage());
                    }
                }
                FormResponseValue::create([
                    'response_id' => $response->id,
                    'field_id' => $field->id,
                    'value' => null,
                    'file_url' => $finalPath,
                    'file_size' => $file->getSize(),
                    'file_type' => $file->getMimeType(),
                    'file_extension' => $file->getClientOriginalExtension(),
                ]);
                // Save document file to mobilization
                if (isset($documentMapping[$label]) && $mobilization) {
                    $mobilizationUpdateData[$documentMapping[$label]] = $finalPath;
                }
            } else {
                $fieldValue = $request->input($inputName);
                FormResponseValue::create([
                    'response_id'    => $response->id,
                    'field_id'       => $field->id,
                    'value'          => $fieldValue,
                    'file_url'       => null,
                    'file_size'      => null,
                    'file_type'      => null,
                    'file_extension' => null,
                ]);
                
                // Also save text field values to mobilization if they map
                if ($mobilization && isset($fieldMap[$label])) {
                    // Save the value even if empty (to allow updates/clearing)
                    $mobilizationUpdateData[$fieldMap[$label]] = $fieldValue;
                }
            }
        }

        // Update mobilization with all collected data (both text fields and documents)
        $bankData = [];
        if (isset($mobilizationUpdateData['bank_account_number'])) {
            $bankData['bank_account_number'] = $mobilizationUpdateData['bank_account_number'];
            unset($mobilizationUpdateData['bank_account_number']);
        }
        if (isset($mobilizationUpdateData['ifsc_code'])) {
            $bankData['ifsc_code'] = $mobilizationUpdateData['ifsc_code'];
            unset($mobilizationUpdateData['ifsc_code']);
        }
        
        if ($mobilization && !empty($mobilizationUpdateData)) {
            $mobilization->update($mobilizationUpdateData);
        }
        
        // Handle bank details separately
        if ($mobilization && !empty($bankData)) {
            $mobilization->bank()->updateOrCreate(
                ['mobilization_id' => $mobilization->id],
                $bankData
            );
        }

        // Handle references
        if ($mobilization && $request->has('reference_person') && is_array($request->reference_person)) {
            $mobilization->references()->delete();
            foreach ($request->reference_person as $index => $person) {
                if (!empty($person)) {
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
        }

        // Handle experiences
        if ($mobilization && $request->has('organization') && is_array($request->organization)) {
            $mobilization->experiences()->delete();
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

        return redirect()->route('forms.thankyou', ['slug' => $slug])->with('success', 'Form submitted successfully!');
    }

    public function show($slug)
    {
        $form = Form::where('slug', $slug)
            ->with('fields')
            ->firstOrFail();

        $today = now()->toDateString();
        if (
            ($form->valid_from && $today < $form->valid_from) ||
            ($form->valid_to && $today > $form->valid_to)
        ) {
            abort(403, 'This form is not active right now.');
        }

        $orderMap = [
            'full name' => 1,
            'email' => 2,
            'mobile' => 3,
            'whatsapp number' => 4,
            'highest qualification' => 5,
            'date of birth' => 6,
            'gender' => 7,
            'marital status' => 8,
            'state' => 9,
            'city' => 10,
            'address' => 11,
            'identification remark' => 12,
            'languages' => 13,
            'current salary' => 14,
            'preferred salary' => 15,
            'bank account number' => 16,
            'ifsc code' => 17,
            'organization' => 18,
            'designation' => 19,
            'duration' => 20,
            'role category' => 21,
            'sub role' => 22,
            'pan number' => 23,
            'pan card' => 24,
            'aadhar number' => 25,
            'aadhar front' => 26,
            'aadhar back' => 27,
            'photo' => 28,
            'signature' => 29,
            'passbook' => 30,
            'driving license' => 31,
            'experience letter' => 32,
            '10th passing year' => 33,
            '10th marksheet' => 34,
            '12th passing year' => 35,
            '12th marksheet' => 36,
            'graduation passing year' => 37,
            'graduation marksheet' => 38,
            'post graduation passing year' => 39,
            'post graduation marksheet' => 40,
        ];

        $form->fields = $form->fields->sortBy(function ($field) use ($orderMap) {
            $label = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
            return $orderMap[$label] ?? 999;
        })->values();

        return view('links.public', compact('form'));
    }

    public function edit($id)
    {
        $form = Form::with('fields')->findOrFail($id);

        $mobilizationFields = [
            // Personal
            ['label' => 'Full Name', 'type' => 'text'],
            ['label' => 'Email', 'type' => 'email'],
            ['label' => 'Mobile', 'type' => 'text'],
            ['label' => 'WhatsApp Number', 'type' => 'text'],
            ['label' => 'Highest Qualification', 'type' => 'text'],
            ['label' => 'Date of Birth', 'type' => 'date'],
            ['label' => 'Gender', 'type' => 'select'],
            ['label' => 'Marital Status', 'type' => 'select'],
            ['label' => 'State', 'type' => 'select'],
            ['label' => 'City', 'type' => 'select'],
            ['label' => 'Address', 'type' => 'text'],
            ['label' => 'Identification Remark', 'type' => 'text'],
            ['label' => 'Languages', 'type' => 'text'],

            // Salary & Bank
            ['label' => 'Current Salary', 'type' => 'text'],
            ['label' => 'Preferred Salary', 'type' => 'text'],
            ['label' => 'Bank Account Number', 'type' => 'text'],
            ['label' => 'IFSC Code', 'type' => 'text'],

            // Experience
            ['label' => 'Organization', 'type' => 'text'],
            ['label' => 'Designation', 'type' => 'text'],
            ['label' => 'Duration', 'type' => 'text'],
            ['label' => 'Role Category', 'type' => 'select'],
            ['label' => 'Sub Role', 'type' => 'select'],

            // Documents - ID Proof
            ['label' => 'PAN Number', 'type' => 'text'],
            ['label' => 'PAN Card', 'type' => 'file'],
            ['label' => 'Aadhar Number', 'type' => 'text'],
            ['label' => 'Aadhar Front', 'type' => 'file'],
            ['label' => 'Aadhar Back', 'type' => 'file'],

            // Documents - Photos
            ['label' => 'Photo', 'type' => 'file'],
            ['label' => 'Signature', 'type' => 'file'],
            ['label' => 'Passbook', 'type' => 'file'],
            ['label' => 'Driving License', 'type' => 'file'],
            ['label' => 'Experience Letter', 'type' => 'file'],

            // Documents - Education
            ['label' => '10th Passing Year', 'type' => 'text'],
            ['label' => '10th Marksheet', 'type' => 'file'],
            ['label' => '12th Passing Year', 'type' => 'text'],
            ['label' => '12th Marksheet', 'type' => 'file'],
            ['label' => 'Graduation Passing Year', 'type' => 'text'],
            ['label' => 'Graduation Marksheet', 'type' => 'file'],
            ['label' => 'Post Graduation Passing Year', 'type' => 'text'],
            ['label' => 'Post Graduation Marksheet', 'type' => 'file'],
        ];

        return view('links.edit', compact('form','mobilizationFields'));
    }

    public function update(Request $request, $id)
    {
        $form = Form::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable',
            'selected_fields' => 'nullable|string'
        ]);

        $instructions = $request->input('instructions', []);
        if (is_array($instructions)) {
            $instructions = array_values(array_filter($instructions, fn($i) => trim($i) !== ''));
        }

        $form->update([
            'title' => $request->title,
            'description' => $request->description,
            'instructions' => json_encode($instructions),
        ]);

        if ($request->filled('selected_fields')) {
            FormField::where('form_id', $form->id)->delete();

            $fields = json_decode($request->selected_fields, true);

            if (is_array($fields)) {
                foreach ($fields as $field) {
                    if (empty($field['label']) || empty($field['type'])) {
                        continue;
                    }
                    
                    $options = null;
                    if ($field['type'] === 'select') {
                        $options = $this->getSelectOptions($field['label']);
                        Log::info('Updating select field: ' . $field['label'], ['options' => $options]);
                        
                        if (!empty($options)) {
                            $options = json_encode($options);
                        }
                    }
                    
                    FormField::create([
                        'form_id' => $form->id,
                        'label' => $field['label'],
                        'type' => $field['type'],
                        'options' => $options,
                        'is_required' => isset($field['is_required']) && $field['is_required'] ? 1 : 0
                    ]);
                }
            }
        }

        return redirect()->route('links.index')
            ->with('success', 'Form Updated Successfully');
    }
public function destroy($id)
{
    $form = Form::withCount('responses')->findOrFail($id);

    if ($form->responses_count > 0) {
        return redirect()->back()
            ->with('error', 'Cannot delete form. Responses already exist.');
    }

    $form->delete();

    return redirect()->route('links.index')
        ->with('success', 'Form deleted successfully.');
}

public function deleteResponse($id)
{
    $response = FormResponse::with('mobilization')->findOrFail($id);
    $mobilization = $response->mobilization;
    $response->values()->delete();
    $response->delete();

    if ($mobilization && $mobilization->formResponses()->count() == 0) {
        $mobilization->delete();
    }

    return back()->with('success', 'Response and associated mobilization deleted successfully');
}


    private function getSelectOptions($label)
{
    $label = strtolower(trim($label));
    
    if ($label === 'gender') {
        return ['Male', 'Female', 'Other'];
    }
    
    if ($label === 'marital status') {
        return ['Single', 'Married', 'Divorced', 'Widowed'];
    }
    
    if ($label === 'state') {
        $states = Mobilization::whereNotNull('state')
            ->distinct()
            ->pluck('state')
            ->filter()
            ->values()
            ->toArray();
        
        return !empty($states) ? $states : ['Chhattisgarh', 'Maharashtra', 'Delhi', 'Uttar Pradesh'];
    }
    
    if ($label === 'city') {
        $cities = Mobilization::whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->filter()
            ->values()
            ->toArray();
        
        return !empty($cities) ? $cities : ['Raipur', 'Bilaspur', 'Durg', 'Mumbai', 'Delhi'];
    }
    
    if ($label === 'role category') {
        $roles = Role::pluck('name')->toArray();
        return !empty($roles) ? $roles : ['Sales', 'Field Work', 'Office Work'];
    }
    
    if ($label === 'sub role') {
        $subRoles = SubRole::pluck('name')->toArray();
        return !empty($subRoles) ? $subRoles : ['Executive', 'Manager', 'Assistant'];
    }
    
    return [];
}
public function responses($id)
{
    $form = Form::with([
        'fields',
        'responses.values.field'
    ])->findOrFail($id);

    return view('links.responses', compact('form'));
}

public function viewResponse($id)
{
    $response = FormResponse::with([
        'values.field',
        'form.fields',
        'mobilization'
    ])->findOrFail($id);

    return view('links.response-view', [
        'response' => $response,
        'form' => $response->form,
        'mobilization' => $response->mobilization
    ]);
}

public function upload(Request $request)
{
    $file = $request->file('file');
    $path = $file->store('uploads', 'public');
    $fullPath = storage_path('app/public/' . $path);
    $mime = $file->getMimeType();
    $processing = $request->processing ?? [];

    if (in_array('compress_pdf', $processing) && str_contains($mime, 'pdf')) {
        $compressedPath = $this->compressPdf($fullPath);
        return response()->json(['file' => $compressedPath]);
    }

    if (in_array('remove_bg', $processing) && str_contains($mime, 'image')) {
        $outputPath = $this->removeBackground($fullPath);
        return response()->json(['file' => $outputPath]);
    }

    return response()->json(['file' => $path]);
}

    private function compressPdf($filePath)
{
    $apiKey = env('PDFCO_API_KEY');
    
    if (empty($apiKey)) {
        \Log::error('PDFCO_API_KEY is not set in .env file');
        throw new \Exception('PDF compression API key not configured');
    }
    
    \Log::info('Starting PDF compression', ['file' => $filePath]);
    
    $response = Http::withHeaders([
        'x-api-key' => $apiKey
    ])->attach(
        'file', file_get_contents($filePath), 'file.pdf'
    )->post('https://api.pdf.co/v1/pdf/optimize', [
        'compression' => 'max'
    ]);

    $data = $response->json();

    if (!isset($data['url'])) {
        \Log::error('PDF compression failed', ['response' => $data]);
        throw new \Exception('PDF compression failed: ' . json_encode($data));
    }

    $compressed = Http::get($data['url'])->body();
    $newPath = 'uploads/mobilization_docs/compressed_' . time() . '.pdf';
    Storage::disk('public')->put($newPath, $compressed);
    
    \Log::info('PDF compression successful', ['output' => $newPath]);

    return $newPath;
}

private function removeBackground($filePath)
{
    $apiKey = env('REMOVE_BG_API_KEY');
    
    if (empty($apiKey)) {
        \Log::error('REMOVE_BG_API_KEY is not set in .env file');
        throw new \Exception('Background removal API key not configured');
    }
    
    \Log::info('Starting background removal', ['file' => $filePath, 'api_key_exists' => !empty($apiKey)]);
    
    $response = Http::withHeaders([
        'X-Api-Key' => $apiKey,
    ])->attach(
        'image_file', file_get_contents($filePath), 'image.png'
    )->post('https://api.remove.bg/v1.0/removebg', [
        'size' => 'auto'
    ]);

    if ($response->failed()) {
        \Log::error('Background removal API failed', [
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json()
        ]);
        throw new \Exception('Background removal failed: ' . $response->body());
    }

    $outputPath = 'uploads/mobilization_docs/nobg_' . time() . '.png';
    Storage::disk('public')->put($outputPath, $response->body());
    
    \Log::info('Background removal successful', ['output' => $outputPath]);

    return $outputPath;
}

public function bulkDelete(Request $request)
{
    $data = $request->validate([
        'ids' => 'required|array|min:1',
        'ids.*' => 'integer|exists:form_responses,id',
    ]);

    $ids = array_unique(array_filter($data['ids'], fn($item) => is_numeric($item)));

    if (empty($ids)) {
        return back()->with('error', 'No valid responses selected');
    }

    \DB::transaction(function () use ($ids) {
        $responses = FormResponse::with('mobilization')
            ->whereIn('id', $ids)
            ->get();

        foreach ($responses as $response) {
            $mobilization = $response->mobilization;
            FormResponseValue::where('response_id', $response->id)->delete();
            $response->delete();

            if ($mobilization && $mobilization->formResponses()->count() == 0) {
                $mobilization->delete();
            }
        }
    });

    return back()->with('success', 'Selected responses deleted successfully');
}

    public function showPrefilled($formId, $mobilizationId)
    {
        $form = Form::where('slug', $formId)
            ->with('fields')
            ->firstOrFail();
        
        $mobilization = Mobilization::findOrFail($mobilizationId);
        
        $today = now()->toDateString();
        if (
            ($form->valid_from && $today < $form->valid_from) ||
            ($form->valid_to && $today > $form->valid_to)
        ) {
            abort(403, 'This form is not active right now.');
        }
        
        $prefilledData = $this->mapMobilizationToForm($mobilization, $form->fields);
        
        return view('links.prefilled', compact('form', 'mobilization', 'prefilledData'));
    }

private function mapMobilizationToForm($mobilization, $fields)
{
    $mapping = [
        'full name' => 'name',
        'email' => 'email',
        'mobile' => 'mobile',
        'whatsapp number' => 'whatsapp_number',
        'highest qualification' => 'highest_qualification',
        'date of birth' => 'dob',
        'gender' => 'gender',
        'marital status' => 'marital_status',
        'state' => 'state',
        'city' => 'city',
        'address' => 'location',
        'pan number' => 'pan_number',
        'aadhar number' => 'aadhar_number',
        'identification remark' => 'identification_remark',
        'languages' => 'languages',
        'current salary' => 'current_salary',
        'preferred salary' => 'preferred_salary',
        'bank account number' => 'bank_account_number',
        'ifsc code' => 'ifsc_code',
        '10th passing year' => 'tenth_passing_year',
        '12th passing year' => 'twelfth_passing_year',
        'graduation passing year' => 'graduation_passing_year',
        'post graduation passing year' => 'post_graduation_passing_year',
        'pan card' => 'documents.pan_card',
        'aadhar front' => 'documents.aadhar_front',
        'aadhar back' => 'documents.aadhar_back',
        'photo' => 'documents.photo',
        'signature' => 'documents.signature',
        'passbook photo' => 'documents.passbook_photo',
        'passbook' => 'documents.passbook_photo',
        'driving license' => 'documents.driving_license',
        'experience letter' => 'documents.experience_letter',
        '10th marksheet' => 'education.tenth_marksheet',
        'tenth marksheet' => 'education.tenth_marksheet',
        '12th marksheet' => 'education.twelfth_marksheet',
        'twelfth marksheet' => 'education.twelfth_marksheet',
        'graduation marksheet' => 'education.graduation_marksheet',
        'post graduation marksheet' => 'education.post_graduation_marksheet',
    ];
    
    $prefilled = [];
    foreach ($fields as $field) {
        $label = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
        if (isset($mapping[$label])) {
            $fieldName = $mapping[$label];
            $prefilled[$field->id] = data_get($mobilization, $fieldName, '');
        } else {
            $prefilled[$field->id] = '';
        }
    }
    
    return $prefilled;
}

public function submitPrefilled(Request $request, $formSlug, $mobilizationId)
{
    $form = Form::where('slug', $formSlug)->with('fields')->firstOrFail();
    $mobilization = Mobilization::findOrFail($mobilizationId);
    
    $rules = [];
    foreach ($form->fields as $field) {
        $rule = ['required'];
        if ($field->type === 'email') {
            $rule[] = 'email';
        }
        if ($field->type === 'file') {
            $rule[] = 'file';
            $rule[] = 'mimes:jpg,jpeg,png,pdf';
            $rule[] = 'max:7168';
        }
        $rules['field_' . $field->id] = $rule;
    }
    $request->validate($rules);
    
    // Field mapping for prefilled forms
    $fieldMap = [
        'full name' => 'name',
        'father name' => 'father_name',
        'mother name' => 'mother_name',
        'email' => 'email',
        'mobile' => 'mobile',
        'whatsapp number' => 'whatsapp_number',
        'highest qualification' => 'highest_qualification',
        'date of birth' => 'dob',
        'gender' => 'gender',
        'marital status' => 'marital_status',
        'state' => 'state',
        'city' => 'city',
        'district' => 'city',
        'address' => 'location',
        'pincode' => 'pincode',
        'category' => 'category',
        'religion' => 'religion',
        'family members' => 'family_members',
        'dependents' => 'dependents',
        'has vehicle' => 'has_vehicle',
        'vehicle details' => 'vehicle_details',
        'has smartphone' => 'has_smartphone',
        'pan number' => 'pan_number',
        'aadhar number' => 'aadhar_number',
        'identification remark' => 'identification_remark',
        'languages' => 'languages',
        'relocation' => 'relocation',
        'current salary' => 'current_salary',
        'preferred salary' => 'preferred_salary',
        'bank account number' => 'bank_account_number',
        'ifsc code' => 'ifsc_code',
        '10th passing year' => 'tenth_passing_year',
        '12th passing year' => 'twelfth_passing_year',
        'graduation passing year' => 'graduation_passing_year',
        'post graduation passing year' => 'post_graduation_passing_year',
    ];
    
    $documentMapping = [
        'pan card' => 'pan_card',
        'aadhar front' => 'aadhar_front',
        'aadhar back' => 'aadhar_back',
        'photo' => 'photo',
        'signature' => 'signature',
        'passbook photo' => 'passbook_photo',
        'passbook' => 'passbook_photo',
        '10th marksheet' => 'tenth_marksheet',
        'tenth marksheet' => 'tenth_marksheet',
        '12th marksheet' => 'twelfth_marksheet',
        'twelfth marksheet' => 'twelfth_marksheet',
        'graduation marksheet' => 'graduation_marksheet',
        'post graduation marksheet' => 'post_graduation_marksheet',
        'driving license' => 'driving_license',
        'experience letter' => 'experience_letter',
    ];
    
    $response = FormResponse::create([
        'form_id' => $form->id,
        'mobilization_id' => $mobilization->id,
        'uuid' => (string) \Str::uuid(),
    ]);
    
    $mobilizationUpdateData = [];
    
    foreach ($form->fields as $field) {
        $inputName = 'field_' . $field->id;
        $label = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
        
        if ($field->type === 'file' && $request->hasFile($inputName)) {
            $file = $request->file($inputName);
            $path = $file->store('uploads/forms', 'public');
            
            FormResponseValue::create([
                'response_id' => $response->id,
                'field_id' => $field->id,
                'file_url' => $path,
                'file_size' => $file->getSize(),
                'file_type' => $file->getMimeType(),
                'file_extension' => $file->getClientOriginalExtension(),
            ]);
            
            // Save document to mobilization
            if (isset($documentMapping[$label])) {
                $mobilizationUpdateData[$documentMapping[$label]] = $path;
            }
        } else {
            $fieldValue = $request->input($inputName);
            FormResponseValue::create([
                'response_id' => $response->id,
                'field_id' => $field->id,
                'value' => $fieldValue,
            ]);
            
            // Save text field to mobilization
            if (isset($fieldMap[$label])) {
                $mobilizationUpdateData[$fieldMap[$label]] = $fieldValue;
            }
        }
    }
    
    // Handle bank details separately
    if ($mobilization) {
        $bankData = [];
        if (isset($mobilizationUpdateData['bank_account_number'])) {
            $bankData['bank_account_number'] = $mobilizationUpdateData['bank_account_number'];
            unset($mobilizationUpdateData['bank_account_number']);
        }
        if (isset($mobilizationUpdateData['ifsc_code'])) {
            $bankData['ifsc_code'] = $mobilizationUpdateData['ifsc_code'];
            unset($mobilizationUpdateData['ifsc_code']);
        }
        
        if (!empty($bankData)) {
            $mobilization->bank()->updateOrCreate(
                ['mobilization_id' => $mobilization->id],
                $bankData
            );
        }
    }

    // Handle references
    $references = [];
    foreach ($form->fields as $field) {
        $label = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
        if (strpos($label, 'reference') !== false) {
            $inputName = 'field_' . $field->id;
            $fieldValue = $request->input($inputName);
            if (is_array($fieldValue)) {
                $references = array_merge($references, $fieldValue);
            }
        }
    }
    if (!empty($references)) {
        $mobilization->references()->delete();
        foreach ($references as $ref) {
            if (!empty($ref['name']) || !empty($ref['contact'])) {
                MobilizationReference::create([
                    'mobilization_id' => $mobilization->id,
                    'reference_person' => $ref['name'] ?? '',
                    'reference_mobile' => $ref['contact'] ?? '',
                    'reference_email' => $ref['email'] ?? '',
                    'reference_designation' => $ref['designation'] ?? '',
                    'reference_organization' => $ref['organization'] ?? '',
                    'reference_detail' => $ref['relation'] ?? '',
                ]);
            }
        }
    }

    // Handle experiences
    $experiences = [];
    foreach ($form->fields as $field) {
        $label = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
        if (strpos($label, 'experience') !== false || strpos($label, 'employment') !== false) {
            $inputName = 'field_' . $field->id;
            $fieldValue = $request->input($inputName);
            if (is_array($fieldValue)) {
                $experiences = array_merge($experiences, $fieldValue);
            }
        }
    }
    if (!empty($experiences)) {
        $mobilization->experiences()->delete();
        foreach ($experiences as $exp) {
            if (!empty($exp['company']) || !empty($exp['designation'])) {
                MobilizationExperience::create([
                    'mobilization_id' => $mobilization->id,
                    'organization' => $exp['company'] ?? '',
                    'designation' => $exp['designation'] ?? '',
                    'duration' => $exp['duration'] ?? '',
                    'reason_for_leaving' => $exp['reason_for_leaving'] ?? '',
                ]);
            }
        }
    }
    
    // Update mobilization with all submitted data
    if (!empty($mobilizationUpdateData)) {
        $mobilization->update($mobilizationUpdateData);
    }
    
    return redirect()->route('mobilizations.index')
        ->with('success', 'Form submitted successfully for ' . $mobilization->name);
}


    public function showPrefilledByToken($token)
{ 
    $prefillToken = FormPrefillToken::where('token', $token)
        ->with(['mobilization', 'form.fields'])
        ->firstOrFail();
    
    // Check if token is valid
    if (!$prefillToken->isValid()) {
        abort(403, 'This link has expired or has already been used.');
    }
    
    $form = $prefillToken->form;
    $mobilization = $prefillToken->mobilization;
    
    // Parse prefill data
    $prefillData = $prefillToken->prefill_data;
    if (is_string($prefillData)) {
        $prefillData = json_decode($prefillData, true);
    }
    
     // Check if form is active
    $today = now()->toDateString();
    if (($form->valid_from && $today < $form->valid_from) ||
        ($form->valid_to && $today > $form->valid_to)) {
        abort(403, 'This form is not active right now.');
    }
    
    // 🔥 FIX: Ensure each select field has proper options
    foreach ($form->fields as $field) {
        $normalizedLabel = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
        
        // Process gender, marital status, role category, sub role regardless of field type
        if (in_array($normalizedLabel, ['gender', 'marital status', 'role category', 'sub role']) || $field->type == 'select') {
            // Decode options if it's a JSON string
            if (is_string($field->options) && !empty($field->options)) {
                $decoded = json_decode($field->options, true);
                if (is_array($decoded) && json_last_error() === JSON_ERROR_NONE) {
                    $field->options = $decoded;
                }
            }
            
            // If still empty or null, set default options
            if (empty($field->options) || !is_array($field->options) || count($field->options) == 0) {
                if ($normalizedLabel === 'gender') {
                    $field->options = ['Male', 'Female', 'Other'];
                } elseif ($normalizedLabel === 'marital status') {
                    $field->options = ['Single', 'Married', 'Divorced', 'Widowed'];
                } elseif ($normalizedLabel === 'role category') {
                    $field->options = ['Sales', 'Field Work', 'Office Work'];
                } elseif ($normalizedLabel === 'sub role') {
                    $field->options = ['Executive', 'Manager', 'Assistant'];
                }
                Log::info('Setting options for ' . $field->label, ['options' => $field->options]);
            }
            
            // Ensure options is an array
            if (!is_array($field->options)) {
                $field->options = [];
            }
        }
    }
    
    // Prepare prefilled fields
    $prefilledFields = $this->mapPrefillDataToFields($prefillData, $form->fields);
    
    // Get states and cities from API for dynamic loading
    $states = [];
    try {
        $response = Http::withHeaders([
            'X-CSCAPI-KEY' => config('services.csc.key')
        ])->get("https://api.countrystatecity.in/v1/countries/IN/states");
        
        if ($response->successful()) {
            $states = $response->json();
        }
    } catch (\Exception $e) {
        Log::error('Failed to fetch states: ' . $e->getMessage());
        // Fallback states
        $states = collect($response->json())->map(function ($s) {
    return [
        'name' => $s['name'],
        'iso2' => $s['iso2']
    ];
    })->toArray();
        }
        
        return view('links.prefilled-token', compact('form', 'mobilization', 'prefilledFields', 'token', 'states'));
}

    private function mapPrefillDataToFields($prefillData, $fields)
    {
        $mapping = [
            'name' => ['full name', 'candidate name'],
            'email' => ['email'],
            'mobile' => ['mobile', 'phone'],
            'whatsapp_number' => ['whatsapp number', 'whatsapp'],
            'highest_qualification' => ['highest qualification', 'qualification'],
            'dob' => ['date of birth', 'dob'],
            'gender' => ['gender'],
            'marital_status' => ['marital status'],
            'state' => ['state'],
            'city' => ['city'],
            'location' => ['address', 'location'],
            'aadhar_number' => ['aadhar number'],
            'pan_number' => ['pan number'],
            'identification_remark' => ['identification remark'],
            'languages' => ['languages'],
            'current_salary' => ['current salary'],
            'preferred_salary' => ['preferred salary'],
            'bank_account_number' => ['bank account number'],
            'ifsc_code' => ['ifsc code'],
            'tenth_passing_year' => ['10th passing year'],
            'twelfth_passing_year' => ['12th passing year'],
            'graduation_passing_year' => ['graduation passing year'],
            'post_graduation_passing_year' => ['post graduation passing year'],
        ];
        
        $prefilled = [];
        foreach ($fields as $field) {
            $label = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
            foreach ($mapping as $fieldName => $labels) {
                if (in_array($label, $labels) && array_key_exists($fieldName, $prefillData)) {
                    $prefilled[$field->id] = $prefillData[$fieldName];
                    break;
                }
            }
        }
        
        return $prefilled;
    }

    public function submitPrefilledByToken(Request $request, $token)
    {
        $prefillToken = FormPrefillToken::where('token', $token)
            ->with(['form.fields', 'mobilization'])
            ->firstOrFail();
        
     
        if (!$prefillToken->isValid()) {
            return back()->with('error', 'This link has expired or has already been used.');
        }
        
        $form = $prefillToken->form;
        $mobilization = $prefillToken->mobilization;
        $tokenValue = $prefillToken->token;
    
        $rules = [];
        foreach ($form->fields as $field) {
            $rule = $field->required ? ['required'] : ['nullable'];
            
            if ($field->type === 'email') {
                $rule[] = 'email';
            }
            if ($field->type === 'file') {
                $rule[] = 'file';
                $rule[] = 'mimes:jpg,jpeg,png,pdf';
                $rule[] = 'max:7168';
            }
            $rules['field_' . $field->id] = $rule;
        }
        
        $validated = $request->validate($rules);
        
        \DB::transaction(function () use ($request, $form, $mobilization, $prefillToken, $tokenValue) {
           
            $response = FormResponse::create([
                'form_id' => $form->id,
                'mobilization_id' => $mobilization->id,
                'uuid' => (string) \Str::uuid(),
                'submitted_via_token' => $tokenValue
            ]);
        
            $documentMapping = [
                'pan card' => 'pan_card',
                'aadhar front' => 'aadhar_front',
                'aadhar back' => 'aadhar_back',
                'photo' => 'photo',
                'signature' => 'signature',
                'passbook photo' => 'passbook_photo',
                'passbook' => 'passbook_photo',
                '10th marksheet' => 'tenth_marksheet',
                'tenth marksheet' => 'tenth_marksheet',
                '12th marksheet' => 'twelfth_marksheet',
                'twelfth marksheet' => 'twelfth_marksheet',
                'graduation marksheet' => 'graduation_marksheet',
                'post graduation marksheet' => 'post_graduation_marksheet',
                'driving license' => 'driving_license',
                'experience letter' => 'experience_letter',
            ];
            
            $fieldMap = [
                'full name' => 'name',
                'email' => 'email',
                'mobile' => 'mobile',
                'whatsapp number' => 'whatsapp_number',
                'highest qualification' => 'highest_qualification',
                'date of birth' => 'dob',
                'gender' => 'gender',
                'marital status' => 'marital_status',
                'state' => 'state',
                'city' => 'city',
                'address' => 'location',
                'pan number' => 'pan_number',
                'aadhar number' => 'aadhar_number',
                'identification remark' => 'identification_remark',
                'languages' => 'languages',
                'current salary' => 'current_salary',
                'preferred salary' => 'preferred_salary',
                'bank account number' => 'bank_account_number',
                'ifsc code' => 'ifsc_code',
                '10th passing year' => 'tenth_passing_year',
                '12th passing year' => 'twelfth_passing_year',
                'graduation passing year' => 'graduation_passing_year',
                'post graduation passing year' => 'post_graduation_passing_year',
            ];
            
            $mobilizationUpdateData = [];
            
            foreach ($form->fields as $field) {
                $inputName = 'field_' . $field->id;
                $label = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
                
                if ($field->type === 'file' && $request->hasFile($inputName)) {
                    $file = $request->file($inputName);
                    $path = $file->store('uploads/mobilization_docs', 'public');
                    $fullPath = storage_path('app/public/' . $path);
                    $mime = $file->getMimeType();
                    $finalPath = $path;
                
                    if (str_contains($mime, 'pdf')) {
                        try {
                            $compressedPath = $this->compressPdf($fullPath);
                            if ($compressedPath) {
                              
                                Storage::disk('public')->delete($path);
                                $finalPath = $compressedPath;
                            }
                        } catch (\Exception $e) {
                        
                            \Log::warning('PDF compression failed: ' . $e->getMessage());
                        }
                    }
                    
        
                    $isPhotoOrSignature =
                        str_contains($label, 'photo') ||
                        str_contains($label, 'signature') ||
                        str_contains($label, 'aadhar') ||
                        str_contains($label, 'pan') ||
                        str_contains($label, 'marksheet');
                    \Log::info('File upload debug', ['label' => $label, 'mime' => $mime, 'isPhotoOrSignature' => $isPhotoOrSignature]);
                    
                    if (str_contains($mime, 'image') && $isPhotoOrSignature) {
                        try {
                            \Log::info('Attempting background removal', ['label' => $label, 'file' => $fullPath]);
                            $nobgPath = $this->removeBackground($fullPath);
                            if ($nobgPath) {
                                // Delete original and use processed
                                Storage::disk('public')->delete($path);
                                $finalPath = $nobgPath;
                            }
                        } catch (\Exception $e) {
                            // Log error but continue with original file
                            \Log::warning('Background removal failed: ' . $e->getMessage());
                        }
                    }
                    
                    // Save to form_response_values
                    FormResponseValue::create([
                        'response_id' => $response->id,
                        'field_id' => $field->id,
                        'file_url' => $finalPath,
                        'file_size' => $file->getSize(),
                        'file_type' => $file->getMimeType(),
                        'file_extension' => $file->getClientOriginalExtension(),
                    ]);
                    
                    // Also save to mobilization table if it's a document field
                    if (isset($documentMapping[$label])) {
                        $mobilizationUpdateData[$documentMapping[$label]] = $finalPath;
                    }
                } else {
                    $value = $request->input($inputName);
                    
                    // Save to form_response_values
                    FormResponseValue::create([
                        'response_id' => $response->id,
                        'field_id' => $field->id,
                        'value' => $value,
                    ]);
                    
                    // Also save text fields to mobilization
                    if (isset($fieldMap[$label])) {
                        $mobilizationUpdateData[$fieldMap[$label]] = $value;
                    }
                }
            }
            
            // Calculate age if DOB is provided
            if (isset($mobilizationUpdateData['dob'])) {
                $mobilizationUpdateData['age'] = \Carbon\Carbon::parse($mobilizationUpdateData['dob'])->age;
            }
            
            // Handle bank details separately
            if ($mobilization) {
                $bankData = [];
                if (isset($mobilizationUpdateData['bank_account_number'])) {
                    $bankData['bank_account_number'] = $mobilizationUpdateData['bank_account_number'];
                    unset($mobilizationUpdateData['bank_account_number']);
                }
                if (isset($mobilizationUpdateData['ifsc_code'])) {
                    $bankData['ifsc_code'] = $mobilizationUpdateData['ifsc_code'];
                    unset($mobilizationUpdateData['ifsc_code']);
                }
                
                if (!empty($bankData)) {
                    $mobilization->bank()->updateOrCreate(
                        ['mobilization_id' => $mobilization->id],
                        $bankData
                    );
                }
            }

            // Handle references
            $references = [];
            foreach ($form->fields as $field) {
                $label = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
                if (strpos($label, 'reference') !== false) {
                    $inputName = 'field_' . $field->id;
                    $fieldValue = $request->input($inputName);
                    if (is_array($fieldValue)) {
                        $references = array_merge($references, $fieldValue);
                    }
                }
            }
            if (!empty($references)) {
                $mobilization->references()->delete();
                foreach ($references as $ref) {
                    if (!empty($ref['name']) || !empty($ref['contact'])) {
                        MobilizationReference::create([
                            'mobilization_id' => $mobilization->id,
                            'reference_person' => $ref['name'] ?? '',
                            'reference_mobile' => $ref['contact'] ?? '',
                            'reference_email' => $ref['email'] ?? '',
                            'reference_designation' => $ref['designation'] ?? '',
                            'reference_organization' => $ref['organization'] ?? '',
                            'reference_detail' => $ref['relation'] ?? '',
                        ]);
                    }
                }
            }

            // Handle experiences
            $experiences = [];
            foreach ($form->fields as $field) {
                $label = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
                if (strpos($label, 'experience') !== false || strpos($label, 'employment') !== false) {
                    $inputName = 'field_' . $field->id;
                    $fieldValue = $request->input($inputName);
                    if (is_array($fieldValue)) {
                        $experiences = array_merge($experiences, $fieldValue);
                    }
                }
            }
            if (!empty($experiences)) {
                $mobilization->experiences()->delete();
                foreach ($experiences as $exp) {
                    if (!empty($exp['company']) || !empty($exp['designation'])) {
                        MobilizationExperience::create([
                            'mobilization_id' => $mobilization->id,
                            'organization' => $exp['company'] ?? '',
                            'designation' => $exp['designation'] ?? '',
                            'duration' => $exp['duration'] ?? '',
                            'reason_for_leaving' => $exp['reason_for_leaving'] ?? '',
                        ]);
                    }
                }
            }
            
            // Update mobilization with all collected data
            if (!empty($mobilizationUpdateData)) {
                $mobilization->update($mobilizationUpdateData);
            }
            
            // Mark token as used
            $prefillToken->update(['used_at' => now()]);
        });
        
        return redirect()->route('forms.prefilled.thankyou', ['token' => $tokenValue])
            ->with('success', 'Form submitted successfully!');
    }

    private function updateMobilizationFromForm($mobilization, $request, $form, $fileData = [])
    {
        // Text field mapping
        $mapping = [
            'full name' => 'name',
            'email' => 'email',
            'mobile' => 'mobile',
            'whatsapp number' => 'whatsapp_number',
            'highest qualification' => 'highest_qualification',
            'date of birth' => 'dob',
            'gender' => 'gender',
            'marital status' => 'marital_status',
            'state' => 'state',
            'city' => 'city',
            'address' => 'location',
            'pan number' => 'pan_number',
            'aadhar number' => 'aadhar_number',
            'identification remark' => 'identification_remark',
            'languages' => 'languages',
            'current salary' => 'current_salary',
            'preferred salary' => 'preferred_salary',
            'bank account number' => 'bank_account_number',
            'ifsc code' => 'ifsc_code',
            '10th passing year' => 'tenth_passing_year',
            '12th passing year' => 'twelfth_passing_year',
            'graduation passing year' => 'graduation_passing_year',
            'post graduation passing year' => 'post_graduation_passing_year',
        ];
        
        $updateData = $fileData; // Start with file data passed from submit function
        
        foreach ($form->fields as $field) {
            $label = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
            $inputName = 'field_' . $field->id;
            
            // Handle text fields only (files are already processed)
            if (isset($mapping[$label])) {
                $fieldName = $mapping[$label];
                $value = $request->input($inputName);
                
                if (!empty($value) && $value != $mobilization->$fieldName) {
                    $updateData[$fieldName] = $value;
                }
            }
        }
        
        if (isset($updateData['dob'])) {
            $updateData['age'] = \Carbon\Carbon::parse($updateData['dob'])->age;
        }
        
        if (!empty($updateData)) {
            $mobilization->update($updateData);
        }
    }
}