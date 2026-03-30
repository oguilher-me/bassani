@extends('layouts/contentNavbarLayout')

@section('title', 'Configuração do Funil de Vendas')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('Etapas do Funil') }}</h4>
            <p class="text-muted mb-0">{{ __('CRM') }}</p>
        </div>
        <button class="btn btn-primary" onclick="openCreateModal()">
            <i class="bx bx-plus me-1"></i> {{ __('Nova Etapa') }}
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bx bx-git-branch text-danger me-2"></i>{{ __('Gerenciar Fluxo de Vendas') }}
            </h5>
            <small class="text-muted">{{ __('Arraste as linhas para reordenar as etapas do funil.') }}</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 py-3" width="50"></th>
                            <th class="border-0 py-3 px-4">{{ __('Etapa') }}</th>
                            <th class="border-0 py-3">{{ __('Cor') }}</th>
                            <th class="border-0 py-3">{{ __('Probabilidade') }}</th>
                            <th class="border-0 py-3">{{ __('Ações Obrigatórias') }}</th>
                            <th class="border-0 py-3">{{ __('Status') }}</th>
                            <th class="border-0 py-3 text-end">{{ __('Ações') }}</th>
                        </tr>
                    </thead>
                    <tbody id="stagesList">
                        @foreach($stages as $stage)
                        <tr class="stage-row" data-id="{{ $stage->id }}">
                            <td class="py-3"><i class="bx bx-grip-vertical text-muted cursor-grab"></i></td>
                            <td class="py-3 px-4">
                                <div class="fw-semibold">{{ $stage->name }}</div>
                                <small class="text-muted">{{ $stage->slug }}</small>
                            </td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <span class="rounded-circle me-2" style="width: 16px; height: 16px; background-color: {{ $stage->color }}; display: inline-block;"></span>
                                    {{ $stage->color }}
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 8px; border-radius: 4px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $stage->probability }}%; background-color: {{ $stage->color }};"></div>
                                    </div>
                                    <span class="fw-medium">{{ $stage->probability }}%</span>
                                </div>
                            </td>
                            <td class="py-3">
                                @if($stage->required_actions)
                                    @foreach($stage->required_actions as $action)
                                        <span class="badge bg-label-secondary rounded-pill me-1">
                                            @if($action == 'anexar_projeto') {{ __('Anexar Projeto') }}
                                            @elseif($action == 'gerar_orcamento') {{ __('Gerar Orçamento') }}
                                            @elseif($action == 'measure') {{ __('Medição Técnica') }}
                                            @else {{ $action }} @endif
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-3">
                                @if($stage->is_active)
                                    <span class="badge bg-label-success rounded-pill">{{ __('Ativo') }}</span>
                                @else
                                    <span class="badge bg-label-danger rounded-pill">{{ __('Inativo') }}</span>
                                @endif
                            </td>
                            <td class="py-3 text-end">
                                <button class="btn btn-sm btn-icon btn-outline-primary me-1" onclick='openEditModal(@json($stage))'>
                                    <i class="bx bx-edit-alt"></i>
                                </button>
                                @if($stage->opportunities_count == 0 && !in_array($stage->slug, ['won', 'lost', 'new']))
                                <form action="{{ route('crm.settings.stages.destroy', $stage->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Tem certeza?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-danger">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                                @else
                                    <button class="btn btn-sm btn-icon btn-outline-secondary" disabled>
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
</div>

{{-- Stage Modal --}}
<div class="modal fade" id="stageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="stageForm" method="POST" action="">
                @csrf
                <div id="methodField"></div>
                
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Nova Etapa') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">{{ __('Nome da Etapa') }}</label>
                            <input type="text" class="form-control" name="name" id="stageName" required placeholder="{{ __('Ex: Briefing Inicial') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Cor') }}</label>
                            <input type="color" class="form-control form-control-color w-100" name="color" id="stageColor" value="#DE0802">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">{{ __('Probabilidade de Fechamento (%)') }}</label>
                            <div class="d-flex align-items-center">
                                <input type="range" class="form-range me-3" min="0" max="100" step="5" id="stageProbabilityRange" oninput="updateProbInput(this.value)">
                                <input type="number" class="form-control w-25" name="probability" id="stageProbabilityInput" min="0" max="100" value="0" oninput="updateProbRange(this.value)">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label d-block mb-2">{{ __('Ações Obrigatórias') }}</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="required_actions[]" value="anexar_projeto" id="req_project">
                                        <label class="form-check-label" for="req_project">{{ __('Exigir Projeto (Anexo)') }}</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="required_actions[]" value="measure" id="req_measure">
                                        <label class="form-check-label" for="req_measure">{{ __('Exigir Medição Técnica') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="required_actions[]" value="gerar_orcamento" id="req_budget">
                                        <label class="form-check-label" for="req_budget">{{ __('Exigir Orçamento') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12" id="isActiveContainer">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="stageIsActive" checked>
                                <label class="form-check-label" for="stageIsActive">{{ __('Etapa Ativa') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Salvar') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.updateProbInput = function(val) {
        document.getElementById('stageProbabilityInput').value = val;
    };
    
    window.updateProbRange = function(val) {
        document.getElementById('stageProbabilityRange').value = val;
    };

    window.openCreateModal = function() {
        document.getElementById('stageForm').action = "{{ route('crm.settings.stages.store') }}";
        document.getElementById('methodField').innerHTML = "";
        document.getElementById('modalTitle').innerText = "{{ __('Nova Etapa') }}";
        document.getElementById('stageName').value = "";
        document.getElementById('stageColor').value = "#DE0802";
        
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
        document.getElementById('modalTitle').innerText = "{{ __('Editar Etapa') }}";
        
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

@section('vendor-script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('stagesList');
        
        if (el) {
            new Sortable(el, {
                animation: 150,
                handle: '.bx-grip-vertical',
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
                      .then(data => console.log('Ordem atualizada!'))
                      .catch(err => console.error('Erro:', err));
                }
            });
        }
    });
</script>
@endsection
