@extends('layouts.app')

@section('title', 'Configuração do Funil de Vendas')

@section('vendor-style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.css" />
<style>
    .stage-row {
        transition: background-color 0.2s;
    }
    .stage-row:hover {
        background-color: #f8f9fa;
    }
    .bx-grid-vertical {
        cursor: grab;
        font-size: 1.2rem;
    }
    .bx-grid-vertical:active {
        cursor: grabbing;
    }
    .color-dot {
        height: 15px;
        width: 15px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">CRM /</span> Etapas do Funil</h4>
        <button class="btn btn-primary" onclick="openCreateModal()">
            <i class="bx bx-plus me-1"></i> Nova Etapa
        </button>
    </div>

    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Gerenciar Fluxo de Vendas</h5>
            <small class="text-muted">Arraste as linhas para reordenar as etapas do funil.</small>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50"></th>
                        <th>Etapa</th>
                        <th>Cor</th>
                        <th>Probabilidade</th>
                        <th>Ações Obrigatórias</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="stagesList">
                    @foreach($stages as $stage)
                    <tr class="stage-row" data-id="{{ $stage->id }}">
                        <td><i class="bx bx-grid-vertical text-muted"></i></td>
                        <td>
                            <strong class="text-body">{{ $stage->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $stage->slug }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="color-dot" style="background-color: {{ $stage->color }}"></span>
                                {{ $stage->color }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress w-100 me-2" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $stage->probability }}%; background-color: {{ $stage->color }}" aria-valuenow="{{ $stage->probability }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span>{{ $stage->probability }}%</span>
                            </div>
                        </td>
                        <td>
                            @if($stage->required_actions)
                                @foreach($stage->required_actions as $action)
                                    <span class="badge bg-label-secondary mb-1">
                                        @if($action == 'anexar_projeto') Anexar Projeto 
                                        @elseif($action == 'gerar_orcamento') Gerar Orçamento 
                                        @elseif($action == 'measure') Medição Técnica 
                                        @else {{ $action }} @endif
                                    </span>
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                         <td>
                            @if($stage->is_active)
                                <span class="badge bg-label-success">Ativo</span>
                            @else
                                <span class="badge bg-label-danger">Inativo</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-icon btn-label-primary" onclick='openEditModal(@json($stage))'>
                                <i class="bx bx-edit-alt"></i>
                            </button>
                            @if($stage->opportunities_count == 0 && !in_array($stage->slug, ['won', 'lost', 'new']))
                            <form action="{{ route('crm.settings.stages.destroy', $stage->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta etapa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon btn-label-danger">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                            @else
                                <button class="btn btn-sm btn-icon btn-label-secondary" disabled title="Não é possível excluir (possui oportunidades ou é do sistema)">
                                    <i class="bx bx-trash"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
<script>
    // Define functions immediately to be available for onclick handlers
    window.updateProbInput = function(val) {
        document.getElementById('stageProbabilityInput').value = val;
    };
    
    window.updateProbRange = function(val) {
        document.getElementById('stageProbabilityRange').value = val;
    };

    window.openCreateModal = function() {
        document.getElementById('stageForm').action = "{{ route('crm.settings.stages.store') }}";
        document.getElementById('methodField').innerHTML = "";
        document.getElementById('modalTitle').innerText = "Nova Etapa";
        document.getElementById('stageName').value = "";
        document.getElementById('stageColor').value = "#696cff";
        
        updateProbInput(0);
        updateProbRange(0);

        document.querySelectorAll('input[name="required_actions[]"]').forEach(el => el.checked = false);
        document.getElementById('stageIsActive').checked = true;
        document.getElementById('isActiveContainer').style.display = 'none';

        var modal = new bootstrap.Modal(document.getElementById('stageModal'));
        modal.show();
    };

    window.openEditModal = function(stage) {
        let url = "{{ route('crm.settings.stages.update', ':id') }}";
        url = url.replace(':id', stage.id);
        
        document.getElementById('stageForm').action = url;
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('modalTitle').innerText = "Editar Etapa";
        
        document.getElementById('stageName').value = stage.name;
        document.getElementById('stageColor').value = stage.color;
        
        updateProbInput(stage.probability);
        updateProbRange(stage.probability);

        document.querySelectorAll('input[name="required_actions[]"]').forEach(el => el.checked = false);
        if (stage.required_actions) {
            stage.required_actions.forEach(act => {
                let cb = document.querySelector(`input[value="${act}"]`);
                if(cb) cb.checked = true;
            });
        }

        document.getElementById('stageIsActive').checked = stage.is_active;
        document.getElementById('isActiveContainer').style.display = 'block';

        var modal = new bootstrap.Modal(document.getElementById('stageModal'));
        modal.show();
    };
</script>

<div class="modal fade" id="stageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="stageForm" method="POST" action="">
                @csrf
                <div id="methodField"></div>
                
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Nova Etapa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nome da Etapa</label>
                            <input type="text" class="form-control" name="name" id="stageName" required placeholder="Ex: Briefing Inicial">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Cor</label>
                            <input type="color" class="form-control form-control-color w-100" name="color" id="stageColor" value="#696cff" title="Escolha a cor">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Probabilidade de Fechamento (%)</label>
                        <div class="d-flex align-items-center">
                            <input type="range" class="form-range me-3" min="0" max="100" step="5" id="stageProbabilityRange" oninput="updateProbInput(this.value)">
                            <input type="number" class="form-control w-25" name="probability" id="stageProbabilityInput" min="0" max="100" value="0" oninput="updateProbRange(this.value)">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block mb-2">Ações Obrigatórias</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="required_actions[]" value="anexar_projeto" id="req_project">
                                    <label class="form-check-label" for="req_project">Exigir Projeto (Anexo)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="required_actions[]" value="measure" id="req_measure">
                                    <label class="form-check-label" for="req_measure">Exigir Medição Técnica</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="required_actions[]" value="gerar_orcamento" id="req_budget">
                                    <label class="form-check-label" for="req_budget">Exigir Orçamento</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="isActiveContainer">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="stageIsActive" checked>
                            <label class="form-check-label" for="stageIsActive">Etapa Ativa</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>



@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('stagesList');
        
        if (el) {
            const sortable = new Sortable(el, {
                animation: 150,
                handle: '.bx-grid-vertical',
                ghostClass: 'table-active',
                dragClass: 'table-primary',
                onEnd: function(evt) {
                    let order = [];
                    document.querySelectorAll('#stagesList tr').forEach((row) => {
                        order.push(row.getAttribute('data-id'));
                    });

                    fetch("{{ route('crm.settings.stages.reorder') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ order: order })
                    }).then(res => res.json())
                      .then(data => {
                          console.log('Ordem atualizada com sucesso!');
                      })
                      .catch(err => console.error('Erro ao atualizar ordem:', err));
                }
            });
        }
    });
</script>
@endsection
