@extends('layouts/contentNavbarLayout')

@section('title', __('Editar Motorista'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Editar Motorista') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('drivers.update', $driver->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="full_name" class="form-label">{{ __('Nome Completo') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $driver->full_name) }}" required>
                            @error('full_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $driver->user->email) }}" required>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="password" class="form-label">{{ __('Senha') }}</label>
                            <input type="password" class="form-control" id="password" name="password">
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="password_confirmation" class="form-label">{{ __('Confirmar Senha') }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" >
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="cpf" class="form-label">{{ __('CPF') }}</label>
                            <input type="text" class="form-control cpf" id="cpf" name="cpf" value="{{ old('cpf', $driver->cpf) }}" maxlength="14">
                            @error('cpf')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="cnh_number" class="form-label">{{ __('Número CNH') }}</label>
                            <input type="text" class="form-control cnh" id="cnh_number" name="cnh_number" value="{{ old('cnh_number', $driver->cnh_number) }}">
                            @error('cnh_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="cnh_category" class="form-label">{{ __('Categoria CNH') }}</label>
                            <select class="form-select select2" id="cnh_category" name="cnh_category">
                                <option value="">{{ __('Selecione a Categoria') }}</option>
                                <option value="A" {{ (old('cnh_category', $driver->cnh_category) == 'A') ? 'selected' : '' }}>A</option>
                                <option value="B" {{ (old('cnh_category', $driver->cnh_category) == 'B') ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ (old('cnh_category', $driver->cnh_category) == 'AB') ? 'selected' : '' }}>AB</option>
                                <option value="C" {{ (old('cnh_category', $driver->cnh_category) == 'C') ? 'selected' : '' }}>C</option>
                                <option value="D" {{ (old('cnh_category', $driver->cnh_category) == 'D') ? 'selected' : '' }}>D</option>
                                <option value="E" {{ (old('cnh_category', $driver->cnh_category) == 'E') ? 'selected' : '' }}>E</option>
                                <option value="ACC" {{ (old('cnh_category', $driver->cnh_category) == 'ACC') ? 'selected' : '' }}>ACC</option>
                            </select>
                            @error('cnh_category')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="cnh_expiration_date" class="form-label">{{ __('Validade CNH') }}</label>
                            <input type="date" class="form-control" id="cnh_expiration_date" name="cnh_expiration_date" value="{{ old('cnh_expiration_date', $driver->cnh_expiration_date) }}">
                            @error('cnh_expiration_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                        <div class="mb-3 col-md-3">
                            <label for="phone" class="form-label">{{ __('Telefone') }}</label>
                            <input type="text" class="form-control phone-mask" id="phone" name="phone" value="{{ old('phone', $driver->phone) }}">
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="status" name="status" required>
                                <option value="Ativo" {{ (old('status', $driver->status) == 'Ativo') ? 'selected' : '' }}>{{ __('Ativo') }}</option>
                                <option value="Inativo" {{ (old('status', $driver->status) == 'Inativo') ? 'selected' : '' }}>{{ __('Inativo') }}</option>
                                <option value="Suspenso" {{ (old('status', $driver->status) == 'Suspenso') ? 'selected' : '' }}>{{ __('Suspenso') }}</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">{{ __('Atualizar') }}</button>
                        <a href="{{ route('drivers.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@php
    $documents = $driver->documents()->latest()->paginate(10);
@endphp
@include('admin.drivers.documents._section', ['driver' => $driver, 'documents' => $documents])

@endsection
