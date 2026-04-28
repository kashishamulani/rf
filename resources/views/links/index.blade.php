@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2 style="font-size:22px; font-weight:600;">
        <i class="fa-solid fa-file-lines" style="color:#6366f1;"></i> Forms Template
    </h2>

    <a href="{{ route('links.create') }}" style="display:flex; align-items:center; gap:8px; padding:10px 16px;
       background:linear-gradient(135deg,#6366f1,#ec4899);
       color:#fff; border-radius:10px; font-weight:600; text-decoration:none;">
        <i class="fa-solid fa-circle-plus"></i> Create Form
    </a>
</div>

{{-- SUCCESS MESSAGE --}}
@if(session('success'))
<div style="padding:10px 14px; background:#22c55e; color:white; border-radius:8px; margin-bottom:12px;">
    {{ session('success') }}
</div>
@endif

{{-- ERROR MESSAGE --}}
@if(session('error'))
<div style="padding:10px 14px; background:#ef4444; color:white; border-radius:8px; margin-bottom:12px;">
    {{ session('error') }}
</div>
@endif

{{-- TABLE --}}
<div style="overflow-x:auto;">
    <table style="width:100%; border-collapse:collapse; background:white;
            border-radius:12px;
            box-shadow:0 4px 12px rgba(0,0,0,0.05);">

        <thead>
            <tr style="background:#f9fafb; border-bottom:2px solid #e5e7eb; text-align:left;">
                <th style="padding:12px;">#</th>
                <th style="padding:12px;">Title</th>
                <th style="padding:12px;">Link</th>
                <th style="padding:12px; text-align:center;">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($forms as $index => $form)
            <tr style="border-bottom:1px solid #f1f5f9; vertical-align:middle;">

                <td style="padding:12px;">{{ $index + 1 }}</td>

                <td style="padding:12px; font-weight:600; color:#111827;">
                    {{ $form->title }}
                </td>

                <td style="padding:12px;">
                    <input type="text" value="{{ route('forms.show',$form->slug) }}" readonly
                        style="width:100%; padding:6px 10px; border:1px solid #e5e7eb; border-radius:6px; font-size:13px;">
                </td>

                {{-- ACTIONS --}}
                <td
                    style="padding:12px; text-align:center; display:flex; gap:14px; justify-content:center; align-items:center;">

                    {{-- OPEN --}}
                    <a href="{{ route('forms.show',$form->slug) }} " target="_blank" title="Open Form"
                        style="color:#22c55e;">
                        <i class="fa-solid fa-up-right-from-square"></i>
                    </a>

                    {{-- QR CODE --}}
                    <a href="javascript:void(0)"
                        onclick="openQR('{{ route('forms.show',$form->slug) }}', '{{ $form->title }}')"
                        title="View QR Code" style="color:#8b5cf6;">
                        <i class="fa-solid fa-qrcode"></i>
                    </a>

                    {{-- WHATSAPP --}}
                    <a href="https://wa.me/?text={{ urlencode(route('forms.show',$form->slug)) }}" target="_blank"
                        title="Share on WhatsApp" style="color:#25D366;">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>

                    {{-- RESPONSES --}}
                    <a href="{{ route('links.responses',$form->id) }}" title="View Responses" style="color:#f59e0b;">
                        <i class="fa-solid fa-chart-column"></i>
                    </a>

                    {{-- EDIT --}}
                    <a href="{{ route('links.edit', $form->id) }}" title="Edit Form" style="color:#3b82f6;">
                        <i class="fa-solid fa-pen"></i>
                    </a>


                    {{-- DELETE --}}
                    <form method="POST" action="{{ route('links.destroy', $form->id) }}"
                        onsubmit="return confirm('Delete this form?');">
                        @csrf
                        @method('DELETE')

                        <button type="submit" title="Delete Form"
                            style="background:none; border:none; color:#ef4444; cursor:pointer;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>

                </td>
            </tr>

            @empty
            <tr>
                <td colspan="7" style="padding:14px; text-align:center; color:#9ca3af;">
                    No Forms Found
                </td>
            </tr>
            @endforelse
        </tbody>

    </table>
</div>




{{-- QR MODAL --}}
<div id="qrModal" style="
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
    z-index:9999;
">

    <div style="
        background:#fff;
        padding:20px;
        border-radius:12px;
        width:320px;
        text-align:center;
        position:relative;
        animation:fadeIn 0.2s ease;
    ">

        {{-- CLOSE --}}
        <span onclick="closeQR()" style="
            position:absolute;
            top:10px; right:15px;
            cursor:pointer;
            font-size:18px;
        ">✖</span>

        <h3 id="qrTitle" style="margin-bottom:10px;"></h3>

        {{-- QR IMAGE --}}
        <img id="qrImage" src="" style="width:200px; height:200px;">

        {{-- LINK --}}
        <input id="qrLink" type="text" readonly style="width:100%; margin-top:10px; padding:6px;
            border:1px solid #e5e7eb; border-radius:6px; font-size:12px;">

        {{-- BUTTONS --}}
        <div style="margin-top:12px; display:flex; gap:8px; justify-content:center;">

            <a id="downloadQR" target="_blank" style="padding:8px 12px; background:#6366f1; color:#fff;
               border-radius:6px; text-decoration:none; font-size:12px;">
                Download
            </a>

            <button onclick="copyQRLink()" style="padding:8px 12px; background:#10b981; color:#fff;
                border:none; border-radius:6px; font-size:12px; cursor:pointer;">
                Copy Link
            </button>

        </div>
    </div>
</div>


<script>
function openQR(url, title) {

    const qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" + encodeURIComponent(url);

    document.getElementById('qrModal').style.display = 'flex';
    document.getElementById('qrImage').src = qrUrl;
    document.getElementById('qrTitle').innerText = title;
    document.getElementById('qrLink').value = url;
    document.getElementById('downloadQR').href = qrUrl;
}

function closeQR() {
    document.getElementById('qrModal').style.display = 'none';
}

function copyQRLink() {
    const input = document.getElementById('qrLink');
    input.select();
    document.execCommand('copy');
    alert('Link copied!');
}

// Close on outside click
window.onclick = function(e) {
    const modal = document.getElementById('qrModal');
    if (e.target === modal) {
        modal.style.display = "none";
    }
}
</script>
@endsection