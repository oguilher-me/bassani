@extends('layouts/contentNavbarLayout')

@section('title', 'Editar Oportunidade: ' . $opportunity->title)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">CRM / Pipeline /</span> Editar Oportunidade
        </h4>
        <a href="{{ route('crm.opportunities.show', $opportunity->id) }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Voltar
        </a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Informações da Oportunidade</h5>
                <form action="{{ route('crm.opportunities.update', $opportunity->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Título da Oportunidade *</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $opportunity->title) }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Etapa do Funil *</label>
                                <select name="stage_id" class="form-select" required>
                                    @foreach($dbStages as $stage)
                                        <option value="{{ $stage->slug }}" {{ old('stage_id', $opportunity->stage_id) == $stage->slug ? 'selected' : '' }}>
                                            {{ $stage->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cliente/Lead *</label>
                                <select name="entity_id" class="form-select select2" required>
                                    @foreach($entities as $entity)
                                        <option value="{{ $entity->id }}" {{ old('entity_id', $opportunity->entity_id) == $entity->id ? 'selected' : '' }}>
                                            {{ $entity->name }} ({{ ucfirst($entity->type) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Arquiteto</label>
                                <select name="architect_id" class="form-select select2">
                                    <option value="">Nenhum</option>
                                    @foreach($architects as $architect)
                                        <option value="{{ $architect->id }}" {{ old('architect_id', $opportunity->architect_id) == $architect->id ? 'selected' : '' }}>
                                            {{ $architect->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Valor Estimado *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" step="0.01" name="estimated_value" class="form-control" value="{{ old('estimated_value', $opportunity->estimated_value) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Probabilidade (%)</label>
                                <input type="number" name="probability" class="form-control" value="{{ old('probability', $opportunity->probability) }}" min="0" max="100">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Previsão de Fechamento</label>
                                <input type="date" name="expected_closing_date" class="form-control" value="{{ old('expected_closing_date', $opportunity->expected_closing_date ? $opportunity->expected_closing_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Endereço do Projeto</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address', $opportunity->address) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Prazo do Projeto</label>
                                <input type="date" name="project_deadline" class="form-control" value="{{ old('project_deadline', $opportunity->project_deadline ? $opportunity->project_deadline->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tamanho/Complexidade</label>
                                <input type="text" name="project_size" class="form-control" value="{{ old('project_size', $opportunity->project_size) }}" placeholder="Ex: Apartamento 120m2, Cozinha Planejada">
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-center mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="needs_project_development" id="needs_project" {{ old('needs_project_development', $opportunity->needs_project_development) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="needs_project">Necessita desenvolvimento de projeto?</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Vendedor</label>
                                <select name="seller_id" class="form-select select2">
                                    <option value="">Selecione o vendedor...</option>
                                    @foreach($sellers as $user)
                                        <option value="{{ $user->id }}" {{ old('seller_id', $opportunity->seller_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Responsável pela Negociação</label>
                                <select name="owner_id" class="form-select select2">
                                    <option value="">Selecione o responsável...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('owner_id', $opportunity->owner_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">Salvar Alterações</button>
                            <a href="{{ route('crm.opportunities.show', $opportunity->id) }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
            $('.select2').select2();
        }
    });
</script>
@endsection
