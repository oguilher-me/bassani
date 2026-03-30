@extends('layouts/contentNavbarLayout')

@section('title', __('Cadastro de Montador'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Cadastro de Montador') }}</h4>
        <p class="text-muted mb-0">{{ __('Cadastrar novo montador') }}</p>
    </div>
    <a href="{{ route('assemblers.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('assemblers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Dados Pessoais --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-user me-2 text-danger"></i>{{ __('Dados Pessoais') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">{{ __('Nome Completo') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="cpf" class="form-label">{{ __('CPF') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control cpf" id="cpf" name="cpf" value="{{ old('cpf') }}" maxlength="14">
                            @error('cpf')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="phone" class="form-label">{{ __('Telefone') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control phone-mask" id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="type" class="form-label">{{ __('Tipo') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">{{ __('Selecione o Tipo') }}</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                                @endforeach
                            </select>
                            @error('type')
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

                    {{-- Endereço --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-map me-2 text-danger"></i>{{ __('Endereço') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="address" class="form-label">{{ __('Endereço') }}</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="city" class="form-label">{{ __('Cidade') }}</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}">
                            @error('city')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="state" class="form-label">{{ __('Estado') }}</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}">
                            @error('state')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Status --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-check-circle me-2 text-danger"></i>{{ __('Status') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">{{ __('Ativo') }}</label>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('assemblers.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-x me-1"></i> {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-check me-1"></i> {{ __('Salvar') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection