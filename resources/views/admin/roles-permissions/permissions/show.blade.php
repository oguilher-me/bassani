@extends('layouts/contentNavbarLayout')

@section('title', __('roles_permissions.permission_details') . ' - ' . __('roles_permissions.roles') . ' & ' . __('roles_permissions.permissions'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('roles_permissions.permission_details') }}</h5>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ __('roles_permissions.permission_colon') }} {{ $permission->name }}</h5>
                <p class="card-text">{{ __('roles_permissions.description') }}: {{ $permission->description }}</p>
                <a href="{{ route('permissions.index') }}" class="btn btn-primary">{{ __('roles_permissions.back_to_permissions') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection