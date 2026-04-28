    @extends('layouts.app')

@section('content')

<div style="max-width:600px;margin:40px auto;background:#fff;padding:25px;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.1);">

    <h2 style="margin-bottom:10px;">Send Form to Candidate</h2>

    <p><b>Name:</b> {{ $mobilization->name }}</p>
    <p><b>Mobile:</b> {{ $mobilization->mobile }}</p>

    <hr style="margin:15px 0;">

    <label>Select Form</label>

    <select id="formSelect" style="width:100%;padding:10px;margin-top:8px;">
        <option value="">-- Select Form --</option>
        @foreach($forms as $form)
            <option value="{{ $form->slug }}">
                {{ $form->title }}
            </option>
        @endforeach
    </select>

    <button onclick="generateLink()" style="margin-top:15px;padding:10px 15px;background:#6366f1;color:#fff;border:none;border-radius:6px;">
        Generate Link
    </button>

    <div id="linkBox" style="margin-top:20px;display:none;">

        <label>Form Link</label>

        <input type="text" id="formLink" readonly style="width:100%;padding:10px;">

        <button onclick="copyLink()" style="margin-top:10px;padding:8px 12px;background:#16a34a;color:#fff;border:none;border-radius:6px;">
            Copy Link
        </button>

        <a id="openLink" target="_blank" style="margin-left:10px;color:#2563eb;">
            Open Form
        </a>

    </div>

</div>

<script>
function generateLink() {

    let slug = document.getElementById('formSelect').value;

    if (!slug) {
        alert('Select form first');
        return;
    }

   let link = "{{ url('/form') }}/" + slug + "?mobilization_id={{ $mobilization->id }}";
    document.getElementById('formLink').value = link;
    document.getElementById('openLink').href = link;

    document.getElementById('linkBox').style.display = 'block';
}

function copyLink() {
    let input = document.getElementById('formLink');
    input.select();
    document.execCommand('copy');
    alert('Copied!');
}
</script>

@endsection