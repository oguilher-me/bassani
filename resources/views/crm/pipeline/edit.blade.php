@extends('layouts/contentNavbarLayout')

@section('title', 'Editar Oportunidade: ' . $opportunity->title)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('Editar Oportunidade') }}</h4>
            <p class="text-muted mb-0">{{ $opportunity->title }}</p>
        </div>
        <a href="{{ route('crm.opportunities.show', $opportunity->id) }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bx bx-edit text-danger me-2"></i>{{ __('Informações da Oportunidade') }}
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('crm.opportunities.update', $opportunity->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    {{-- Título --}}
                    <div class="col-md-8">
                        <label class="form-label">{{ __('Título da Oportunidade') }} *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $opportunity->title) }}" required>
                    </div>

                    {{-- Etapa --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Etapa do Funil') }} *</label>
                        <select name="stage_id" class="form-select" required>
                            @foreach($dbStages as $stage)
                                <option value="{{ $stage->slug }}" {{ old('stage_id', $opportunity->stage_id) == $stage->slug ? 'selected' : '' }}>
                                    {{ $stage->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Cliente --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Cliente/Lead') }} *</label>
                        <select name="entity_id" class="form-select" required>
                            @foreach($entities as $entity)
                                <option value="{{ $entity->id }}" {{ old('entity_id', $opportunity->entity_id) == $entity->id ? 'selected' : '' }}>
                                    {{ $entity->name }} ({{ ucfirst($entity->type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Arquiteto --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Arquiteto') }}</label>
                        <select name="architect_id" class="form-select">
                            <option value="">{{ __('Nenhum') }}</option>
                            @foreach($architects as $architect)
                                <option value="{{ $architect->id }}" {{ old('architect_id', $opportunity->architect_id) == $architect->id ? 'selected' : '' }}>
                                    {{ $architect->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Valor --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Valor Estimado') }} *</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" step="0.01" name="estimated_value" class="form-control" value="{{ old('estimated_value', $opportunity->estimated_value) }}" required>
                        </div>
                    </div>

                    {{-- Probabilidade --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Probabilidade (%)') }}</label>
                        <div class="input-group">
                            <input type="number" name="probability" class="form-control" value="{{ old('probability', $opportunity->probability) }}" min="0" max="100">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>

                    {{-- Previsão --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Previsão de Fechamento') }}</label>
                        <input type="date" name="expected_closing_date" class="form-control" value="{{ old('expected_closing_date', $opportunity->expected_closing_date ? $opportunity->expected_closing_date->format('Y-m-d') : '') }}">
                    </div>

                    {{-- Endereço --}}
                    <div class="col-md-8">
                        <label class="form-label">{{ __('Endereço do Projeto') }}</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $opportunity->address) }}">
                    </div>

                    {{-- Prazo --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Prazo do Projeto') }}</label>
                        <input type="date" name="project_deadline" class="form-control" value="{{ old('project_deadline', $opportunity->project_deadline ? $opportunity->project_deadline->format('Y-m-d') : '') }}">
                    </div>

                    {{-- Tamanho --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Tamanho/Complexidade') }}</label>
                        <input type="text" name="project_size" class="form-control" value="{{ old('project_size', $opportunity->project_size) }}" placeholder="{{ __('Ex: Apartamento 120m²') }}">
                    </div>

                    {{-- Precisa de Projeto --}}
                    <div class="col-md-6">
                        <label class="form-label d-block">{{ __('Necessita desenvolvimento de projeto?') }}</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="needs_project_development" id="needs_project" {{ old('needs_project_development', $opportunity->needs_project_development) ? 'checked' : '' }}>
                            <label class="form-check-label" for="needs_project">{{ __('Sim, necessita desenvolvimento') }}</label>
                        </div>
                    </div>

                    {{-- Vendedor --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Vendedor') }}</label>
                        <select name="seller_id" class="form-select">
                            <option value="">{{ __('Selecione o vendedor...') }}</option>
                            @foreach($sellers as $user)
                                <option value="{{ $user->id }}" {{ old('seller_id', $opportunity->seller_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Responsável --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Responsável pela Negociação') }}</label>
                        <select name="owner_id" class="form-select">
                            <option value="">{{ __('Selecione o responsável...') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('owner_id', $opportunity->owner_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('crm.opportunities.show', $opportunity->id) }}" class="btn btn-outline-secondary">{{ __('Cancelar') }}</a>
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
@endsection
