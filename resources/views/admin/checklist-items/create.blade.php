@extends('layouts/contentNavbarLayout')

@section('title', __('Novo Item do Checklist'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Novo Item do Checklist') }}</h4>
        <p class="text-muted mb-0">{{ __('Cadastre um novo item para check-up de veículos') }}</p>
    </div>
    <a href="{{ route('checklist-items.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('checklist-items.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('Descrição') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" 
                               id="description" name="description" 
                               value="{{ old('description') }}" 
                               placeholder="Ex.: Verificar nível de óleo" required>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Item Restritivo') }}</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_restrictive" name="is_restrictive" 
                                   value="1" {{ old('is_restrictive') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_restrictive">
                                {{ __('Se houver falha neste item, bloqueia o uso do veículo') }}
                            </label>
                        </div>
                        <small class="text-muted">{{ __('Itens restritivos impedem a liberação do veículo quando apresentam falha.') }}</small>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>{{ __('Ativo') }}</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>{{ __('Inativo') }}</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('checklist-items.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('Cancelar') }}</a>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bx bx-save me-1"></i> {{ __('Salvar') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-info-circle text-primary me-2"></i>{{ __('Informações') }}
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2">
                        <i class="bx bx-check text-success me-2"></i>
                        {{ __('Descreva claramente o item a ser verificado') }}
                    </li>
                    <li class="mb-2">
                        <i class="bx bx-check text-success me-2"></i>
                        {{ __('Marque como restritivo se a falha bloquear o veículo') }}
                    </li>
                    <li class="mb-0">
                        <i class="bx bx-check text-success me-2"></i>
                        {{ __('Itens inativos não aparecem no formulário do motorista') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
