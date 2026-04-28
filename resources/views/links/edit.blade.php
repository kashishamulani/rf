@extends('layouts.app')

@section('content')

<div style="padding:6px; width:100%; display:flex; flex-direction:column; align-items:center;">

    {{-- Back --}}
    <div style="width:100%; max-width:1100px; display:flex; justify-content:flex-end; margin-bottom:10px;">
        <a href="{{ route('links.index') }}"
            style="padding:6px 12px;background:#e5e7eb;color:#374151;border-radius:6px;text-decoration:none;font-size:13px;">
            ← Back
        </a>
    </div>

    <form method="POST" action="{{ route('links.update',$form->id) }}"
        style="width:100%; max-width:1100px; background:#fff;padding:20px; border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.05);">
        @csrf

        <h2 style="font-size:20px; font-weight:600; margin-bottom:12px; color:#4f46e5;">
            Edit Form
        </h2>

        {{-- Errors --}}
        @if($errors->any())
        <div style="padding:8px; background:#ef4444; color:#fff; border-radius:6px; margin-bottom:10px;">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Title + Description --}}
        <div style="display:flex; gap:10px; margin-bottom:12px;">
            <div style="flex:1;">
                <label style="display:block; font-size:13px; margin-bottom:4px; color:#374151;">
                    Form Title *
                </label>
                <input type="text" name="title" value="{{ $form->title }}" required
                    style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
            </div>

            <div style="flex:1;">
                <label style="display:block; font-size:13px; margin-bottom:4px; color:#374151;">
                    Description
                </label>
                <input type="text" name="description" value="{{ $form->description }}"
                    style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
            </div>
        </div>

        {{-- Meta --}}
        <!-- <div style="display:flex; gap:10px; margin-bottom:12px;">
            <div style="flex:1;">
                <label style="display:block; font-size:13px; margin-bottom:4px; color:#374151;">
                    Location
                </label>
                <input type="text" name="location" value="{{ $form->location }}"
                    style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
            </div>

            <div style="flex:1;">
                <label style="display:block; font-size:13px; margin-bottom:4px; color:#374151;">
                    Valid From
                </label>
                <input type="date" name="valid_from" value="{{ $form->valid_from }}"
                    style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
            </div>

            <div style="flex:1;">
                <label style="display:block; font-size:13px; margin-bottom:4px; color:#374151;">
                    Valid To
                </label>
                <input type="date" name="valid_to" value="{{ $form->valid_to }}"
                    style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
            </div>
        </div> -->



        <h3 style="margin-top:20px; color:#4f46e5;">Instructions</h3>

        <div id="instructions_wrapper" style="display:flex; flex-direction:column; gap:8px; margin-top:10px;">
        </div>

        <button type="button" onclick="addInstruction()" style="
    margin-top:10px;
    padding:6px 12px;
    background:#10b981;
    color:#fff;
    border:none;
    border-radius:6px;">
            + Add Instruction
        </button>
        {{-- 🔥 FIELD SELECTOR --}}
        <h3 style="margin-top:20px; color:#4f46e5;">Select Fields</h3>

        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:10px; margin-top:10px;">

            @foreach($mobilizationFields as $field)
            <div id="field-box-{{ $loop->index }}"
                onclick="toggleField('{{ $field['label'] }}','{{ $field['type'] }}', this)"
                style="padding:10px; border:1px solid #e5e7eb; border-radius:8px; cursor:pointer; background:#f9fafb; position:relative;">

                <span class="tick" style="position:absolute; top:6px; right:8px; display:none;">✔</span>

                <strong>{{ $field['label'] }}</strong><br>
                <small style="color:#6b7280;">{{ $field['type'] }}</small>
                
                <!-- Required Checkbox (only show when field is selected) -->
                <div class="required-container" style="margin-top:8px; display:flex; align-items:center; gap:6px;" onclick="event.stopPropagation()" style="display:none;">
                    <input type="checkbox" 
                           class="required-checkbox" 
                           data-label="{{ $field['label'] }}"
                           onchange="toggleRequired(this)"
                           style="width:16px; height:16px; cursor:pointer;">
                    <label style="font-size:12px; color:#374151; cursor:pointer; margin:0;">Required</label>
                </div>
            </div>
            @endforeach

        </div>

        {{-- hidden --}}
        <input type="hidden" name="selected_fields" id="selected_fields">

        {{-- Submit --}}
        <button type="submit"
            style="margin-top:20px;padding:10px;background:linear-gradient(135deg,#6366f1,#ec4899);color:#fff;border-radius:8px;width:100%;">
            Update Form
        </button>

    </form>
