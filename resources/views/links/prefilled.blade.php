@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <div class="form-card" style="background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 30px;">
        
        <div style="margin-bottom: 20px; padding: 15px; background: #f3f4f6; border-radius: 8px;">
            <h3>Prefilling from: {{ $mobilization->name }}</h3>
            <p style="margin-top: 5px; color: #6b7280;">Some fields are prefilled from your mobilization data. You can edit them if needed.</p>
        </div>
        
        <form method="POST" action="{{ route('forms.submit.prefilled', [$form->slug, $mobilization->id]) }}" enctype="multipart/form-data">
            @csrf
            
            @foreach($form->fields as $field)
                @php
                    $fieldId = 'field_' . $field->id;
                    $prefilledValue = $prefilledData[$field->id] ?? '';
                @endphp
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                        {{ $field->label }}
                        @if($field->is_required)
                            <span style="color: #ef4444;">*</span>
                        @endif
                    </label>
                    
                    @if($field->type == 'text' || $field->type == 'email' || $field->type == 'number')
                        <input type="{{ $field->type }}" 
                               name="{{ $fieldId }}" 
                               value="{{ old($fieldId, $prefilledValue) }}"
                               @if($field->is_required) required @endif
                               style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px;">
                    
                    @elseif($field->type == 'textarea')
                        <textarea name="{{ $fieldId }}" 
                                  rows="4"
                                  @if($field->is_required) required @endif
                                  style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px;">{{ old($fieldId, $prefilledValue) }}</textarea>
                    
                    @elseif($field->type == 'select')
                        <select name="{{ $fieldId }}" 
                                @if($field->is_required) required @endif
                                style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px;">
                            <option value="">Select {{ $field->label }}</option>
                            @if($field->options)
                                @foreach($field->options as $option)
                                    <option value="{{ $option }}" 
                                        {{ old($fieldId, $prefilledValue) == $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    
                    @elseif($field->type == 'date')
                        <input type="date" 
                               name="{{ $fieldId }}" 
                               value="{{ old($fieldId, $prefilledValue) }}"
                               @if($field->is_required) required @endif
                               style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px;">
                    
                    @elseif($field->type == 'file')
                        <input type="file" 
                               name="{{ $fieldId }}" 
                               @if($field->is_required) required @endif
                               style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px;">
                        @if($prefilledValue)
                            <div style="margin-top: 8px; font-size: 13px; color: #6b7280;">
                                <div>Current file: {{ basename($prefilledValue) }}</div>
                                <a href="{{ route('document.view', ['path' => $prefilledValue]) }}" target="_blank" style="color: #2563eb; text-decoration: underline;">View existing file</a>
                            </div>
                        @endif
                    
                    @elseif($field->type == 'checkbox')
                        <div>
                            @if($field->options)
                                @foreach($field->options as $option)
                                    <label style="display: inline-block; margin-right: 15px;">
                                        <input type="checkbox" 
                                               name="{{ $fieldId }}[]" 
                                               value="{{ $option }}"
                                               @if(in_array($option, old($fieldId, is_array($prefilledValue) ? $prefilledValue : []))) checked @endif>
                                        {{ $option }}
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
            
            <div style="margin-top: 30px; display: flex; gap: 10px; justify-content: flex-end;">
                <a href="{{ route('mobilizations.index') }}" class="btn btn-cancel" style="padding: 10px 20px; background: #e5e7eb; color: #374151; text-decoration: none; border-radius: 8px;">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary" style="padding: 10px 20px; background: linear-gradient(135deg,#6366f1,#ec4899); color: white; border: none; border-radius: 8px; cursor: pointer;">
                    Submit Form
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-card {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection