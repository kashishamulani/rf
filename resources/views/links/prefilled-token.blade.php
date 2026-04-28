<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $form->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>

<body style="margin:0; font-family: 'Roboto', sans-serif; background:#f1f3f4;">

    <div style="padding:20px; display:flex; justify-content:center;">
        <div style="width:100%; max-width:1150px;">

            <!-- HEADER -->
            <div class="form-section"
                style="background:#673ab7; color:white; padding:24px 32px; border-radius:12px 12px 0 0; margin-bottom:0;">
                <h2 style="margin:0 0 8px 0; font-size:24px;">{{ $form->title }}</h2>
                @if($form->description)
                <p style="margin:0; opacity:0.9;">{{ $form->description }}</p>
                @endif
            </div>

            <div class="form-section"
                style="background:white; padding:32px 40px; border-radius:0 0 18px 18px; box-shadow:0 6px 18px rgba(0,0,0,0.06); margin-bottom:24px;">

                {{-- INSTRUCTIONS --}}
                @php $instructions = json_decode($form->instructions, true); @endphp
                @if(!empty($instructions))
                <div
                    style="background:#fafafa; border-left:4px solid #673ab7; padding:14px 18px; margin-bottom:24px; border-radius:6px; font-size:13px;">
                    <strong style="color:#673ab7;">Instructions:</strong><br>
                    @foreach($instructions as $i => $ins)
                    {{ $i+1 }}. {{ $ins }}<br>
                    @endforeach
                </div>
                @endif

                {{-- Use URL helper with the direct path --}}
                <form action="{{ url('/forms/prefill/' . $token) }}" method="POST" enctype="multipart/form-data"
                    id="dynamicForm">
                    @csrf

                    {{-- ===================== CORE LOGIC ===================== --}}
                    @php
                    $orderMap = [
                    'full name'=>1,'email'=>2,'mobile'=>3,'whatsapp number'=>4,
                    'highest qualification'=>5,'date of birth'=>6,'gender'=>7,
                    'marital status'=>8,'state'=>9,'city'=>10,'address'=>11,
                    'identification remark'=>12,'languages'=>13,'current salary'=>14,
                    'preferred salary'=>15,'bank account number'=>16,'ifsc code'=>17,
                    'organization'=>18,'designation'=>19,'duration'=>20,
                    'role category'=>21,'sub role'=>22,'pan number'=>23,'pan card'=>24,
                    'aadhar number'=>25,'aadhar front'=>26,'aadhar back'=>27,
                    'photo'=>28,'signature'=>29,'passbook'=>30,'driving license'=>31,
                    'experience letter'=>32,'10th passing year'=>33,'10th marksheet'=>34,
                    '12th passing year'=>35,'12th marksheet'=>36,'graduation passing year'=>37,
                    'graduation marksheet'=>38,'post graduation passing year'=>39,
                    'post graduation marksheet'=>40,
                    ];

                    $personalLabels = [
                    'full name','email','mobile','whatsapp number','highest qualification',
                    'date of birth','gender','marital status','state','city','address',
                    'identification remark','languages','current salary','preferred salary',
                    'bank account number','ifsc code'
                    ];

                    $experienceLabels = [
                    'organization','designation','duration','role category','sub role'
                    ];

                    $personalFields = [];
                    $experienceFields = [];
                    $documentFields = [];

                    // SORT
                    $sortedFields = $form->fields->sortBy(function ($field) use ($orderMap) {
                    $label = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
                    return $orderMap[$label] ?? 999;
                    });

                    // GROUP
                    foreach ($sortedFields as $field) {
                    $label = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));

                    if (in_array($label, $personalLabels)) {
                    $personalFields[] = $field;
                    } elseif (in_array($label, $experienceLabels)) {
                    $experienceFields[] = $field;
                    } else {
                    $documentFields[] = $field;
                    }
                    }
                    @endphp
                    {{-- ===================================================== --}}

                    <!-- Display validation errors -->
                    <div id="error-container"
                        style="display:none; margin-bottom:20px; padding:15px; background:#fee2e2; border:1px solid #fecaca; border-radius:12px; color:#991b1b;">
                        <strong>Please fix the following errors:</strong>
                        <ul id="error-list" style="margin-top:8px; padding-left:18px;"></ul>
                    </div>

                    <!-- STEP 1 -->
                    @if(count($personalFields))
                    <h2 class="section-title"
                        style="font-size:20px; font-weight:600; color:#673ab7; margin-bottom:20px; border-left:4px solid #673ab7; padding-left:15px;">
                        Step 1: Personal Details</h2>
                    <div class="grid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px;">
                        @foreach($personalFields as $field)
                        @php
                        $value = $prefilledFields[$field->id] ?? '';
                        $normalizedLabel = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
                        @endphp

                        <div class="field">
                            <label
                                style="font-size:13px; font-weight:500; margin-bottom:6px; display:block; color:#333;">{{ $field->label }}
                                @if($field->required)<span style="color:red;">*</span>@endif</label>

                            <div style="border:1px solid #ddd; border-radius:8px; padding:10px; background:white;">
                                @if($field->type == 'select' || in_array($normalizedLabel,
                                ['state','city','gender','marital status']))
                                @php
                                $options = $field->options;

                                if (is_string($options)) {
                                $decoded = json_decode($options, true);
                                if (json_last_error() === JSON_ERROR_NONE) {
                                $options = $decoded;
                                }
                                }

                                if (!is_array($options)) {
                                $options = [];
                                }

                                if (empty($options)) {
                                switch ($normalizedLabel) {
                                case 'gender':
                                $options = ['Male', 'Female', 'Other'];
                                break;

                                case 'marital status':
                                $options = ['Single', 'Married', 'Divorced', 'Widowed'];
                                break;

                                case 'role category':
                                $options = ['Sales', 'Field Work', 'Office Work'];
                                break;

                                case 'sub role':
                                $options = ['Executive', 'Manager', 'Assistant'];
                                break;
                                }
                                }
                                @endphp

                                @if($normalizedLabel == 'state')
                                <select name="field_{{ $field->id }}" id="state_select"
                                    style="width:100%; border:none; outline:none; font-size:13px;" @if($field->required)
                                    required @endif>
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                    <option value="{{ $state['name'] }}" data-code="{{ $state['iso2'] }}"
                                        {{ ($value == $state['name']) ? 'selected' : '' }}>
                                        {{ $state['name'] }}
                                    </option>
                                    @endforeach
                                </select>

                                @elseif($normalizedLabel == 'city')
                                <select name="field_{{ $field->id }}" id="city_select"
                                    style="width:100%; border:none; outline:none; font-size:13px;" @if($field->required)
                                    required @endif>
                                    <option value="">Select District</option>
                                </select>

                                @elseif(!empty($options) && is_array($options))
                                <select name="field_{{ $field->id }}"
                                    style="width:100%; border:none; outline:none; font-size:13px;" @if($field->required)
                                    required @endif>
                                    <option value="">Select {{ $field->label }}</option>
                                    @foreach($options as $opt)
                                    @if(!is_null($opt))
                                    @php
                                    $prefillValue = strtolower(trim($prefilledFields[$field->id] ?? ''));
                                    $optionValue = strtolower(trim($opt));
                                    @endphp

                                    <option value="{{ $opt }}" {{ $prefillValue === $optionValue ? 'selected' : '' }}>
                                        {{ $opt }}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>

                                @else
                                <input type="text" name="field_{{ $field->id }}"
                                    value="{{ $prefilledFields[$field->id] ?? '' }}"
                                    placeholder="Enter {{ $field->label }}"
                                    style="width:100%; border:none; outline:none; font-size:13px;">
                                @endif

                                @elseif($field->type == 'file')
                                <input type="file" name="field_{{ $field->id }}" class="file-input"
                                    accept=".jpg,.jpeg,.png,.pdf" data-max-size="5"
                                    style="width:100%; border:none; outline:none; font-size:13px;">
                                <div class="file-preview" id="preview_{{ $field->id }}"
                                    style="font-size:11px; margin-top:4px;"></div>
                                <small style="color: #6b7280; font-size: 11px; display:block; margin-top:4px;">Accepted:
                                    JPG, PNG, PDF (Max 5MB)</small>
                                @else
                                <input type="{{ $field->type }}" name="field_{{ $field->id }}"
                                    value="{{ $prefilledFields[$field->id] ?? '' }}"
                                    placeholder="Enter {{ $field->label }}"
                                    style="width:100%; border:none; outline:none; font-size:13px;">
                                @endif
                            </div>

                            @error('field_'.$field->id)
                            <div style="color:red; font-size:11px; margin-top:4px;">{{ $message }}</div>
                            @enderror
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- STEP 2 -->
                    @if(count($experienceFields))
                    <h2 class="section-title"
                        style="font-size:20px; font-weight:600; color:#673ab7; margin:30px 0 20px 0; border-left:4px solid #673ab7; padding-left:15px;">
                        Step 2: Experience Details</h2>
                    <div class="grid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px;">
                        @foreach($experienceFields as $field)
                        @php
                        $normalizedLabel = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
                        @endphp
                        <div class="field">
                            <label
                                style="font-size:13px; font-weight:500; margin-bottom:6px; display:block; color:#333;">{{ $field->label }}</label>
                            <div style="border:1px solid #ddd; border-radius:8px; padding:10px; background:white;">
                                @if($field->type == 'select' || in_array($normalizedLabel, ['role category','sub
                                role','gender','marital status']))
                                @php
                                $options = $field->options;
                                if (is_string($options) && !empty($options)) {
                                $options = json_decode($options, true);
                                }
                                if (empty($options) || (is_array($options) && count($options) == 0)) {
                                if ($normalizedLabel == 'role category') {
                                $options = ['Sales', 'Field Work', 'Office Work'];
                                } elseif ($normalizedLabel == 'sub role') {
                                $options = ['Executive', 'Manager', 'Assistant'];
                                } elseif ($normalizedLabel == 'gender') {
                                $options = ['Male', 'Female', 'Other'];
                                } elseif ($normalizedLabel == 'marital status') {
                                $options = ['Single', 'Married', 'Divorced', 'Widowed'];
                                }
                                }
                                @endphp
                                @if(!empty($options) && is_array($options))
                                <select name="field_{{ $field->id }}"
                                    style="width:100%; border:none; outline:none; font-size:13px;">
                                    <option value="">Select {{ $field->label }}</option>
                                    @foreach($options as $opt)
                                    @php
                                    $prefill = strtolower(trim($prefilledFields[$field->id] ?? ''));
                                    $option = strtolower(trim($opt));

                                    if ($prefill === 'm') $prefill = 'male';
                                    if ($prefill === 'f') $prefill = 'female';
                                    @endphp
                                    <option value="{{ $opt }}" {{ $prefill == $option ? 'selected' : '' }}>
                                        {{ $opt }}
                                    </option>
                                    @endforeach
                                </select>
                                @else
                                <input type="text" name="field_{{ $field->id }}"
                                    value="{{ $prefilledFields[$field->id] ?? '' }}"
                                    style="width:100%; border:none; outline:none; font-size:13px;">
                                @endif
                                @elseif($field->type == 'file')
                                <input type="file" name="field_{{ $field->id }}" class="file-input"
                                    accept=".jpg,.jpeg,.png,.pdf" data-max-size="5"
                                    style="width:100%; border:none; outline:none; font-size:13px;">
                                <div class="file-preview" id="preview_{{ $field->id }}"
                                    style="font-size:11px; margin-top:4px;"></div>
                                <small style="color: #6b7280; font-size: 11px; display:block; margin-top:4px;">Accepted:
                                    JPG, PNG, PDF (Max 5MB)</small>
                                @else
                                <input type="{{ $field->type }}" name="field_{{ $field->id }}"
                                    value="{{ $prefilledFields[$field->id] ?? '' }}"
                                    style="width:100%; border:none; outline:none; font-size:13px;">
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- STEP 3 -->
                    @if(count($documentFields))
                    <h2 class="section-title"
                        style="font-size:20px; font-weight:600; color:#673ab7; margin:30px 0 20px 0; border-left:4px solid #673ab7; padding-left:15px;">
                        Step 3: Documents</h2>
                    <div class="grid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px;">
                        @foreach($documentFields as $field)
                        <div class="field">
                            <label
                                style="font-size:13px; font-weight:500; margin-bottom:6px; display:block; color:#333;">{{ $field->label }}</label>
                            <div style="border:1px solid #ddd; border-radius:8px; padding:10px; background:white;">
                                @if($field->type == 'file')
                                <input type="file" name="field_{{ $field->id }}" class="file-input"
                                    accept=".jpg,.jpeg,.png,.pdf" data-max-size="5"
                                    style="width:100%; border:none; outline:none; font-size:13px;">
                                <div class="file-preview" id="preview_{{ $field->id }}"
                                    style="font-size:11px; margin-top:4px;"></div>
                                <small style="color: #6b7280; font-size: 11px; display:block; margin-top:4px;">Accepted:
                                    JPG, PNG, PDF (Max 5MB)</small>
                                @else
                                <input type="text" name="field_{{ $field->id }}"
                                    value="{{ $prefilledFields[$field->id] ?? '' }}"
                                    style="width:100%; border:none; outline:none; font-size:13px;">
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div style="margin-top:30px; text-align:right;">
                        <button type="submit" id="submitBtn"
                            style="background:#673ab7; color:white; padding:12px 28px; border:none; border-radius:6px; cursor:pointer; font-size:14px; font-weight:500;">Submit</button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {

        const stateSelect = document.getElementById('state_select');
        const citySelect = document.getElementById('city_select');

        if (stateSelect && citySelect) {
            // Load States from LocationController
            fetch('/states')
                .then(res => res.json())
                .then(states => {
                    stateSelect.innerHTML = '<option value="">Select State</option>';
                    states.forEach(state => {
                        const option = document.createElement('option');
                        option.value = state.name;
                        option.textContent = state.name;
                        option.setAttribute('data-code', state.iso2);
                        stateSelect.appendChild(option);
                    });

                    // ✅ Auto-load (prefill)
                    @if(isset($cityField) && $cityField)
                    const selectedCity = @json($prefilledFields[$cityField -> id] ?? null);
                    if (stateSelect.value) {
                        const selectedOption = stateSelect.options[stateSelect.selectedIndex];
                        const stateCode = selectedOption.getAttribute('data-code');
                        if (stateCode) {
                            loadCities(stateCode, selectedCity);
                        }
                    }
                    @endif
                })
                .catch(error => {
                    console.error('Error loading states:', error);
                    stateSelect.innerHTML = '<option value="">Error loading states</option>';
                });

            // Load Cities when State changes
            stateSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const stateCode = selectedOption.getAttribute('data-code');

                if (!stateCode) {
                    citySelect.innerHTML = '<option value="">Select District</option>';
                    return;
                }

                citySelect.innerHTML = '<option value="">Loading...</option>';

                fetch(`/districts/${stateCode}`)
                    .then(res => res.json())
                    .then(cities => {
                        citySelect.innerHTML = '<option value="">Select District</option>';
                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.name;
                            option.textContent = city.name;
                            citySelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading cities:', error);
                        citySelect.innerHTML = '<option value="">Error loading districts</option>';
                    });
            });
        }

        async function loadCities(stateCode, selectedCityValue = null) {
            if (!stateCode) {
                citySelect.innerHTML = '<option value="">Select District</option>';
                return;
            }

            citySelect.innerHTML = '<option value="">Loading...</option>';

            try {
                const response = await fetch(`/districts/${stateCode}`);

                if (!response.ok) {
                    throw new Error('Failed to fetch');
                }

                const cities = await response.json();

                citySelect.innerHTML = '<option value="">Select District</option>';

                if (Array.isArray(cities) && cities.length > 0) {
                    cities.forEach(city => {
                        const name = typeof city === 'object' ? city.name : city;
                        const option = document.createElement('option');
                        option.value = name;
                        option.textContent = name;

                        if (selectedCityValue && selectedCityValue === name) {
                            option.selected = true;
                        }

                        citySelect.appendChild(option);
                    });
                } else {
                    citySelect.innerHTML = '<option value="">No districts found</option>';
                }
            } catch (error) {
                console.error('City load error:', error);
                citySelect.innerHTML = '<option value="">Error loading districts</option>';
            }
        }

        // ==================== FILE VALIDATION WITH PREVIEW ====================

        function validateFile(file, maxSizeMB = 5) {
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            const maxSize = maxSizeMB * 1024 * 1024;

            if (!allowedTypes.includes(file.type)) {
                return {
                    valid: false,
                    message: `Invalid file type: ${file.name}. Only JPG, PNG, and PDF files are allowed.`
                };
            }

            if (file.size > maxSize) {
                return {
                    valid: false,
                    message: `File "${file.name}" exceeds ${maxSizeMB}MB limit. Current size: ${(file.size / (1024 * 1024)).toFixed(2)}MB`
                };
            }

            return {
                valid: true
            };
        }

        // Add validation and preview to all file inputs
        const fileInputs = document.querySelectorAll('.file-input');

        fileInputs.forEach(input => {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const maxSize = parseInt(this.getAttribute('data-max-size') || '5');
                const fieldId = this.getAttribute('data-id') || Math.random();
                const previewDiv = this.parentElement.querySelector('.file-preview');

                // Remove any existing error message
                const existingError = this.parentElement.querySelector('.field-error');
                if (existingError) existingError.remove();

                if (file) {
                    const validation = validateFile(file, maxSize);
                    if (!validation.valid) {
                        this.value = '';
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'field-error';
                        errorDiv.style.color = '#dc2626';
                        errorDiv.style.fontSize = '11px';
                        errorDiv.style.marginTop = '4px';
                        errorDiv.textContent = validation.message;
                        this.parentElement.appendChild(errorDiv);
                    } else if (previewDiv && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewDiv.innerHTML =
                                `<img src="${e.target.result}" style="max-width:60px; max-height:60px; border-radius:4px;">`;
                        };
                        reader.readAsDataURL(file);
                    } else if (previewDiv) {
                        previewDiv.innerHTML = `📄 ${file.name}`;
                    }
                } else if (previewDiv) {
                    previewDiv.innerHTML = '';
                }
            });
        });

        // Form submission validation
        const form = document.getElementById('dynamicForm');
        const errorContainer = document.getElementById('error-container');
        const errorList = document.getElementById('error-list');

        if (form) {
            form.addEventListener('submit', function(e) {
                let hasErrors = false;
                const errors = [];

                if (errorContainer) {
                    errorContainer.style.display = 'none';
                    errorList.innerHTML = '';
                }

                document.querySelectorAll('.field-error').forEach(err => err.remove());

                const fileInputsToValidate = document.querySelectorAll('.file-input');

                fileInputsToValidate.forEach(input => {
                    const file = input.files[0];
                    const maxSize = parseInt(input.getAttribute('data-max-size') || '5');
                    const fieldLabel = input.closest('.field')?.querySelector('label')
                        ?.innerText || 'File';

                    if (file) {
                        const validation = validateFile(file, maxSize);
                        if (!validation.valid) {
                            hasErrors = true;
                            errors.push(`${fieldLabel}: ${validation.message}`);

                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'field-error';
                            errorDiv.style.color = '#dc2626';
                            errorDiv.style.fontSize = '11px';
                            errorDiv.style.marginTop = '4px';
                            errorDiv.textContent = validation.message;
                            input.parentElement.appendChild(errorDiv);
                            input.style.borderColor = '#dc2626';
                        }
                    }
                });

                if (hasErrors && errorContainer) {
                    e.preventDefault();
                    errorContainer.style.display = 'block';
                    errors.forEach(error => {
                        const li = document.createElement('li');
                        li.textContent = error;
                        errorList.appendChild(li);
                    });
                    errorContainer.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    return false;
                }

                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerText = 'Submitting...';
                }
            });
        }

        // Mobile number validation
        document.querySelectorAll('input').forEach(input => {
            const label = input.closest('.field')?.querySelector('label')?.innerText.toLowerCase();
            if (label && (label.includes('mobile') || label.includes('whatsapp'))) {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
        });
    });
    </script>

    <style>
    @media(max-width:992px) {
        .grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }

    @media(max-width:600px) {
        .grid {
            grid-template-columns: 1fr !important;
        }

        .form-section {
            padding: 20px !important;
        }
    }

    .field-error {
        animation: shake 0.3s ease-in-out;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-3px);
        }

        75% {
            transform: translateX(3px);
        }
    }
    </style>

</body>

</html>