</div>

<script>
let selected = [];

/* ✅ PRELOAD EXISTING FIELDS */
let existing = @json($form -> fields);

existing.forEach(f => {
    selected.push({
        label: f.label,
        type: f.type,
        is_required: f.is_required || 0
    });
});

/* ✅ MARK PRESELECTED UI */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[id^="field-box-"]').forEach(box => {

        let label = box.querySelector('strong').innerText;

        let found = selected.find(f => f.label === label);

        if (found) {
            box.style.background = '#6366f1';
            box.style.color = '#fff';
            box.querySelector('.tick').style.display = 'block';
            
            // Show and set required checkbox
            let reqContainer = box.querySelector('.required-container');
            if (reqContainer) {
                reqContainer.style.display = 'flex';
                let reqCheckbox = box.querySelector('.required-checkbox');
                if (reqCheckbox) {
                    reqCheckbox.checked = found.is_required ? true : false;
                }
            }
        } else {
            // Hide required container for unselected fields
            let reqContainer = box.querySelector('.required-container');
            if (reqContainer) {
                reqContainer.style.display = 'none';
            }
        }
    });

    updateInput();
});

/* ✅ TOGGLE FIELD */
function toggleField(label, type, el) {

    let index = selected.findIndex(f => f.label === label);

    if (index > -1) {
        // REMOVE
        selected.splice(index, 1);

        el.style.background = '#f9fafb';
        el.style.color = '#000';
        el.querySelector('.tick').style.display = 'none';
        
        // Hide required checkbox and uncheck it
        let reqContainer = el.querySelector('.required-container');
        if (reqContainer) {
            reqContainer.style.display = 'none';
            let reqCheckbox = el.querySelector('.required-checkbox');
            if (reqCheckbox) {
                reqCheckbox.checked = false;
            }
        }

    } else {
        // ADD
        selected.push({
            label,
            type,
            is_required: false
        });

        el.style.background = '#6366f1';
        el.style.color = '#fff';
        el.querySelector('.tick').style.display = 'block';
        
        // Show required checkbox
        let reqContainer = el.querySelector('.required-container');
        if (reqContainer) {
            reqContainer.style.display = 'flex';
            let reqCheckbox = el.querySelector('.required-checkbox');
            if (reqCheckbox) {
                reqCheckbox.checked = false;
            }
        }
    }

    updateInput();
}

/* ✅ UPDATE HIDDEN INPUT */
function updateInput() {
    document.getElementById('selected_fields').value = JSON.stringify(selected);
}

function toggleRequired(checkbox) {
    let label = checkbox.getAttribute('data-label');
    let fieldObj = selected.find(f => f.label === label);
    
    if (fieldObj) {
        fieldObj.is_required = checkbox.checked ? 1 : 0;
        document.getElementById('selected_fields').value = JSON.stringify(selected);
    }
}
    </script>

<script>
let instructions = @json(json_decode($form->instructions, true) ?? []);

document.addEventListener('DOMContentLoaded', () => {

    let wrapper = document.getElementById('instructions_wrapper');

    // preload
    if (instructions.length > 0) {
        instructions.forEach(ins => {
            addInstruction(ins);
        });
    } else {
        addInstruction();
    }
});

function addInstruction(value = '') {
    let wrapper = document.getElementById('instructions_wrapper');

    let div = document.createElement('div');
    div.style.display = "flex";
    div.style.gap = "8px";

    div.innerHTML = `
        <input type="text" name="instructions[]" value="${value}"
            placeholder="Enter instruction"
            style="flex:1; padding:8px; border-radius:6px; border:1px solid #d1d5db;">

        <button type="button" onclick="removeInstruction(this)"
            style="background:#ef4444;color:#fff;border:none;padding:6px 10px;border-radius:6px;">
            ✕
        </button>
    `;

    wrapper.appendChild(div);
}

function removeInstruction(btn) {
    btn.parentElement.remove();
}
</script>
@endsection