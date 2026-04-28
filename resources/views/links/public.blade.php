<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $form->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
    body { margin:0; font-family: 'Roboto', sans-serif; background:#f1f3f4; }
    .container { max-width:1000px; margin:30px auto; padding:10px; }

    .header {
        background:#673ab7;
        color:white;
        padding:20px 24px;
        border-radius:12px 12px 0 0;
    }

    .card {
        background:white;
        padding:24px;
        border-radius:0 0 12px 12px;
        box-shadow:0 6px 18px rgba(0,0,0,0.06);
    }

    .instructions {
        background:#fafafa;
        border-left:4px solid #673ab7;
        padding:14px;
        margin-bottom:20px;
        border-radius:6px;
        font-size:13px;
    }

    .grid {
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:18px;
    }

    .field label { font-size:13px; font-weight:500; margin-bottom:6px; display:block; }

    .input-box {
        border:1px solid #ddd;
        border-radius:8px;
        padding:10px;
    }

    input, select, textarea {
        width:100%;
        border:none;
        outline:none;
        font-size:13px;
        background:transparent;
    }

    textarea { min-height:70px; }

    .error {
        color:red;
        font-size:11px;
        margin-top:4px;
    }

    .file-preview { font-size:11px; margin-top:4px; }

    button {
        background:#673ab7;
        color:white;
        padding:12px;
        border:none;
        border-radius:6px;
        margin-top:20px;
        cursor:pointer;
    }

    @media(max-width:992px){ .grid{grid-template-columns:repeat(2,1fr);} }
    @media(max-width:600px){ .grid{grid-template-columns:1fr;} }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h2>{{ $form->title }}</h2>
        <p>{{ $form->description }}</p>
    </div>

    <div class="card">

        {{-- GLOBAL ERROR --}}
        @if ($errors->any())
            <div style="background:#fee2e2; padding:10px; margin-bottom:15px; border-radius:6px;">
                <ul style="margin:0; padding-left:15px;">
                    @foreach ($errors->all() as $error)
                        <li style="color:#991b1b; font-size:13px;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- INSTRUCTIONS --}}
        @php $instructions = json_decode($form->instructions, true); @endphp

        @if(!empty($instructions))
        <div class="instructions">
            <b>Instructions:</b><br>
            @foreach($instructions as $i => $ins)
                {{ $i+1 }}. {{ $ins }}<br>
            @endforeach
        </div>
        @endif

        <form method="POST" enctype="multipart/form-data" action="{{ route('forms.submit',$form->slug) }}">
            @csrf

            <div class="grid">

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

                @foreach($sortedFields as $field)

                @php
                    $label = strtolower(trim(preg_replace('/\s+/', ' ', $field->label)));
                    $isMobile = ($label == 'mobile' || $label == 'whatsapp number');
                    $isState = ($label == 'state');
                    $isCity = ($label == 'city');
                @endphp

                <div class="field">
                    <label>
                        {{ $field->label }}
                        @if($field->is_required)
                            <span style="color:red">*</span>
                        @endif
                    </label>

                    <div class="input-box">

                        {{-- STATE DROPDOWN --}}
                        @if($isState)
                            <select name="field_{{ $field->id }}" id="state_select" @if($field->is_required) required @endif>
                                <option value="">Select State</option>
                            </select>

                        {{-- CITY DROPDOWN --}}
                        @elseif($isCity)
                            <select name="field_{{ $field->id }}" id="city_select" @if($field->is_required) required @endif>
                                <option value="">Select District</option>
                            </select>

                        {{-- SELECT --}}
                        @elseif($field->type == 'select')
                            <select name="field_{{ $field->id }}" @if($field->is_required) required @else nullable @endif>
                                <option value="">Select</option>
                                @php
    $options = is_array($field->options)
        ? $field->options
        : json_decode($field->options, true);
@endphp

@foreach($options ?? [] as $opt)
                                    <option value="{{ $opt }}"
                                        {{ old('field_'.$field->id) == $opt ? 'selected' : '' }}>
                                        {{ $opt }}
                                    </option>
                                @endforeach
                            </select>

                        {{-- FILE --}}
                        @elseif($field->type == 'file')
                            <input type="file"
                                name="field_{{ $field->id }}"
                                class="file-input"
                                data-id="{{ $field->id }}"
                                @if($field->is_required) required @endif>

                            <div class="file-preview" id="preview_{{ $field->id }}"></div>

                        {{-- TEXTAREA --}}
                        @elseif($field->type == 'textarea')
                            <textarea name="field_{{ $field->id }}" @if($field->is_required) required @endif>{{ old('field_'.$field->id) }}</textarea>

                        {{-- DEFAULT --}}
                        @else
                            <input type="{{ $field->type }}"
                                name="field_{{ $field->id }}"
                                value="{{ old('field_'.$field->id) }}"

                                {{-- ✅ MOBILE RESTRICTION --}}
                                @if($isMobile)
                                    inputmode="numeric"
                                    pattern="[0-9]*"
                                    maxlength="15"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                @endif

                                @if($field->is_required) required @endif>
                        @endif

                    </div>

                    {{-- ERROR --}}
                    @error('field_'.$field->id)
                        <div class="error">{{ $message }}</div>
                    @enderror

                </div>

                @endforeach

            </div>

          <button type="submit" id="submitBtn">Submit</button>
        </form>

    </div>
</div>

<script>

// ✅ FILE VALIDATION + PREVIEW
document.querySelectorAll('.file-input').forEach(input => {

    input.addEventListener('change', function() {

        let file = this.files[0];
        let id = this.dataset.id;
        let preview = document.getElementById('preview_' + id);

        if (!file) return;

        let allowed = ['jpg','jpeg','png','pdf'];
        let ext = file.name.split('.').pop().toLowerCase();

        if (!allowed.includes(ext)) {
            alert('Invalid file type');
            this.value = '';
            return;
        }

       if (file.size > 7 * 1024 * 1024) {
    alert('Max 7MB allowed');
    this.value = '';
    return;
}

        if (file.type.startsWith('image')) {
            let reader = new FileReader();
            reader.onload = function(e){
                preview.innerHTML = `<img src="${e.target.result}" style="width:60px;">`;
            }
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '📄 ' + file.name;
        }

    });

});

// ✅ EXTRA SAFETY (BLOCK ALPHABETS IN MOBILE)
document.querySelectorAll('input').forEach(input => {

    let label = input.closest('.field')?.querySelector('label')?.innerText.toLowerCase();

    if (label && (label.includes('mobile') || label.includes('whatsapp'))) {

        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

    }
});


document.querySelector('form').addEventListener('submit', function () {

    let btn = document.getElementById('submitBtn');

    btn.disabled = true;
    btn.innerText = 'Submitting...';

});

// ✅ STATE & CITY DROPDOWN LOADING
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

</script>

</body>
</html>