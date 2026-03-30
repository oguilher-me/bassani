@extends('layouts/contentNavbarLayout')

@section('title', 'Editar Lead: ' . $lead->name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">{{ __('Editar Lead') }}</h4>
                <p class="text-muted mb-0">CRM / Leads</p>
            </div>
            <a href="{{ route('crm.leads.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
            </a>
        </div>

        <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bx bx-edit text-danger me-2"></i>{{ __('Informações do Lead') }}
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('crm.leads.update', $lead->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    {{-- Nome --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Nome Completo') }} *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $lead->name) }}" required>
                    </div>

                    {{-- Tipo --}}
                    <div class="col-md-3">
                        <label class="form-label">{{ __('Tipo') }}</label>
                        <select name="type" class="form-select">
                            <option value="PF" {{ old('type', $lead->type) == 'PF' ? 'selected' : '' }}>{{ __('Pessoa Física') }}</option>
                            <option value="PJ" {{ old('type', $lead->type) == 'PJ' ? 'selected' : '' }}>{{ __('Pessoa Jurídica') }}</option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-3">
                        <label class="form-label">{{ __('Status') }} *</label>
                        <select name="status" class="form-select" required>
                            <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>{{ __('Novo') }}</option>
                            <option value="contacted" {{ old('status', $lead->status) == 'contacted' ? 'selected' : '' }}>{{ __('Contatado') }}</option>
                            <option value="qualified" {{ old('status', $lead->status) == 'qualified' ? 'selected' : '' }}>{{ __('Qualificado') }}</option>
                            <option value="converted" {{ old('status', $lead->status) == 'converted' ? 'selected' : '' }}>{{ __('Convertido') }}</option>
                            <option value="lost" {{ old('status', $lead->status) == 'lost' ? 'selected' : '' }}>{{ __('Perdido') }}</option>
                            <option value="discarded" {{ old('status', $lead->status) == 'discarded' ? 'selected' : '' }}>{{ __('Descartado') }}</option>
                        </select>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Email') }}</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $lead->email) }}">
                    </div>

                    {{-- Telefone --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Telefone') }}</label>
                        <input type="text" name="phone" class="form-control phone-mask" value="{{ old('phone', $lead->phone) }}">
                    </div>

                    {{-- WhatsApp --}}
                    <div class="col-md-4">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control phone-mask" value="{{ old('whatsapp', $lead->whatsapp) }}">
                    </div>

                    {{-- Cidade --}}
                    <div class="col-md-5">
                        <label class="form-label">{{ __('Cidade') }}</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', $lead->city) }}">
                    </div>

                    {{-- UF --}}
                    <div class="col-md-2">
                        <label class="form-label">{{ __('UF') }}</label>
                        <input type="text" name="uf" class="form-control" maxlength="2" value="{{ old('uf', $lead->uf) }}">
                    </div>

                    {{-- Origem --}}
                    <div class="col-md-5">
                        <label class="form-label">{{ __('Origem') }}</label>
                        <select name="source" class="form-select">
                            <option value="store" {{ old('source', $lead->source) == 'store' ? 'selected' : '' }}>{{ __('Loja Física') }}</option>
                            <option value="instagram" {{ old('source', $lead->source) == 'instagram' ? 'selected' : '' }}>Instagram</option>
                            <option value="site" {{ old('source', $lead->source) == 'site' ? 'selected' : '' }}>{{ __('Site') }}</option>
                            <option value="referral" {{ old('source', $lead->source) == 'referral' ? 'selected' : '' }}>{{ __('Indicação') }}</option>
                            <option value="architect" {{ old('source', $lead->source) == 'architect' ? 'selected' : '' }}>{{ __('Arquiteto') }}</option>
                        </select>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top">
                        <h5 class="fw-semibold mb-3">
                            <i class="bx bx-check-shield text-danger me-2"></i>{{ __('Qualificação Básica') }}
                        </h5>
                    </div>

                    {{-- Investimento --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Investimento Estimado') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" step="0.01" name="qualification[estimated_investment]" class="form-control" value="{{ old('qualification.estimated_investment', $lead->qualification->estimated_investment ?? '') }}">
                        </div>
                    </div>

                    {{-- Tipo Imóvel --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Tipo de Imóvel') }}</label>
                        <select name="qualification[property_type]" class="form-select">
                            <option value="">{{ __('Selecione...') }}</option>
                            <option value="residential" {{ (old('qualification.property_type', $lead->qualification->property_type ?? '') == 'residential') ? 'selected' : '' }}>{{ __('Residencial') }}</option>
                            <option value="commercial" {{ (old('qualification.property_type', $lead->qualification->property_type ?? '') == 'commercial') ? 'selected' : '' }}>{{ __('Comercial') }}</option>
                        </select>
                    </div>

                    {{-- Urgência --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Nível de Urgência') }}</label>
                        <select name="qualification[urgency_level]" class="form-select">
                            <option value="">{{ __('Selecione...') }}</option>
                            <option value="low" {{ (old('qualification.urgency_level', $lead->qualification->urgency_level ?? '') == 'low') ? 'selected' : '' }}>{{ __('Baixa') }}</option>
                            <option value="medium" {{ (old('qualification.urgency_level', $lead->qualification->urgency_level ?? '') == 'medium') ? 'selected' : '' }}>{{ __('Média') }}</option>
                            <option value="high" {{ (old('qualification.urgency_level', $lead->qualification->urgency_level ?? '') == 'high') ? 'selected' : '' }}>{{ __('Alta') }}</option>
                        </select>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('crm.leads.index') }}" class="btn btn-outline-secondary">{{ __('Cancelar') }}</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-check me-1"></i> {{ __('Salvar Alterações') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
@endsection
