@extends('layouts.app')

@section('title', 'Editar Lead: ' . $lead->name)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">CRM / Leads /</span> Editar Lead
        </h4>
        <a href="{{ route('crm.leads.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Voltar
        </a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Informações do Lead</h5>
                <form action="{{ route('crm.leads.update', $lead->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nome Completo *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $lead->name) }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tipo</label>
                                <select name="type" class="form-select">
                                    <option value="PF" {{ old('type', $lead->type) == 'PF' ? 'selected' : '' }}>Pessoa Física</option>
                                    <option value="PJ" {{ old('type', $lead->type) == 'PJ' ? 'selected' : '' }}>Pessoa Jurídica</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select" required>
                                    <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>Novo</option>
                                    <option value="contacted" {{ old('status', $lead->status) == 'contacted' ? 'selected' : '' }}>Contatado</option>
                                    <option value="qualified" {{ old('status', $lead->status) == 'qualified' ? 'selected' : '' }}>Qualificado</option>
                                    <option value="converted" {{ old('status', $lead->status) == 'converted' ? 'selected' : '' }}>Convertido</option>
                                    <option value="lost" {{ old('status', $lead->status) == 'lost' ? 'selected' : '' }}>Perdido</option>
                                    <option value="discarded" {{ old('status', $lead->status) == 'discarded' ? 'selected' : '' }}>Descartado</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $lead->email) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Telefone</label>
                                <input type="text" name="phone" class="form-control phone-mask" value="{{ old('phone', $lead->phone) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">WhatsApp</label>
                                <input type="text" name="whatsapp" class="form-control phone-mask" value="{{ old('whatsapp', $lead->whatsapp) }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Cidade</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city', $lead->city) }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">UF</label>
                                <input type="text" name="uf" class="form-control" maxlength="2" value="{{ old('uf', $lead->uf) }}">
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Origem</label>
                                <select name="source" class="form-select">
                                    <option value="store" {{ old('source', $lead->source) == 'store' ? 'selected' : '' }}>Loja Física</option>
                                    <option value="instagram" {{ old('source', $lead->source) == 'instagram' ? 'selected' : '' }}>Instagram</option>
                                    <option value="site" {{ old('source', $lead->source) == 'site' ? 'selected' : '' }}>Site</option>
                                    <option value="referral" {{ old('source', $lead->source) == 'referral' ? 'selected' : '' }}>Indicação</option>
                                    <option value="architect" {{ old('source', $lead->source) == 'architect' ? 'selected' : '' }}>Arquiteto</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-4">Qualificação Básica</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Investimento Estimado</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" step="0.01" name="qualification[estimated_investment]" class="form-control" value="{{ old('qualification.estimated_investment', $lead->qualification->estimated_investment ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipo de Imóvel</label>
                                <select name="qualification[property_type]" class="form-select">
                                    <option value="">Selecione...</option>
                                    <option value="residential" {{ (old('qualification.property_type', $lead->qualification->property_type ?? '') == 'residential') ? 'selected' : '' }}>Residencial</option>
                                    <option value="commercial" {{ (old('qualification.property_type', $lead->qualification->property_type ?? '') == 'commercial') ? 'selected' : '' }}>Comercial</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nível de Urgência</label>
                                <select name="qualification[urgency_level]" class="form-select">
                                    <option value="">Selecione...</option>
                                    <option value="low" {{ (old('qualification.urgency_level', $lead->qualification->urgency_level ?? '') == 'low') ? 'selected' : '' }}>Baixa</option>
                                    <option value="medium" {{ (old('qualification.urgency_level', $lead->qualification->urgency_level ?? '') == 'medium') ? 'selected' : '' }}>Média</option>
                                    <option value="high" {{ (old('qualification.urgency_level', $lead->qualification->urgency_level ?? '') == 'high') ? 'selected' : '' }}>Alta</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">Salvar Alterações</button>
                            <a href="{{ route('crm.leads.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
