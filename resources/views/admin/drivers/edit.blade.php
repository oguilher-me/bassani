@extends('layouts/contentNavbarLayout')

@section('title', __('Editar Motorista'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Editar Motorista') }}</h4>
        <p class="text-muted mb-0">{{ $driver->full_name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('drivers.show', $driver->id) }}" class="btn btn-outline-secondary">
            <i class="bx bx-show me-1"></i> {{ __('Ver Detalhes') }}
        </a>
        <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('drivers.update', $driver->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Dados Pessoais --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-user me-2 text-danger"></i>{{ __('Dados Pessoais') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="full_name" class="form-label">{{ __('Nome Completo') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $driver->full_name) }}" required>
                            @error('full_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $driver->user->email) }}" required>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="cpf" class="form-label">{{ __('CPF') }}</label>
                            <input type="text" class="form-control cpf" id="cpf" name="cpf" value="{{ old('cpf', $driver->cpf) }}" maxlength="14">
                            @error('cpf')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="phone" class="form-label">{{ __('Telefone') }}</label>
                            <input type="text" class="form-control phone-mask" id="phone" name="phone" value="{{ old('phone', $driver->phone) }}">
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Ativo" {{ (old('status', $driver->status) == 'Ativo') ? 'selected' : '' }}>{{ __('Ativo') }}</option>
                                <option value="Inativo" {{ (old('status', $driver->status) == 'Inativo') ? 'selected' : '' }}>{{ __('Inativo') }}</option>
                                <option value="Suspenso" {{ (old('status', $driver->status) == 'Suspenso') ? 'selected' : '' }}>{{ __('Suspenso') }}</option>
                            </select>
                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    {{-- Senha --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-lock me-2 text-danger"></i>{{ __('Alterar Senha') }} <small class="text-muted fw-normal">({{ __('deixe em branco para manter a atual') }})</small>
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label">{{ __('Nova Senha') }}</label>
                            <input type="password" class="form-control" id="password" name="password">
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">{{ __('Confirmar Nova Senha') }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    {{-- CNH --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-id-card me-2 text-danger"></i>{{ __('Carteira Nacional de Habilitação') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="cnh_number" class="form-label">{{ __('Número CNH') }}</label>
                            <input type="text" class="form-control cnh" id="cnh_number" name="cnh_number" value="{{ old('cnh_number', $driver->cnh_number) }}">
                            @error('cnh_number')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label for="cnh_category" class="form-label">{{ __('Categoria') }}</label>
                            <select class="form-select" id="cnh_category" name="cnh_category">
                                <option value="">{{ __('--') }}</option>
                                <option value="A" {{ (old('cnh_category', $driver->cnh_category) == 'A') ? 'selected' : '' }}>A</option>
                                <option value="B" {{ (old('cnh_category', $driver->cnh_category) == 'B') ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ (old('cnh_category', $driver->cnh_category) == 'AB') ? 'selected' : '' }}>AB</option>
                                <option value="C" {{ (old('cnh_category', $driver->cnh_category) == 'C') ? 'selected' : '' }}>C</option>
                                <option value="D" {{ (old('cnh_category', $driver->cnh_category) == 'D') ? 'selected' : '' }}>D</option>
                                <option value="E" {{ (old('cnh_category', $driver->cnh_category) == 'E') ? 'selected' : '' }}>E</option>
                                <option value="ACC" {{ (old('cnh_category', $driver->cnh_category) == 'ACC') ? 'selected' : '' }}>ACC</option>
                            </select>
                            @error('cnh_category')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="cnh_expiration_date" class="form-label">{{ __('Validade CNH') }}</label>
                            <input type="date" class="form-control" id="cnh_expiration_date" name="cnh_expiration_date" value="{{ old('cnh_expiration_date', $driver->cnh_expiration_date) }}">
                            @error('cnh_expiration_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-x me-1"></i> {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-check me-1"></i> {{ __('Atualizar') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Documents Section --}}
@php
    $documents = $driver->documents()->latest()->paginate(10);
@endphp
@include('admin.drivers.documents._section', ['driver' => $driver, 'documents' => $documents])
@endsection
