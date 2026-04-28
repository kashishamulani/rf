@extends('layouts.app')

@section('content')

<div style="padding:6px; width:100%; display:flex; flex-direction:column; align-items:center;">

    {{-- Back Button --}}
    <div style="width:100%; max-width:1100px; display:flex; justify-content:flex-end; margin-bottom:10px;">
        <a href="{{ route('links.index') }}" style="
            padding:6px 12px;
            background:#e5e7eb;
            color:#374151;
            font-weight:500;
            border-radius:6px;
            text-decoration:none;
            font-size:13px;">
            ← Back
        </a>
    </div>

    <form method="POST" action="{{ route('links.store') }}" style="width:100%; max-width:1100px; background:#fff;
        padding:20px; border-radius:10px;
        box-shadow:0 4px 12px rgba(0,0,0,0.05);">
        @csrf

        <h2 style="font-size:20px; font-weight:600; margin-bottom:12px; color:#4f46e5;">
            Create Form
        </h2>

        {{-- Errors --}}
        @if($errors->any())
        <div
            style="padding:8px; background:#ef4444; color:#fff; border-radius:6px; margin-bottom:10px; font-size:13px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Title --}}
        <div style="display:flex; gap:10px; margin-bottom:12px;">
            <div style="flex:1;">
                <label style="display:block; font-size:13px; margin-bottom:4px; color:#374151;">
                    Form Title
                </label>
                <input type="text" name="title" placeholder="Enter form title" required
                    style="width:100%; padding:8px; border-radius:6px; border:1px solid #d1d5db;">
            </div>

            <div style="flex:1;">
                <label style="display:block; font-size:13px; margin-bottom:4px; color:#374151;">
                    Description
                </label>
                <input type="text" name="description" placeholder="Enter description"
                    style="width:100%; padding:8px; border-radius:6px; border:1px solid #d1d5db;">
            </div>
        </div>

        {{-- Meta --}}
        <!-- <div style="display:flex; gap:10px; margin-bottom:12px;">
            <div style="flex:1;">
                <label style="display:block; font-size:13px; margin-bottom:4px; color:#374151;">
                    Location
                </label>
                <input type="text" name="location" placeholder="Enter location"
                    style="width:100%; padding:8px; border-radius:6px; border:1px solid #d1d5db;">
            </div>

            <div style="flex:1;">
                <label style="display:block; font-size:13px; margin-bottom:4px; color:#374151;">
                    Valid From
                </label>
                <input type="date" name="valid_from"
                    style="width:100%; padding:8px; border-radius:6px; border:1px solid #d1d5db;">
            </div>

            <div style="flex:1;">
                <label style="display:block; font-size:13px; margin-bottom:4px; color:#374151;">
                    Valid To
                </label>
                <input type="date" name="valid_to"
                    style="width:100%; padding:8px; border-radius:6px; border:1px solid #d1d5db;">
            </div>
        </div> -->



        {{-- INSTRUCTIONS --}}
        <h3 style="margin-top:20px; color:#4f46e5;">Instructions</h3>

        <div id="instructions_wrapper" style="margin-top:10px; display:flex; flex-direction:column; gap:8px;">

            <div style="display:flex; gap:8px;">
                <input type="text" name="instructions[]" placeholder="Enter instruction"
                    style="flex:1; padding:8px; border-radius:6px; border:1px solid #d1d5db;">

                <button type="button" onclick="removeInstruction(this)"
                    style="background:#ef4444; color:#fff; border:none; padding:6px 10px; border-radius:6px;">
                    ✕
                </button>
            </div>

        </div>

        <button type="button" onclick="addInstruction()" style="
    margin-top:10px;
    padding:6px 12px;
    background:#10b981;
    color:#fff;
    border:none;
    border-radius:6px;
    font-size:13px;">
            + Add Instruction
        </button>
        {{-- FIELD SELECTOR --}}
        <h3 style="margin-top:20px; color:#4f46e5;">Select Fields</h3>

        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:10px; margin-top:10px;">

            @foreach($mobilizationFields as $field)
            <div class="field-card" data-label="{{ $field['label'] }}" data-type="{{ $field['type'] }}"
                onclick="toggleField(this)" style="
                        padding:12px;
                        border:1px solid #e5e7eb;
                        border-radius:10px;
                        cursor:pointer;
                        background:#f9fafb;
                        position:relative;
                        transition:0.2s;
                    ">

                <!-- Tick -->
                <span class="tick" style="
                        position:absolute;
                        top:8px;
                        right:10px;
                        font-size:14px;
                        color:#fff;
                        display:none;
                    ">✔</span>

                <strong>{{ $field['label'] }}</strong><br>
                <small style="color:#6b7280;">{{ $field['type'] }}</small>
                
                <!-- Required Checkbox (only show when field is selected) -->
                <div style="margin-top:8px; display:flex; align-items:center; gap:6px;" onclick="event.stopPropagation()">
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

        {{-- Hidden --}}
        <input type="hidden" name="selected_fields" id="selected_fields">

        {{-- Submit --}}
        <div style="margin-top:14px;">
            <button type="submit" style="
                padding:10px;
                background:linear-gradient(135deg,#6366f1,#ec4899);
                color:#fff;
                font-weight:600;
                border-radius:8px;
                width:100%;">
                Save Form
            </button>
        </div>

    </form>
</div>

{{-- JS --}}
<script>
let selected = [];

function toggleField(el) {
    let label = el.getAttribute('data-label');
    let type = el.getAttribute('data-type');
    
    let index = selected.findIndex(f => f.label === label);
    
    if (index > -1) {
        // ❌ REMOVE
        selected.splice(index, 1);
        
        el.style.background = "#f9fafb";
        el.style.color = "#000";
        el.style.border = "1px solid #e5e7eb";
        el.querySelector('.tick').style.display = "none";
        
        // Hide required checkbox and uncheck it
        let reqCheckbox = el.querySelector('.required-checkbox');
        if (reqCheckbox) {
            reqCheckbox.parentElement.style.display = 'none';
            reqCheckbox.checked = false;
        }
        
    } else {
        // ✅ ADD
        selected.push({
            label,
            type,
            is_required: false
        });
        
        el.style.background = "#6366f1";
        el.style.color = "#fff";
        el.style.border = "1px solid #6366f1";
        el.querySelector('.tick').style.display = "block";
        
        // Show required checkbox
        let reqCheckbox = el.querySelector('.required-checkbox');
        if (reqCheckbox) {
            reqCheckbox.parentElement.style.display = 'flex';
            reqCheckbox.checked = false;
        }
    }
    
    document.getElementById('selected_fields').value = JSON.stringify(selected);
}

function toggleRequired(checkbox) {
    let label = checkbox.getAttribute('data-label');
    let fieldObj = selected.find(f => f.label === label);
    
    if (fieldObj) {
        fieldObj.is_required = checkbox.checked;
        document.getElementById('selected_fields').value = JSON.stringify(selected);
    }
}


function addInstruction() {
    let wrapper = document.getElementById('instructions_wrapper');
    
    let div = document.createElement('div');
    div.style.display = "flex";
    div.style.gap = "8px";
    
    div.innerHTML = `
        <input type="text" name="instructions[]" placeholder="Enter instruction"
            style="flex:1; padding:8px; border-radius:6px; border:1px solid #d1d5db;">
        
        <button type="button" onclick="removeInstruction(this)" 
            style="background:#ef4444; color:#fff; border:none; padding:6px 10px; border-radius:6px;">
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