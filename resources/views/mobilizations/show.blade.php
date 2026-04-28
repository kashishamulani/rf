@extends('layouts.app')

@section('content')

<style>
.page-wrap {
    max-width: 1150px;
    margin: auto;
    padding: 24px;
}

/* CARD STYLE (same feel as form) */
.main-card {
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(14px);
    border-radius: 18px;
    padding: 32px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
}

/* HEADER */
.header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.back-btn {
    padding: 8px 16px;
    border-radius: 10px;
    background: #f3f4f6;
    text-decoration: none;
    font-weight: 600;
    color: #374151;
    transition: .2s;
}

.back-btn:hover {
    background: linear-gradient(135deg, #6366f1, #ec4899);
    color: #fff;
}

/* SECTION TITLE */
.section-title {
    font-size: 20px;
    font-weight: 700;
    color: #4f46e5;
    margin: 26px 0 14px;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 16px;
}

/* FIELD */
.field label {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 4px;
}

.field p {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    padding: 10px 12px;
    border-radius: 10px;
    margin: 0;
}

/* EXPERIENCE CARD */
.exp-card {
    background: rgba(255, 255, 255, 0.7);
    border-left: 6px solid #6366f1;
    border-radius: 14px;
    padding: 16px;
    box-shadow: 0 8px 20px rgba(99, 102, 241, .15);
    margin-bottom: 14px;
}

/* TAGS */
.tag-wrap {
    border: 1px solid #e5e7eb;
    padding: 8px;
    border-radius: 10px;
    background: #f9fafb;
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.tag {
    background: #6366f1;
    color: #fff;
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 20px;
}

/* DOC CARD */
.doc-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 16px;
    text-align: center;
    box-shadow: 0 4px 14px rgba(0, 0, 0, .06);
}

.doc-btn {
    display: inline-block;
    margin-top: 6px;
    padding: 6px 12px;
    background: #eef2ff;
    border-radius: 8px;
    color: #4338ca;
    text-decoration: none;
    font-size: 13px;
}
</style>

<div class="page-wrap">
    <div class="main-card">

        <div class="header-flex">
            <h2>Mobilization Details</h2>
            <a href="{{ route('mobilizations.index') }}" class="back-btn">← Back</a>
        </div>

        {{-- IDENTIFICATION REMARK --}}
        @if($mobilization->identification_remark)
        <div class="section-title">Identification Remark</div>
        <div class="field">
            <p>{{ $mobilization->identification_remark }}</p>
        </div>
        @endif

        {{-- PERSONAL DETAILS --}}
        <div class="section-title">Personal Details</div>
        <div class="grid">
            <div class="field"><label>Name</label>
                <p>{{ $mobilization->name ?? '-' }}</p>
            </div>
            <div class="field"><label>Father Name</label>
                <p>{{ $mobilization->father_name ?? '-' }}</p>
            </div>
            <div class="field"><label>Mother Name</label>
                <p>{{ $mobilization->mother_name ?? '-' }}</p>
            </div>
            <div class="field"><label>Email</label>
                <p>{{ $mobilization->email ?? '-' }}</p>
            </div>
            <div class="field"><label>Mobile</label>
                <p>{{ $mobilization->mobile ?? '-' }}</p>
            </div>
            <div class="field"><label>WhatsApp</label>
                <p>{{ $mobilization->whatsapp_number ?? '-' }}</p>
            </div>
            <div class="field"><label>Qualification</label>
                <p>{{ $mobilization->highest_qualification ?? '-' }}</p>
            </div>
            <div class="field"><label>DOB</label>
                <p>{{ $mobilization->dob ?? '-' }}</p>
            </div>
            <div class="field"><label>Age</label>
                <p>{{ $mobilization->age ?? '-' }}</p>
            </div>
            <div class="field"><label>Gender</label>
                <p>{{ $mobilization->gender ?? '-' }}</p>
            </div>
            <div class="field"><label>Marital Status</label>
                <p>{{ $mobilization->marital_status ?? '-' }}</p>
            </div>
            <div class="field"><label>Pincode</label>
                <p>{{ $mobilization->pincode ?? '-' }}</p>
            </div>
            <div class="field"><label>Category</label>
                <p>{{ $mobilization->category ?? '-' }}</p>
            </div>
            <div class="field"><label>Religion</label>
                <p>{{ $mobilization->religion ?? '-' }}</p>
            </div>
            <div class="field"><label>Family Members</label>
                <p>{{ $mobilization->family_members ?? '-' }}</p>
            </div>
            <div class="field"><label>Dependents</label>
                <p>{{ $mobilization->dependents ?? '-' }}</p>
            </div>
            <div class="field"><label>Has Vehicle?</label>
                <p>{{ $mobilization->has_vehicle == '1' ? 'Yes' : ($mobilization->has_vehicle == '0' ? 'No' : '-') }}
                </p>
            </div>
            <div class="field"><label>Vehicle Details</label>
                <p>{{ $mobilization->vehicle_details ?? '-' }}</p>
            </div>
            <div class="field"><label>Has Smartphone?</label>
                <p>{{ $mobilization->has_smartphone == '1' ? 'Yes' : ($mobilization->has_smartphone == '0' ? 'No' : '-') }}
                </p>
            </div>
            <div class="field"><label>State</label>
                <p>{{ $mobilization->state ?? '-' }}</p>
            </div>
            <div class="field"><label>City/District</label>
                <p>{{ $mobilization->district ?? $mobilization->city ?? '-' }}</p>
            </div>
            <div class="field" style="grid-column:1/-1">
                <label>Address</label>
                <p>{{ $mobilization->location ?? '-' }}</p>
            </div>
        </div>

        {{-- EXPERIENCE --}}
        <div class="section-title">Experience Details</div>

        @if($mobilization->experiences->count())
        @foreach($mobilization->experiences as $exp)
        <div class="exp-card">
            <div class="grid">
                <div class="field"><label>Organization</label>
                    <p>{{ $exp->organization ?? '-' }}</p>
                </div>
                <div class="field"><label>Designation</label>
                    <p>{{ $exp->designation ?? '-' }}</p>
                </div>
                <div class="field"><label>Duration</label>
                    <p>{{ $exp->duration ?? '-' }}</p>
                </div>
                <div class="field"><label>Role</label>
                    <p>{{ optional($exp->role)->name ?? '-' }}</p>
                </div>
                <div class="field"><label>Sub Role</label>
                    <p>{{ optional($exp->subRole)->name ?? '-' }}</p>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="exp-card">
            <p style="color:#9ca3af;">No experience records available</p>
        </div>
        @endif

        {{-- FORM SUBMISSION DETAILS --}}
        @if($mobilization->form_responses_count ?? 0)
        @php
        $latestForm = $mobilization->formResponses->first();
        @endphp

        <div class="section-title">Form Submission Summary</div>

        <div class="grid">
            <div class="field"><label>Form Submissions</label>
                <p>{{ $mobilization->form_responses_count }} completed</p>
            </div>
            <div class="field"><label>Latest Form</label>
                <p>{{ $latestForm?->form?->title ?? '—' }}</p>
            </div>
            <div class="field"><label>Last Updated</label>
                <p>{{ $latestForm?->created_at?->format('d M Y, H:i') ?? '—' }}</p>
            </div>
        </div>
        @endif

        {{-- OTHER DETAILS --}}
        <div class="section-title">Other Details</div>

        @php
        $languages = $mobilization->languages ?? [];
        if (is_string($languages)) {
        $languages = json_decode($languages, true) ?: [$languages];
        }
        @endphp

        <div class="grid">
            <div class="field">
                <label>Relocation Preference</label>
                @php
                $relocation = $mobilization->relocation ?? [];

                if (is_string($relocation)) {
                $relocation = json_decode($relocation, true) ?: [$relocation];
                }

                if (!is_array($relocation)) {
                $relocation = [$relocation];
                }
                @endphp

                <div class="tag-wrap">
                    @forelse($relocation as $rel)
                    @if($rel)
                    <span class="tag">{{ $rel }}</span>
                    @endif
                    @empty
                    <span>-</span>
                    @endforelse
                </div>
            </div>

            <div class="field">
                <label>Languages Known</label>
                <div class="tag-wrap">
                    @forelse($languages as $lang)
                    @if($lang) <span class="tag">{{ $lang }}</span> @endif
                    @empty
                    <span>-</span>
                    @endforelse
                </div>
            </div>

            <div class="field">
                <label>Current Salary</label>
                <p>{{ $mobilization->current_salary ? '₹'.number_format($mobilization->current_salary) : '-' }}</p>
            </div>

            <div class="field">
                <label>Preferred Salary</label>
                <p>{{ $mobilization->preferred_salary ? '₹'.number_format($mobilization->preferred_salary) : '-' }}</p>
            </div>
        </div>
        {{-- EDUCATION DETAILS --}}
        <div class="section-title">Education Details</div>

        <div class="grid">

            <div class="field">
                <label>10th Passing Year</label>
                <p>{{ optional($mobilization->education)->tenth_passing_year ?? '-' }}</p>
            </div>

            <div class="field">
                <label>12th Passing Year</label>
                <p>{{ optional($mobilization->education)->twelfth_passing_year ?? '-' }}</p>
            </div>

            <div class="field">
                <label>Graduation Passing Year</label>
                <p>{{ optional($mobilization->education)->graduation_passing_year ?? '-' }}</p>
            </div>

            <div class="field">
                <label>Post Graduation Passing Year</label>
                <p>{{ optional($mobilization->education)->post_graduation_passing_year ?? '-' }}</p>
            </div>

        </div>


        {{-- ID & BANK DETAILS --}}
        <div class="section-title">ID & Bank Details</div>

        <div class="grid">

            <div class="field">
                <label>PAN Number</label>
                <p>{{ optional($mobilization->documents)->pan_number ?? '-' }}</p>
            </div>

            <div class="field">
                <label>Aadhar Number</label>
                <p>{{ optional($mobilization->documents)->aadhar_number ?? '-' }}</p>
            </div>

            <div class="field">
                <label>Bank Account Number</label>
                <p>{{ optional($mobilization->bank)->bank_account_number ?? '-' }}</p>
            </div>

            <div class="field">
                <label>IFSC Code</label>
                <p>{{ optional($mobilization->bank)->ifsc_code ?? '-' }}</p>
            </div>

        </div>

        {{-- DOCUMENT UPLOAD DETAILS --}}
        <div class="section-title">Document Upload Details</div>

        <!-- PAN -->
        <div class="exp-card">
            <div class="grid">
                <div class="field"><label>PAN Number</label>
                    <p>{{ optional($mobilization->documents)->pan_number ?? '-' }}</p>
                </div>
                <div class="field"><label>PAN Card Upload</label>
                    @if(!empty(optional($mobilization->documents)->pan_card))
                    <a href="{{ route('document.view', ['path' => optional($mobilization->documents)->pan_card]) }}" target="_blank"
                        class="doc-btn">View</a>
                    @else
                    <span style="color:#9ca3af;">Not Uploaded</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- AADHAR -->
        <div class="exp-card">
            <div class="grid">
                <div class="field"><label>Aadhar Number</label>
                    <p>{{ optional($mobilization->documents)->aadhar_number ?? '-' }}</p>
                </div>
                <div class="field"><label>Aadhar Front</label>
                    @if(!empty(optional($mobilization->documents)->aadhar_front))
                    <a href="{{ route('document.view', ['path' => optional($mobilization->documents)->aadhar_front]) }}" target="_blank"
                        class="doc-btn">View</a>
                    @else
                    <span style="color:#9ca3af;">Not Uploaded</span>
                    @endif
                </div>
                <div class="field"><label>Aadhar Back</label>
                    @if(!empty(optional($mobilization->documents)->aadhar_back))
                    <a href="{{ route('document.view', ['path' => optional($mobilization->documents)->aadhar_back]) }}" target="_blank"
                        class="doc-btn">View</a>
                    @else
                    <span style="color:#9ca3af;">Not Uploaded</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- PHOTO & SIGNATURE -->
        <div class="exp-card">
            <div class="grid">
                <div class="field"><label>Photo Upload</label>
                    @if(!empty(optional($mobilization->documents)->photo))
                    <a href="{{ route('document.view', ['path' => optional($mobilization->documents)->photo]) }}" target="_blank"
                        class="doc-btn">View</a>
                    @else
                    <span style="color:#9ca3af;">Not Uploaded</span>
                    @endif
                </div>
                <div class="field"><label>Signature Upload</label>
                    @if(!empty(optional($mobilization->documents)->signature))
                    <a href="{{ route('document.view', ['path' => optional($mobilization->documents)->signature]) }}" target="_blank"
                        class="doc-btn">View</a>
                    @else
                    <span style="color:#9ca3af;">Not Uploaded</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- BANK DETAILS -->
        <div class="exp-card">
            <div class="grid">
                <div class="field"><label>Bank Account Number</label>
                    <p>{{ optional($mobilization->bank)->bank_account_number ?? '-' }}</p>
                </div>
                <div class="field"><label>IFSC Code</label>
                    <p>{{ optional($mobilization->bank)->ifsc_code ?? '-' }}</p>
                </div>
                <div class="field"><label>Passbook Upload</label>
                    @if(!empty(optional($mobilization->documents)->passbook_photo))
                    <a href="{{ route('document.view', ['path' => optional($mobilization->documents)->passbook_photo]) }}" target="_blank"
                        class="doc-btn">View</a>
                    @else
                    <span style="color:#9ca3af;">Not Uploaded</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- 10TH -->
        <div class="exp-card">
            <div class="grid">
                <div class="field"><label>10th Passing Year</label>
                    <p>{{ $mobilization->tenth_passing_year ?? '-' }}</p>
                </div>
                <div class="field"><label>10th Marksheet</label>
                    @if(!empty(optional($mobilization->education)->tenth_marksheet))
                    <a href="{{ route('document.view', ['path' => optional($mobilization->education)->tenth_marksheet]) }}"
                        target="_blank" class="doc-btn">View</a>
                    @else
                    <span style="color:#9ca3af;">Not Uploaded</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- 12TH -->
        <div class="exp-card">
            <div class="grid">
                <div class="field"><label>12th Passing Year</label>
                    <p>{{ $mobilization->twelfth_passing_year ?? '-' }}</p>
                </div>
                <div class="field"><label>12th Marksheet</label>
                    @if(!empty(optional($mobilization->education)->twelfth_marksheet))
                    <a href="{{ route('document.view', ['path' => optional($mobilization->education)->twelfth_marksheet]) }}"
                        target="_blank" class="doc-btn">View</a>
                    @else
                    <span style="color:#9ca3af;">Not Uploaded</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- GRADUATION -->
        <div class="exp-card">
            <div class="grid">
                <div class="field"><label>Graduation Passing Year</label>
                    <p>{{ $mobilization->graduation_passing_year ?? '-' }}</p>
                </div>
                <div class="field"><label>Graduation Marksheet</label>
                    @if(!empty(optional($mobilization->education)->graduation_marksheet))
                    <a href="{{ route('document.view', ['path' => optional($mobilization->education)->graduation_marksheet]) }}"
                        target="_blank" class="doc-btn">View</a>
                    @else
                    <span style="color:#9ca3af;">Not Uploaded</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- POST GRADUATION -->
        <div class="exp-card">
            <div class="grid">
                <div class="field"><label>Post Graduation Passing Year</label>
                    <p>{{ $mobilization->post_graduation_passing_year ?? '-' }}</p>
                </div>
                <div class="field"><label>Post Graduation Marksheet</label>
                    @if(!empty(optional($mobilization->education)->post_graduation_marksheet))
                    <a href="{{ route('document.view', ['path' => optional($mobilization->education)->post_graduation_marksheet]) }}"
                        target="_blank" class="doc-btn">View</a>
                    @else
                    <span style="color:#9ca3af;">Not Uploaded</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- OTHER DOCUMENTS -->
        <div class="exp-card">
            <div class="grid">
                <div class="field"><label>Driving License</label>
                    @if(!empty(optional($mobilization->documents)->driving_license))
                    <a href="{{ route('document.view', ['path' => optional($mobilization->documents)->driving_license]) }}"
                        target="_blank" class="doc-btn">View</a>
                    @else
                    <span style="color:#9ca3af;">Not Uploaded</span>
                    @endif
                </div>
                <div class="field"><label>Experience Letter</label>
                    @if(!empty(optional($mobilization->documents)->experience_letter))
                    <a href="{{ route('document.view', ['path' => optional($mobilization->documents)->experience_letter]) }}"
                        target="_blank" class="doc-btn">View</a>
                    @else
                    <span style="color:#9ca3af;">Not Uploaded</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- REFERENCES --}}
        <div class="section-title">References</div>

        @if($mobilization->references && $mobilization->references->count() > 0)
        @foreach($mobilization->references as $index => $reference)
        <div class="exp-card">
            <div class="grid">
                <div class="field"><label>Reference Person</label>
                    <p>{{ $reference->reference_person ?? '-' }}</p>
                </div>
                <div class="field"><label>Mobile</label>
                    <p>{{ $reference->reference_mobile ?? '-' }}</p>
                </div>
                <div class="field"><label>Email</label>
                    <p>{{ $reference->reference_email ?? '-' }}</p>
                </div>
                <div class="field"><label>Designation</label>
                    <p>{{ $reference->reference_designation ?? '-' }}</p>
                </div>
                <div class="field"><label>Organization</label>
                    <p>{{ $reference->reference_organization ?? '-' }}</p>
                </div>
                <div class="field"><label>Details</label>
                    <p>{{ $reference->reference_detail ?? '-' }}</p>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="exp-card">
            <p style="color:#9ca3af;">No references available</p>
        </div>
        @endif

    </div>
</div>

@endsection