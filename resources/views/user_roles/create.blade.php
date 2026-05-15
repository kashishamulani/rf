@extends('layouts.app')

@section('content')

<div class="container">

    <h2>Add Role</h2>

    <form action="{{ route('user-roles.store') }}" method="POST">

        @csrf

        <div class="mb-3">

            <label>Role Name</label>

            <input type="text"
                   name="role_name"
                   class="form-control">

        </div>

        <button class="btn btn-success">
            Save Role
        </button>

    </form>

</div>

@endsection