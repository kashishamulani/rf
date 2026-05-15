@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h3 class="card-title">
                Role Permissions
            </h3>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @foreach($roles as $role)

                <div class="card mb-4 border">

                    <div class="card-header bg-dark text-white">
                        <strong>{{ $role->role_name }}</strong>
                    </div>

                    <div class="card-body">

                        <form action="{{ route('role-permissions.update') }}"
                              method="POST">

                            @csrf

                            <input type="hidden"
                                   name="role_id"
                                   value="{{ $role->id }}">

                            <div class="row">

                                @foreach($permissions as $permission)

                                    @php
                                        $checked = $rolePermissions
                                            ->where('role_id', $role->id)
                                            ->where('permission_id', $permission->id)
                                            ->count();
                                    @endphp

                                    <div class="col-md-3 mb-3">

                                        <div class="form-check">

                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   name="permissions[]"
                                                   value="{{ $permission->id }}"
                                                   id="p{{ $role->id }}{{ $permission->id }}"
                                                   {{ $checked ? 'checked' : '' }}>

                                            <label class="form-check-label"
                                                   for="p{{ $role->id }}{{ $permission->id }}">

                                                {{ ucfirst($permission->name) }}

                                            </label>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                            <button class="btn btn-primary mt-3">
                                Update Permissions
                            </button>

                        </form>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>

@endsection