<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $form->title }} - Response</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: 'Roboto', 'Segoe UI', sans-serif;
            background: #f1f3f4;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 10px;
        }

        .header {
            background: #673ab7;
            color: white;
            padding: 20px 24px;
            border-radius: 12px 12px 0 0;
        }

        .card {
            background: white;
            padding: 24px;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .field {
            display: flex;
            flex-direction: column;
        }

        .full-width {
            grid-column: span 3;
        }

        .field label {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .value-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px 12px;
            background: #fafafa;
            font-size: 13px;
        }

        .file-link {
            display: inline-block;
            margin-right: 10px;
            margin-top: 5px;
            color: #2563eb;
            text-decoration: none;
            font-size: 12px;
        }

        .meta {
            font-size: 11px;
            color: #6b7280;
            margin-top: 5px;
        }

        .back-btn {
            margin-top: 20px;
            display: inline-block;
            background: #e5e7eb;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            color: #374151;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #4f46e5;
            margin: 24px 0 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }

        .doc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 12px;
        }

        .doc-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }

        .doc-card strong {
            display: block;
            font-size: 12px;
            color: #374151;
            margin-bottom: 6px;
        }

        .doc-link {
            display: inline-block;
            padding: 4px 10px;
            background: #eef2ff;
            color: #4338ca;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
        }

        .no-doc {
            color: #9ca3af;
            font-size: 11px;
        }

        @media(max-width: 992px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width: 600px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="container">

    {{-- HEADER --}}
    <div class="header">
        <h2>{{ $form->title }}</h2>
        <p>Candidate Response</p>
    </div>

    {{-- CARD --}}
    <div class="card">

        @php
            // Define field order same as mobilization
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
            
            // Sort fields by order map
            $sortedFields = $form->fields->sortBy(function($field) use ($orderMap) {
                $label = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
                return $orderMap[$label] ?? 999;
            });
        @endphp

        <div class="grid">

            @foreach($sortedFields as $field)

                @php
                    $value = $response->values
                        ->where('field_id', $field->id)
                        ->first();
                @endphp

                <div class="field {{ $field->type == 'textarea' ? 'full-width' : '' }}">

                    <label>{{ $field->label }}</label>

                    <div class="value-box">

                        {{-- FILE HANDLING --}}
                        @if($field->type == 'file' && $value && ($value->file_url || $value->value))

                            @php
                                $filePath = $value->file_url ?? $value->value;
                            @endphp

                            {{-- FILE LINK --}}
                            @if($filePath)
                                <a href="{{ route('document.view', ['path' => $filePath]) }}" target="_blank" class="file-link">
                                    📄 View File
                                </a>
                            @endif

                            {{-- META --}}
                            @if($value->file_type || $value->file_extension || $value->file_size)
                            <div class="meta">
                                Type: {{ $value->file_type ?? 'N/A' }} |
                                Ext: {{ $value->file_extension ?? 'N/A' }} |
                                Size: 
                                {{ $value->file_size ? round($value->file_size / 1024, 2) . ' KB' : 'N/A' }}
                            </div>
                            @endif

                        {{-- NORMAL VALUE --}}
                        @else
                            {{ $value->value ?? '—' }}
                        @endif

                    </div>

                </div>

            @endforeach

        </div>

        {{-- MOBILIZATION DOCUMENTS --}}
        @if(isset($mobilization) && $mobilization)
        <div class="section-title">Documents (Mobilization)</div>
        <div class="doc-grid">
            @php
                $documents = [
                    'pan_card' => 'PAN Card',
                    'aadhar_front' => 'Aadhar Front',
                    'aadhar_back' => 'Aadhar Back',
                    'photo' => 'Photo',
                    'signature' => 'Signature',
                    'passbook_photo' => 'Passbook',
                    'tenth_marksheet' => '10th Marksheet',
                    'twelfth_marksheet' => '12th Marksheet',
                    'graduation_marksheet' => 'Graduation Marksheet',
                    'post_graduation_marksheet' => 'Post Graduation Marksheet',
                    'driving_license' => 'Driving License',
                    'experience_letter' => 'Experience Letter',
                ];
            @endphp

            @foreach($documents as $field => $label)
                <div class="doc-card">
                    <strong>{{ $label }}</strong>
                    @if(!empty($mobilization->$field))
                        <a href="{{ route('document.view', ['path' => $mobilization->$field]) }}" target="_blank" class="doc-link">
                            View
                        </a>
                    @else
                        <span class="no-doc">Not Uploaded</span>
                    @endif
                </div>
            @endforeach
        </div>
        @endif

        <a href="{{ route('links.responses', $form->id) }}" class="back-btn">
            ← Back to Responses
        </a>

    </div>

</div>

</body>
</html>