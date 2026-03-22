@extends('layouts.app')

@php
    $typeLabel = match($type ?? '') {
        'lead' => 'Lead',
        'architect' => 'Arquiteto',
        'partner' => 'Parceiros',
        default => 'Entidade'
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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">CRM / {{ $typeLabel }} /</span> Novo {{ $typeLabel }}</h4>
    
    <div class="col-xl-8 col-lg-10 col-md-12 mx-auto">
        <div class="card mb-4">
            <h5 class="card-header">Dados do {{ $typeLabel }}</h5>
            <div class="card-body">
                <form action="{{ route('crm.entities.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nome Completo / Razão Social</label>
                        <input type="text" name="name" class="form-control" placeholder="Ex: Arq. João Silva ou Residência Souza" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo</label>
                            @if(isset($type))
                                <input type="hidden" name="type" value="{{ $type }}">
                                <input type="text" class="form-control" value="{{ ucfirst($type) }}" disabled>
                            @else
                                <select name="type" class="form-select" required>
                                    <option value="lead">Lead (Potencial)</option>
                                    <option value="client">Cliente Final</option>
                                    <option value="architect">Arquiteto / Especificador</option>
                                    <option value="partner">Parceiro / Construtora</option>
                                </select>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Segmento</label>
                            <select name="segment" class="form-select" required>
                                <option value="residential">Residencial</option>
                                <option value="commercial">Comercial / Corporativo</option>
                                <option value="high_end">Alto Padrão (High End)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Documento (CPF/CNPJ)</label>
                        <input type="text" name="document" class="form-control" placeholder="Apenas números">
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ $cancelRoute }}" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
