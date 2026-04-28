<h2>Requests</h2>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Whatsapp</th>
            <th>City</th>
            <th>Email</th>
            <th>DOB</th>
            <th>Pincode</th>
            <th>Address</th>
            <th>Pan</th>
            <th>Aadhar</th>
            <th>10th</th>
            <th>Parents</th>
            <th>Resume</th>
            <th>Samarth</th>
            <th>Pan Card</th>
            <th>Adhar Front</th>
            <th>Adhar Back</th>
            <th>12th</th>
            <th>Bank</th>
            <th>Photo</th>
            <th>Signature</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requests as $r)
        <tr>
            <td>{{ $r['sr'] }}</td>
            <td>{{ $r['name'] }}</td>
            <td>{{ $r['number'] }}</td>
            <td>{{ $r['wnumber'] }}</td>
            <td>{{ $r['city'] }}</td>
            <td>{{ $r['email'] }}</td>
            <td>{{ $r['dob'] }}</td>
            <td>{{ $r['pincode'] }}</td>
            <td>{{ $r['address'] }}</td>
            <td>{{ $r['pannumber'] }}</td>
            <td>{{ $r['aadhar'] }}</td>
            <td>{{ $r['tenthpassing'] }}</td>
            <td>{{ $r['parents'] }}</td>
            <td>
                @if($r['files']['resume'])
                    <a href="{{ url($r['files']['resume']) }}" target="_blank">View</a>
                @endif
            </td>
            <td>
                @if($r['files']['samarth'])
                    <a href="{{ url($r['files']['samarth']) }}" target="_blank">View</a>
                @endif
            </td>
            <td>
                @if($r['files']['pan'])
                    <a href="{{ url($r['files']['pan']) }}" target="_blank">View</a>
                @endif
            </td>
            <td>
                @if($r['files']['adhar_front'])
                    <a href="{{ url($r['files']['adhar_front']) }}" target="_blank">View</a>
                @endif
            </td>
            <td>
                @if($r['files']['adhar_back'])
                    <a href="{{ url($r['files']['adhar_back']) }}" target="_blank">View</a>
                @endif
            </td>
            <td>
                @if($r['files']['twelvemarksheet'])
                    <a href="{{ url($r['files']['twelvemarksheet']) }}" target="_blank">View</a>
                @endif
            </td>
            <td>
                @if($r['files']['bank'])
                    <a href="{{ url($r['files']['bank']) }}" target="_blank">View</a>
                @endif
            </td>
            <td>
                @if($r['files']['photo'])
                    <a href="{{ url($r['files']['photo']) }}" target="_blank">View</a>
                @endif
            </td>
            <td>
                @if($r['files']['signature'])
                    <a href="{{ url($r['files']['signature']) }}" target="_blank">View</a>
                @endif
            </td>
            <td>{{ $r['created_at'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
