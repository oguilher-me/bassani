@extends('layouts/contentNavbarLayout')

@section('title', ($isEdit ? __('Editar') : __('Novo')) . ' ' . __('Vendedor'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ $isEdit ? __('Editar Vendedor') : __('Novo Vendedor') }}</h4>
        <p class="text-muted mb-0">{{ $isEdit ? __('Editar informações do vendedor') : __('Cadastrar novo vendedor') }}</p>
    </div>
    <a href="{{ route('crm.sellers.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ $isEdit ? route('crm.sellers.update', $seller->id) : route('crm.sellers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($isEdit) @method('PUT') @endif

                    {{-- Dados Pessoais --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-user me-2 text-danger"></i>{{ __('Dados Pessoais') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">{{ __('Nome Completo') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $seller->name ?? '') }}" required autofocus>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">{{ __('E-mail (Login)') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $seller->email ?? '') }}" placeholder="vendedor@bassani.com.br" required>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="cpf" class="form-label">{{ __('CPF') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control cpf" id="cpf" name="cpf" value="{{ old('cpf', $seller->cpf ?? '') }}" placeholder="000.000.000-00" required>
                            @error('cpf')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="phone" class="form-label">{{ __('WhatsApp / Telefone') }}</label>
                            <input type="text" class="form-control phone-mask" id="phone" name="phone" value="{{ old('phone', $seller->phone ?? '') }}" placeholder="(00) 00000-0000">
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="photo" class="form-label">{{ __('Foto de Perfil') }}</label>
                            <input type="file" class="form-control" id="photo" name="photo">
                            @error('photo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            @if($seller->photo ?? false)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($seller->photo) }}" alt="{{ $seller->name }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Comissão e Status --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-dollar me-2 text-danger"></i>{{ __('Comissão e Status') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="commission_percentage" class="form-label">{{ __('Porcentagem de Comissão') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" id="commission_percentage" name="commission_percentage" value="{{ old('commission_percentage', $seller->commission_percentage ?? 0) }}" required>
                                <span class="input-group-text">%</span>
                            </div>
                            @error('commission_percentage')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">{{ __('Status do Vendedor') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active" {{ old('status', $seller->status ?? 'active') === 'active' ? 'selected' : '' }}>{{ __('Ativo') }}</option>
                                <option value="inactive" {{ old('status', $seller->status ?? '') === 'inactive' ? 'selected' : '' }}>{{ __('Inativo') }}</option>
                            </select>
                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Segurança --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-lock-alt me-2 text-danger"></i>{{ __('Segurança (Login)') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label">
                                {{ __('Senha') }} 
                                @if($isEdit)
                                    <small class="text-muted">({{ __('deixe em branco para não alterar') }})</small>
                                @else
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            <input type="password" class="form-control" id="password" name="password" {{ $isEdit ? '' : 'required' }}>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">{{ __('Confirmar Senha') }} @if(!$isEdit)<span class="text-danger">*</span>@endif</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" {{ $isEdit ? '' : 'required' }}>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('crm.sellers.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-x me-1"></i> {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-check me-1"></i> {{ $isEdit ? __('Atualizar') : __('Salvar Vendedor') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection