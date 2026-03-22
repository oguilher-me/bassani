@extends('layouts.app')

@section('title', 'Nova Oportunidade')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">CRM / Pipeline /</span> Nova Oportunidade</h4>
    
    <div class="card mb-4">
        <h5 class="card-header">Detalhes da Oportunidade</h5>
        <div class="card-body">
            <form action="{{ route('crm.pipeline.store') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Cliente -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Lead *</label>
                        <select name="entity_id" class="form-select" required>
                            <option value="">Selecione...</option>
                            @foreach($entities as $entity)
                                <option value="{{ $entity->id }}">{{ $entity->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Título -->
                     <div class="col-md-6 mb-3">
                        <label class="form-label">Título do Projeto *</label>
                        <input type="text" name="title" class="form-control" placeholder="Ex: Apto 302 - Ed. Solar" required>
                    </div>
                </div>

                <div class="row">
                    <!-- CPF/CNPJ -->
                    <div class="col-md-4 mb-3">
                         <label class="form-label">CPF/CNPJ</label>
                         <input type="text" name="cpf_cnpj" class="form-control" placeholder="Digite apenas números">
                    </div>

                    <!-- Valor -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Valor Estimado (R$)</label>
                        <input type="number" name="estimated_value" class="form-control" step="0.01" value="0">
                    </div>
                    
                    <!-- Probabilidade -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Probabilidade de Fechamento (%)</label>
                        <div class="input-group">
                            <input type="number" name="probability" class="form-control" min="0" max="100" value="10">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Endereço do Projeto</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Rua, Número, Bairro, Cidade..."></textarea>
                </div>

                <div class="row">
                    <!-- Arquiteto -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Arquiteto Parceiro (Opcional)</label>
                        <select name="architect_id" class="form-select">
                            <option value="">Nenhum / Não informado</option>
                            @foreach($architects as $architect)
                                <option value="{{ $architect->id }}">{{ $architect->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tamanho -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tamanho do Projeto</label>
                        <input type="text" name="project_size" class="form-control" placeholder="Ex: 150m²">
                    </div>
                </div>

                <div class="row">
                     <!-- Precisa de Desenvolvimento? -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label d-block">Precisa que desenvolvamos o projeto?</label>
                        <div class="form-check form-check-inline mt-2">
                            <input class="form-check-input" type="radio" name="needs_project_development" value="1" id="dev_yes">
                            <label class="form-check-label" for="dev_yes">Sim</label>
                        </div>
                        <div class="form-check form-check-inline mt-2">
                            <input class="form-check-input" type="radio" name="needs_project_development" value="0" id="dev_no" checked>
                            <label class="form-check-label" for="dev_no">Não</label>
                        </div>
                    </div>

                    <!-- Data Limite Projeto -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Data Limite para Conclusão do Projeto</label>
                        <input type="date" name="project_deadline" class="form-control">
                    </div>

                    <!-- Expectativa Fechamento -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Data de Expectativa de Fechamento</label>
                        <input type="date" name="expected_closing_date" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <!-- Vendedor -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vendedor</label>
                        <select name="seller_id" class="form-select select2">
                            <option value="">Selecione o vendedor...</option>
                            @foreach($sellers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Responsável -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Responsável pela Negociação</label>
                        <select name="owner_id" class="form-select select2">
                            <option value="">Selecione o responsável...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ auth()->id() == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('crm.pipeline.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Salvar Oportunidade</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
