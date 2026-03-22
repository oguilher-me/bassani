@extends('layouts/contentNavbarLayout')

@section('title', 'Pipeline de Vendas')

@section('vendor-style')
<style>
    /* Mimic or ensure styles for Kanban from Sales module exist */
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
    }
    .kanban-item:active {
        cursor: grabbing;
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Pipeline de Vendas</h5>
        <div class="d-flex align-items-center">
            <div>
                 <!-- Toggle Buttons (Optional, enabling Kanban by default) -->
                <div style="margin-top: -10px;" class="btn-group me-2" role="group" aria-label="Visualização">
                    {{-- <button type="button" class="btn btn-outline-primary" id="listViewBtn"><i class="bx bx-list-ul"></i> Lista</button> --}}
                    <button type="button" class="btn btn-outline-primary active" id="kanbanViewBtn"><i class="bx bx-columns"></i> Kanban</button>
                </div>
                <a href="{{ route('crm.pipeline.create') }}" class="btn btn-primary mb-3"><i class="bx bx-plus"></i> Nova Oportunidade</a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
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
                         <div class="card mb-3" style="box-shadow: none; ">
                            <div class="card-header d-flex justify-content-between align-items-center py-2" >
                                <h6 class="card-title mb-0 text-uppercase" style="color: {{ $stageColor }}">{{ $stageName }}</h6>
                                <span class="badge rounded-pill" style="background-color: {{ $stageColor }}">{{ $count }}</span>
                            </div>
                            <div class="card-body px-2 pb-2">
                                <div class="kanban-items" id="stage-{{ $stageKey }}" data-stage="{{ $stageKey }}">
                                    @if($opportunities->get($stageKey))
                                        @foreach($opportunities->get($stageKey) as $opportunity)
                                            <div class="card kanban-item mb-2" data-id="{{ $opportunity->id }}">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-label-info">{{ $opportunity->probability }}%</span>
                                                        <div class="dropdown">
                                                            <i class="bx bx-dots-vertical-rounded cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="{{ route('crm.opportunities.show', $opportunity->id) }}">Ver Detalhes</a></li>
                                                                {{-- <li><a class="dropdown-item" href="#">Editar</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <h6 class="card-title mb-1">
                                                        <a href="{{ route('crm.opportunities.show', $opportunity->id) }}" class="text-body">{{ $opportunity->title }}</a>
                                                    </h6>
                                                    <div class="text-muted small mb-2">
                                                        {{ $opportunity->customer->name ?? $opportunity->entity->name ?? 'Sem Cliente' }}
                                                    </div>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                                        <span class="fw-bold text-primary">R$ {{ number_format($opportunity->estimated_value, 2, ',', '.') }}</span>
                                                        @if($opportunity->video_call_link)
                                                            <i class='bx bx-video text-muted'></i>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($opportunity->updated_at < now()->subDays(10))
                                                        <div class="mt-2 text-center">
                                                            <span class="badge bg-label-warning" style="font-size: 0.7rem;">Parado há {{ $opportunity->updated_at->diffInDays() }} dias</span>
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
</div>
@endsection
 
@section('vendor-script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('CRM Pipeline: Initializing SortableJS');
        const columns = document.querySelectorAll('.kanban-items');
        
        if (columns.length === 0) {
            console.warn('CRM Pipeline: No kanban columns found');
            return;
        }

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

                    console.log(`Moving opportunity ${opportunityId} to stage ${newStage}`);

                    // Correct API URL based on web.php: /admin/api/crm/opportunities/{opportunity}/stage
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
                        if (data.success) {
                            console.log('Moved successfully');
                        } else {
                            console.error('Move failed:', data);
                            alert('Erro ao mover: ' + (data.message || 'Erro desconhecido'));
                            location.reload(); // Revert by refreshing
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erro de conexão ao servidor');
                        location.reload();
                    });
                }
            });
        });
    });
</script>
@endsection
