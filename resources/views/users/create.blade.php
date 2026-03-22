@extends('layouts/contentNavbarLayout')

@section('title', __('users.create_user') . ' - ' . __('users.users'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('users.create_new_user') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="row">

                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">{{ __('users.name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ is_array(old('name')) ? '' : old('name') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">{{ __('users.email') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ is_array(old('email')) ? '' : old('email') }}" required>
                            @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="password" class="form-label">{{ __('users.password') }} <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="password_confirmation" class="form-label">{{ __('users.confirm_password') }} <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="role_id" class="form-label">{{ __('users.role') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="role_id" name="role_id" required>
                                <option value="">{{ __('users.select_role') }}</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="status" class="form-label">{{ __('users.status') }} <span class="text-danger">*</span></label>
                        <select class="form-select select2" id="status" name="status" required>
                            <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>{{ __('users.active') }}</option>
                            <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>{{ __('users.inactive') }}</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">{{ __('users.submit') }}</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ __('users.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection