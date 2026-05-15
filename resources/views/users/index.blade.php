@extends('layouts.app')

@section('content')

<div class="container">

    <div style="display:flex;justify-content:space-between;margin-bottom:20px;">
        <h2>Users</h2>

        <a href="{{ route('users.create') }}" class="btn btn-primary">
            Add User
        </a>
    </div>

    <table class="table table-bordered">

        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>

        <tbody>

            @foreach($users as $user)

            <tr>
                <td>{{ $user->id }}</td>

                <td>{{ $user->name }}</td>

                <td>{{ $user->email }}</td>

                <td>{{ $user->role->role_name ?? '-' }}</td>
            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection