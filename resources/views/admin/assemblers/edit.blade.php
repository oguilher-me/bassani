@extends('layouts/contentNavbarLayout')

@section('title', __('Editar Montador'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Editar Montador') }}</h4>
        <p class="text-muted mb-0">{{ $assembler->name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('assemblers.show', $assembler->id) }}" class="btn btn-outline-secondary">
            <i class="bx bx-show me-1"></i> {{ __('Ver Detalhes') }}
        </a>
        <a href="{{ route('assemblers.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('assemblers.update', $assembler->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Dados Pessoais --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-user me-2 text-danger"></i>{{ __('Dados Pessoais') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">{{ __('Nome Completo') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $assembler->name) }}" required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $assembler->email) }}" required>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="cpf" class="form-label">{{ __('CPF') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control cpf" id="cpf" name="cpf" value="{{ old('cpf', $assembler->cpf) }}" maxlength="14">
                            @error('cpf')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="phone" class="form-label">{{ __('Telefone') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control phone-mask" id="phone" name="phone" value="{{ old('phone', $assembler->phone) }}">
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="type" class="form-label">{{ __('Tipo') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">{{ __('Selecione o Tipo') }}</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->value }}" {{ old('type', $assembler->type->value) == $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
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
                            <label for="password" class="form-label">{{ __('Senha') }}</label>
                            <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" placeholder="{{ __('Deixe em branco para manter a atual') }}">
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">{{ __('Confirmar Senha') }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
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
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $assembler->address) }}">
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="city" class="form-label">{{ __('Cidade') }}</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $assembler->city) }}">
                            @error('city')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="state" class="form-label">{{ __('Estado') }}</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $assembler->state) }}">
                            @error('state')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Foto e Status --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-image me-2 text-danger"></i>{{ __('Foto e Status') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="photo" class="form-label">{{ __('Foto') }}</label>
                            <input type="file" class="form-control" id="photo" name="photo">
                            @error('photo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            @if ($assembler->photo)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($assembler->photo) }}" alt="{{ $assembler->name }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">{{ __('Status') }}</label>
                            <select class="form-select" id="status" name="status">
                                <option value="1" {{ old('status', $assembler->status) == '1' ? 'selected' : '' }}>{{ __('Ativo') }}</option>
                                <option value="0" {{ old('status', $assembler->status) == '0' ? 'selected' : '' }}>{{ __('Inativo') }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('assemblers.index') }}" class="btn btn-outline-secondary">
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
@endsection