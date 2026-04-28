@extends('layouts.app')

@section('content')

<style>
.form-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(14px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    padding: 24px;
    width: 100%;
    max-width: 1000px;
}

.label {
    font-weight: 600;
    color: #4338ca;
    font-size: 14px;
}

.table-title {
    font-weight: 600;
    color: #4338ca;
    margin-top: 24px;
}

.remark-box {
    border-bottom: 1px solid #e5e7eb;
    padding: 14px 0;
}

.remark-text {
    font-size: 14px;
}

.remark-date {
    font-size: 12px;
    color: #6b7280;
    margin-top: 6px;
}

input,
select,
textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    font-size: 14px;
}

input:focus,
select:focus,
textarea:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
}

.btn-primary {
    margin-top: 12px;
    background: linear-gradient(135deg, #6366f1, #ec4899);
    color: white;
    border: none;
    padding: 8px 18px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 500;
}

.btn-back {
    color: #6b7280;
    text-decoration: none;
    font-size: 14px;
}

.success-box {
    background: #ecfdf5;
    padding: 10px;
    border-radius: 10px;
    color: #065f46;
    margin-bottom: 15px;
}
</style>

<div style="padding:12px; display:flex; justify-content:center;">

    <div class="form-card">

        {{-- HEADER --}}

        <div style="display:flex; justify-content:space-between; margin-bottom:16px; align-items:center;">
            <h2 style="background:linear-gradient(135deg,#6366f1,#ec4899);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;">
                Call Remarks - {{ $mobilization->name }}
            </h2>

            <a href="{{ route('mobilizations.index') }}" class="btn-back">← Back</a>

        </div>

        @if(session('success'))

        <div class="success-box">
            {{ session('success') }}
        </div>
        @endif

        {{-- ADD CALL REMARK --}}

        <form method="POST" action="{{ route('mobilizations.storeRemark',$mobilization->id) }}">
            @csrf

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">

                <div>
                    <div class="label">Calling Date</div>
                    <input type="date" name="calling_date" required>
                </div>

                <div>
                    <div class="label">Calling Time</div>
                    <input type="time" name="calling_time" required>
                </div>

                <div>
                    <div class="label">Calling Mode</div>
                    <select name="calling_mode" required>
                        <option value="">Select</option>
                        <option value="Tele Calling">Tele Calling</option>
                        <option value="WhatsApp">WhatsApp</option>
                        <option value="Message">Message</option>
                        <option value="Email">Email</option>
                    </select>
                </div>

                <div>
                    <div class="label">Call Action</div>
                    <select name="call_action" required>
                        <option value="">Select</option>
                        <option value="Connected">Connected</option>
                        <option value="Not Answer">Not Answer</option>
                        <option value="Invalid Number">Invalid Number</option>
                        <option value="Disconnected">Disconnected</option>
                    </select>
                </div>

                <div>
                    <div class="label">Call Response</div>
                    <select name="call_response" required>
                        <option value="">Select</option>
                        <option value="Not Responded">Not Responded</option>
                        <option value="Not Interested">Not Interested</option>
                        <option value="Partially Interested">Partially Interested</option>
                        <option value="Interested">Interested</option>
                        <option value="Language Barrier">Language Barrier</option>
                        <option value="Not Reachable">Not Reachable</option>
                        <option value="Confirmed">Confirmed</option>
                    </select>
                </div>

                <div>
                    <div class="label">Next Followup Date</div>
                    <input type="date" name="next_followup_date">
                </div>

                <div>
                    <div class="label">Tags</div>

                    <div id="tag-container"
                        style="border:1px solid #e5e7eb;padding:8px;border-radius:10px;min-height:40px;"></div>

                    <div style="display:flex; gap:6px; margin-top:6px;">
                        <input type="text" id="tag-input" placeholder="Enter tag..."
                            style="flex:1; padding:8px; border:1px solid #e5e7eb; border-radius:8px;">

                        <button type="button" onclick="addTag()"
                            style="background:#6366f1;color:white;border:none;padding:8px 12px;border-radius:8px;cursor:pointer;">
                            Add
                        </button>
                    </div>

                    <input type="hidden" name="tag" id="tags-hidden">

                </div>

                <div>
                    <div class="label">Status</div>
                    <select name="status" required>
                        <option value="">Select Status</option>
                        <option value="Interested">Interested</option>
                        <option value="Not Interested">Not Interested</option>
                        <option value="Joined">Joined</option>
                        <option value="Unsubscribe">Unsubscribe</option>
                    </select>
                </div>

            </div>

            <div style="margin-top:12px;">
                <div class="label">Notes</div>
                <textarea name="notes" rows="3" placeholder="Enter notes..."></textarea>
            </div>

            <button type="submit" class="btn-primary">
                Save Call Remark
            </button>

        </form>

        {{-- HISTORY --}}

        <div class="table-title">Call History</div>

        @if($remarks->count())

        @foreach($remarks as $remark)

        <div class="remark-box">

            <div style="display:flex; justify-content:space-between;">

                <span style="background:#eef2ff;padding:4px 10px;border-radius:20px;font-size:12px;color:#4338ca;">
                    {{ $remark->status }}
                </span>

                <span style="font-size:12px;color:#6b7280;">
                    {{ $remark->calling_date }} {{ $remark->calling_time }}
                </span>

            </div>

            <div class="remark-text" style="margin-top:6px;">
                <strong>Mode:</strong> {{ $remark->calling_mode }} |
                <strong>Action:</strong> {{ $remark->call_action }} |
                <strong>Response:</strong> {{ $remark->call_response }}
            </div>

            @if($remark->tag)
            <div class="remark-text" style="margin-top:6px;">
                <strong>Tags:</strong>

                @foreach(explode(',', $remark->tag) as $tag)

                <span
                    style="background:#eef2ff;padding:4px 10px;border-radius:20px;font-size:12px;margin-right:4px;color:#4338ca;">
                    {{ $tag }}
                </span>

                @endforeach

            </div>
            @endif

            @if($remark->notes)

            <div class="remark-text" style="margin-top:6px;">
                {{ $remark->notes }}
            </div>
            @endif

            @if($remark->next_followup_date)

            <div class="remark-date">
                Next Followup : {{ $remark->next_followup_date }}
            </div>
            @endif

            <div class="remark-date">
                {{ $remark->created_at->format('d M Y h:i A') }}
            </div>

        </div>

        @endforeach

        @else

        <p style="color:#9ca3af; margin-top:10px;">
            No call history found.
        </p>

        @endif

    </div>

</div>


<script>

let tags = [];

function addTag() {

    let input = document.getElementById('tag-input');
    let value = input.value.trim();

    if(value === '') return;

    tags.push(value);
    input.value = '';

    renderTags();
}

function removeTag(index){
    tags.splice(index,1);
    renderTags();
}

function renderTags(){

    let container = document.getElementById('tag-container');
    container.innerHTML = '';

    tags.forEach((tag,index)=>{

        let span = document.createElement('span');

        span.innerHTML = tag + 
        ' <span style="cursor:pointer;margin-left:6px;" onclick="removeTag('+index+')">✕</span>';

        span.style.background = '#eef2ff';
        span.style.padding = '4px 10px';
        span.style.margin = '4px';
        span.style.borderRadius = '20px';
        span.style.fontSize = '12px';
        span.style.display = 'inline-block';

        container.appendChild(span);

    });

    document.getElementById('tags-hidden').value = tags.join(',');
}

</script>

@endsection