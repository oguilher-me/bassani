@extends('layouts/contentNavbarLayout')

@section('title', __('users.user_details') . ' - ' . __('users.users'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('users.user_details') }}</h5>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ __('users.name_colon') }} {{ $user->name }}</h5>
                <p class="card-text">{{ __('users.email') }}: {{ $user->email }}</p>
                <p class="card-text">{{ __('users.role') }}: {{ $user->role->name ?? 'N/A' }}</p>
                <p class="card-text">{{ __('users.status') }}: 
                    @if ($user->status)
                        <span class="badge bg-label-success">{{ __('users.active') }}</span>
                    @else
                        <span class="badge bg-label-danger">{{ __('users.inactive') }}</span>
                    @endif
                </p>
                <a href="{{ route('users.index') }}" class="btn btn-primary">{{ __('users.back_to_users') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection