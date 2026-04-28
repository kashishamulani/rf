@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card">

        <div class="card-header">
            <h4>Team Member Task Report</h4>
        </div>

        <div class="card-body">

            {{-- MEMBER FILTER --}}
            <form method="GET">
                <select name="member_id" class="form-control mb-3" onchange="this.form.submit()">
                    <option value="">Select Team Member</option>

                    @foreach($members as $m)
                    <option value="{{$m->id}}" {{request('member_id')==$m->id?'selected':''}}>
                        {{$m->name}}
                    </option>
                    @endforeach
                </select>
            </form>

            {{-- TABLE --}}
            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>Assignment</th>
                        <th>Phase</th>
                        <th>Activity</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>Target Date</th>
                        <th>Remark</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @if($reportData->count())

                    @foreach($reportData as $row)

                    <form method="POST" action="{{ route('team-task-report.update',$row['id']) }}">
                        @csrf

                        <tr>

                            <td>{{ $row['assignment'] }}</td>

                            <td>{{ $row['phases'] }}</td>

                            <td>{{ $row['activities'] }}</td>

                            <td>
                                <select name="status" class="form-control">
                                    <option value="Open" {{ $row['status'] == 'Open' ? 'selected':'' }}>Open</option>
                                    <option value="In Progress" {{ $row['status'] == 'In Progress' ? 'selected':'' }}>In
                                        Progress</option>
                                    <option value="Closed" {{ $row['status'] == 'Closed' ? 'selected':'' }}>Closed
                                    </option>
                                    <option value="Hold" {{ $row['status'] == 'Hold' ? 'selected':'' }}>Hold</option>
                                </select>
                            </td>

                            <td>{{ $row['start'] }}</td>

                            <td>{{ $row['target'] }}</td>

                            <td>
                                <input type="text" name="remark" value="{{ $row['remark'] }}" class="form-control">
                            </td>

                            <td>
                                <button type="submit" class="btn btn-success btn-sm">
                                    Save
                                </button>
                            </td>

                        </tr>
                    </form>

                    @endforeach

                    @else
                    <tr>
                        <td colspan="8" class="text-center">Select a Team Member to View Report</td>
                    </tr>
                    @endif

                </tbody>

            </table>

        </div>
    </div>
</div>

@endsection