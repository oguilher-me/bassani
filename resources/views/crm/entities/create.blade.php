@extends('layouts/contentNavbarLayout')

@php
    $typeLabel = match($type ?? '') {
        'lead' => __('Lead'),
        'architect' => __('Arquiteto'),
        'partner' => __('Parceiro'),
        default => __('Entidade')
    };
    $cancelRoute = match($type ?? '') {
        'lead' => route('crm.leads.index'),
        'architect' => route('crm.architects.index'),
        'partner' => route('crm.partners.index'),
        default => route('crm.entities.index')
    };
@endphp

@section('title', 'Novo ' . $typeLabel)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('Novo') }} {{ $typeLabel }}</h4>
            <p class="text-muted mb-0">CRM / {{ $typeLabel }}</p>
        </div>
        <a href="{{ $cancelRoute }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-10 col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bx bx-user-plus text-danger me-2"></i>{{ __('Dados do') }} {{ $typeLabel }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('crm.entities.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            {{-- Nome --}}
                            <div class="col-12">
                                <label class="form-label">{{ __('Nome Completo / Razão Social') }} *</label>
                                <input type="text" name="name" class="form-control" placeholder="{{ __('Ex: Arq. João Silva ou Residência Souza') }}" required>
                            </div>
                            
                            {{-- Tipo --}}
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Tipo') }}</label>
                                @if(isset($type))
                                    <input type="hidden" name="type" value="{{ $type }}">
                                    <input type="text" class="form-control bg-light" value="{{ ucfirst($type) }}" disabled>
                                @else
                                    <select name="type" class="form-select" required>
                                        <option value="lead">{{ __('Lead (Potencial)') }}</option>
                                        <option value="client">{{ __('Cliente Final') }}</option>
                                        <option value="architect">{{ __('Arquiteto / Especificador') }}</option>
                                        <option value="partner">{{ __('Parceiro / Construtora') }}</option>
                                    </select>
                                @endif
                            </div>

                            {{-- Segmento --}}
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Segmento') }}</label>
                                <select name="segment" class="form-select" required>
                                    <option value="residential">{{ __('Residencial') }}</option>
                                    <option value="commercial">{{ __('Comercial / Corporativo') }}</option>
                                    <option value="high_end">{{ __('Alto Padrão (High End)') }}</option>
                                </select>
                            </div>

                            {{-- Documento --}}
                            <div class="col-12">
                                <label class="form-label">{{ __('Documento (CPF/CNPJ)') }}</label>
                                <input type="text" name="document" class="form-control" placeholder="{{ __('Apenas números') }}">
                            </div>

                            <div class="col-12 mt-4 pt-3 border-top">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ $cancelRoute }}" class="btn btn-outline-secondary">{{ __('Cancelar') }}</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-check me-1"></i> {{ __('Salvar') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
