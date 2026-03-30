@extends('layouts/contentNavbarLayout')

@section('title', 'Pipeline de Vendas')

@section('vendor-style')
<style>
    .kanban-container {
        display: flex;
        overflow-x: auto;
        padding-bottom: 20px;
        gap: 1rem;
    }
    .kanban-column {
        min-width: 320px;
        flex: 0 0 320px;
    }
    .kanban-items {
        min-height: 100px;
    }
    .kanban-item {
        cursor: grab;
        border: none;
        border-radius: 12px;
    }
    .kanban-item:active {
        cursor: grabbing;
    }
    .stage-header {
        border-radius: 12px 12px 0 0;
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('Pipeline de Vendas') }}</h4>
            <p class="text-muted mb-0">{{ __('Arraste as oportunidades entre as etapas') }}</p>
        </div>
        <div class="d-flex gap-2">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary active" id="kanbanViewBtn">
                    <i class="bx bx-kanban me-1"></i> {{ __('Kanban') }}
                </button>
            </div>
            <a href="{{ route('crm.pipeline.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> {{ __('Nova Oportunidade') }}
            </a>
        </div>
    </div>

    {{-- Kanban View --}}
    <div id="kanbanViewContent">
        <div class="kanban-container">
            @foreach($dbStages as $stage)
                @php 
                    $stageKey = $stage->slug;
                    $count = $opportunities->get($stageKey)?->count() ?? 0;
                    $stageName = $stage->name;
                    $stageColor = $stage->color ?? '#8592a3';
                @endphp
                <div class="kanban-column">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header stage-header py-3" style="background: {{ $stageColor }}15; border-bottom: 2px solid {{ $stageColor }};">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0 fw-semibold" style="color: {{ $stageColor }};">
                                    {{ $stageName }}
                                </h6>
                                <span class="badge rounded-pill" style="background-color: {{ $stageColor }}; color: white;">
                                    {{ $count }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body px-2 pb-2 pt-3">
                            <div class="kanban-items" id="stage-{{ $stageKey }}" data-stage="{{ $stageKey }}">
                                @if($opportunities->get($stageKey))
                                    @foreach($opportunities->get($stageKey) as $opportunity)
                                        <div class="card kanban-item shadow-sm mb-3" data-id="{{ $opportunity->id }}">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-label-info rounded-pill px-2 py-1">
                                                        {{ $opportunity->probability }}%
                                                    </span>
                                                    <div class="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded cursor-pointer text-muted" data-bs-toggle="dropdown"></i>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('crm.opportunities.show', $opportunity->id) }}">
                                                                    <i class="bx bx-show me-1"></i> {{ __('Ver Detalhes') }}
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('crm.opportunities.edit', $opportunity->id) }}">
                                                                    <i class="bx bx-edit me-1"></i> {{ __('Editar') }}
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <h6 class="card-title mb-1">
                                                    <a href="{{ route('crm.opportunities.show', $opportunity->id) }}" class="text-body fw-semibold text-decoration-none">
                                                        {{ $opportunity->title }}
                                                    </a>
                                                </h6>
                                                <div class="text-muted small mb-2">
                                                    <i class="bx bx-user me-1"></i>
                                                    {{ $opportunity->customer->name ?? $opportunity->entity->name ?? __('Sem Cliente') }}
                                                </div>
                                                
                                                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                                    <span class="fw-bold" style="color: #DE0802;">
                                                        R$ {{ number_format($opportunity->estimated_value, 2, ',', '.') }}
                                                    </span>
                                                    @if($opportunity->video_call_link)
                                                        <a href="{{ $opportunity->video_call_link }}" target="_blank" class="text-info">
                                                            <i class="bx bx-video"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                                
                                                @if($opportunity->updated_at < now()->subDays(10))
                                                    <div class="mt-2 text-center">
                                                        <span class="badge bg-label-warning rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                                            <i class="bx bx-clock me-1"></i>
                                                            {{ __('Parado há') }} {{ $opportunity->updated_at->diffInDays() }} {{ __('dias') }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('vendor-script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const columns = document.querySelectorAll('.kanban-items');
        
        if (columns.length === 0) return;

        columns.forEach(column => {
            new Sortable(column, {
                group: 'kanban',
                animation: 150,
                ghostClass: 'bg-label-primary',
                onEnd: function (evt) {
                    const itemEl = evt.item;
                    const newStage = evt.to.dataset.stage;
                    const opportunityId = itemEl.dataset.id;
                    
                    if (evt.from === evt.to && evt.oldIndex === evt.newIndex) return;

                    fetch(`/admin/api/crm/opportunities/${opportunityId}/stage`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ stage_id: newStage })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro',
                                text: data.message || 'Erro ao mover',
                                confirmButtonColor: '#DE0802'
                            });
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        location.reload();
                    });
                }
            });
        });
    });
</script>
@endsection
