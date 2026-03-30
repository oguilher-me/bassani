@extends('layouts/contentNavbarLayout')

@section('title', 'Nova Oportunidade')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('Nova Oportunidade') }}</h4>
            <p class="text-muted mb-0">{{ __('CRM / Pipeline') }}</p>
        </div>
        <a href="{{ route('crm.pipeline.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bx bx-briefcase text-danger me-2"></i>{{ __('Detalhes da Oportunidade') }}
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('crm.pipeline.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    {{-- Lead/Cliente --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Lead / Cliente') }} *</label>
                        <select name="entity_id" class="form-select" required>
                            <option value="">{{ __('Selecione...') }}</option>
                            @foreach($entities as $entity)
                                <option value="{{ $entity->id }}">{{ $entity->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Título --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Título do Projeto') }} *</label>
                        <input type="text" name="title" class="form-control" placeholder="{{ __('Ex: Apto 302 - Ed. Solar') }}" required>
                    </div>

                    {{-- CPF/CNPJ --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('CPF/CNPJ') }}</label>
                        <input type="text" name="cpf_cnpj" class="form-control" placeholder="{{ __('Digite apenas números') }}">
                    </div>

                    {{-- Valor --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Valor Estimado (R$)') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" name="estimated_value" class="form-control" step="0.01" value="0">
                        </div>
                    </div>
                    
                    {{-- Probabilidade --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Probabilidade (%)') }}</label>
                        <div class="input-group">
                            <input type="number" name="probability" class="form-control" min="0" max="100" value="10">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>

                    {{-- Endereço --}}
                    <div class="col-12">
                        <label class="form-label">{{ __('Endereço do Projeto') }}</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="{{ __('Rua, Número, Bairro, Cidade...') }}"></textarea>
                    </div>

                    {{-- Arquiteto --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Arquiteto Parceiro') }}</label>
                        <select name="architect_id" class="form-select">
                            <option value="">{{ __('Nenhum / Não informado') }}</option>
                            @foreach($architects as $architect)
                                <option value="{{ $architect->id }}">{{ $architect->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tamanho --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Tamanho do Projeto') }}</label>
                        <input type="text" name="project_size" class="form-control" placeholder="{{ __('Ex: 150m²') }}">
                    </div>

                    {{-- Precisa de Projeto --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Precisa de desenvolvimento?') }}</label>
                        <div class="d-flex gap-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="needs_project_development" value="1" id="dev_yes">
                                <label class="form-check-label" for="dev_yes">{{ __('Sim') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="needs_project_development" value="0" id="dev_no" checked>
                                <label class="form-check-label" for="dev_no">{{ __('Não') }}</label>
                            </div>
                        </div>
                    </div>

                    {{-- Data Limite Projeto --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Prazo do Projeto') }}</label>
                        <input type="date" name="project_deadline" class="form-control">
                    </div>

                    {{-- Expectativa Fechamento --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Expectativa de Fechamento') }}</label>
                        <input type="date" name="expected_closing_date" class="form-control">
                    </div>

                    {{-- Vendedor --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Vendedor') }}</label>
                        <select name="seller_id" class="form-select">
                            <option value="">{{ __('Selecione o vendedor...') }}</option>
                            @foreach($sellers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Responsável --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Responsável pela Negociação') }}</label>
                        <select name="owner_id" class="form-select">
                            <option value="">{{ __('Selecione o responsável...') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ auth()->id() == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('crm.pipeline.index') }}" class="btn btn-outline-secondary">{{ __('Cancelar') }}</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-check me-1"></i> {{ __('Salvar Oportunidade') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
