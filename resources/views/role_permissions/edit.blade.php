@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Edit Permissions for: <strong>{{ $role->role_name }}</strong>
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('role-permissions.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to All Roles
                        </a>
                    </div>
                </div>

                <form action="{{ route('role-permissions.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    Select the permissions you want to assign to <strong>{{ $role->role_name }}</strong> role.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-3 mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="permission_{{ $permission->id }}" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}"
                                           {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="permission_{{ $permission->id }}">
                                        {{ ucwords(str_replace('_', ' ', $permission->permission_name)) }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($permissions->isEmpty())
                            <div class="alert alert-warning">
                                No permissions found. Please add permissions first.
                            </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" {{ $permissions->isEmpty() ? 'disabled' : '' }}>
                            <i class="fas fa-save"></i> Update Permissions
                        </button>
                        <a href="{{ route('role-permissions.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection