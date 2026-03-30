@extends('layouts/contentNavbarLayout')

@section('title', __('Novo Usuário'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Novo Usuário') }}</h4>
        <p class="text-muted mb-0">{{ __('Cadastrar novo usuário do sistema') }}</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    {{-- Dados Pessoais --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-user me-2 text-danger"></i>{{ __('Dados Pessoais') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">{{ __('Nome') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ is_array(old('name')) ? '' : old('name') }}" required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ is_array(old('email')) ? '' : old('email') }}" required>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Senha --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-lock-alt me-2 text-danger"></i>{{ __('Senha de Acesso') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label">{{ __('Senha') }} <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">{{ __('Confirmar Senha') }} <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Perfil e Status --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-shield me-2 text-danger"></i>{{ __('Perfil e Status') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="role_id" class="form-label">{{ __('Perfil') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="role_id" name="role_id" required>
                                <option value="">{{ __('Selecione o Perfil') }}</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>{{ __('Ativo') }}</option>
                                <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>{{ __('Inativo') }}</option>
                            </select>
                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-x me-1"></i> {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-check me-1"></i> {{ __('Salvar Usuário') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection