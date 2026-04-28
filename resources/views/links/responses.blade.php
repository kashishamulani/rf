@extends('layouts.app')

@section('content')

<div style="max-width:1100px; margin:auto; padding:20px;">

    @if(session('success'))
    <div style="background:#d1fae5; color:#065f46; padding:10px; border-radius:6px; margin-bottom:15px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background:#fee2e2; color:#991b1b; padding:10px; border-radius:6px; margin-bottom:15px;">
        {{ session('error') }}
    </div>
    @endif

    {{-- Header --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">

        <h2 style="margin:0;">
            {{ $form->title }} - Responses
        </h2>

        <div style="display:flex; align-items:center; gap:10px;">
            <span style="background:#6366f1; color:#fff; padding:6px 12px; border-radius:6px;">
                Total: {{ $form->responses->count() }}
            </span>

            <a href="{{ route('links.index') }}" style="padding:6px 12px;
                background:#e5e7eb;
                color:#374151;
                border-radius:6px;
                text-decoration:none;
                font-size:13px;">
                ← Back
            </a>
        </div>

    </div>

    {{-- Bulk Delete Form --}}
    <form action="{{ route('responses.bulkDelete') }}" method="POST">
        @csrf

        <div style="margin-bottom:10px;">
            <button type="submit" id="bulkDeleteBtn"
                onclick="return confirm('Delete selected responses?')"
                style="background:#ef4444; color:#fff; padding:6px 12px; border:none; border-radius:5px; cursor:pointer;">
                Bulk Delete
            </button>
        </div>

        {{-- Table --}}
        <div style="background:#fff; border-radius:10px; padding:15px; box-shadow:0 4px 10px rgba(0,0,0,0.05);">

            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="text-align:left; border-bottom:1px solid #ddd;">
                        <th style="padding:8px;">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th style="padding:8px;">#</th>
                        <th style="padding:8px;">Name</th>
                        <th style="padding:8px;">Mobile</th>
                        <th style="padding:8px;">Location</th>
                        <th style="padding:8px;">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($form->responses as $index => $response)

                    @php
                    $name = '—';
                    $mobile = '—';
                    $location = '—';
                    @endphp

                    @foreach($response->values as $val)

                    @if(!$val->field)
                    @continue
                    @endif

                    @php
                    $label = strtolower($val->field->label);
                    @endphp

                    @if(str_contains($label, 'name'))
                    @php $name = $val->value; @endphp
                    @endif

                    @if(str_contains($label, 'mobile') || str_contains($label, 'phone'))
                    @php $mobile = $val->value; @endphp
                    @endif

                    @if(str_contains($label, 'location') || str_contains($label, 'city'))
                    @php $location = $val->value; @endphp
                    @endif

                    @endforeach

                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:8px;">
                            <input type="checkbox" name="ids[]" value="{{ $response->id }}" class="rowCheckbox">
                        </td>
                        <td style="padding:8px;">{{ $index + 1 }}</td>
                        <td style="padding:8px;">{{ $name }}</td>
                        <td style="padding:8px;">{{ $mobile }}</td>
                        <td style="padding:8px;">{{ $location }}</td>

                        <td style="padding:8px; display:flex; gap:6px;">

                            {{-- VIEW --}}
                            <a href="{{ route('responses.view', $response->id) }}"
                                style="background:#10b981; color:#fff; padding:5px 10px; border-radius:5px; text-decoration:none;">
                                View
                            </a>

                            {{-- DELETE (FIXED - NO FORM) --}}
                            <button type="button"
                                onclick="deleteSingle({{ $response->id }})"
                                style="background:#ef4444; color:#fff; padding:5px 10px; border:none; border-radius:5px; cursor:pointer;">
                                Delete
                            </button>

                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" style="padding:10px; text-align:center;">
                            No responses yet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </form>

</div>

{{-- SCRIPT --}}
<script>

// SELECT ALL LOGIC
const selectAll = document.getElementById('selectAll');
const bulkBtn = document.getElementById('bulkDeleteBtn');

function updateBulkButtonState() {
    const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
    const checkedCheckboxes = document.querySelectorAll('.rowCheckbox:checked');

    bulkBtn.disabled = checkedCheckboxes.length === 0;

    if (selectAll) {
        selectAll.checked = rowCheckboxes.length > 0 && checkedCheckboxes.length === rowCheckboxes.length;
        selectAll.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < rowCheckboxes.length;
    }
}

if (selectAll) {
    selectAll.addEventListener('change', function () {
        const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
        rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBulkButtonState();
    });
}

document.querySelectorAll('.rowCheckbox').forEach(cb => {
    cb.addEventListener('change', updateBulkButtonState);
});

updateBulkButtonState();


// SINGLE DELETE FIX (NO NESTED FORM)
function deleteSingle(id) {
    if (!confirm('Are you sure you want to delete this response?')) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/responses/' + id;

    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';

    const method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'DELETE';

    form.appendChild(csrf);
    form.appendChild(method);

    document.body.appendChild(form);
    form.submit();
}

</script>

@endsection