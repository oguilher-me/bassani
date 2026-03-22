@extends('layouts/contentNavbarLayout')

@section('title', __('roles_permissions.edit_role') . ' - ' . __('roles_permissions.roles') . ' & ' . __('roles_permissions.permissions'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('roles_permissions.edit_role') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('roles_permissions.role_name') }}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('roles_permissions.description') }}</label>
                        <textarea class="form-control" id="description" name="description">{{ $role->description }}</textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">{{ __('roles_permissions.update') }}</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">{{ __('roles_permissions.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection