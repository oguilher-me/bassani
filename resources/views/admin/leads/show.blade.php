@extends('layouts.app')

@section('title', 'Detalhes do Lead')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-xl-4 col-lg-5 col-md-5">
            <!-- Lead Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                         <div class="avatar avatar-lg me-2">
                             <span class="avatar-initial rounded-circle bg-label-primary">{{ substr($lead->name, 0, 1) }}</span>
                         </div>
                         <div>
                             <h5 class="mb-0">{{ $lead->name }}</h5>
                             <small class="text-muted">{{ $lead->type }}</small>
                         </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Status:</span>
                        <span class="badge bg-label-{{ $lead->status == 'new' ? 'primary' : ($lead->status == 'converted' ? 'success' : 'secondary') }}">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('crm.leads.edit', $lead->id) }}" class="btn btn-primary w-100"><i class="bx bx-edit-alt me-1"></i> Editar Lead</a>
                        
                        @if($lead->status !== 'converted' && $lead->status !== 'discarded')
                            <form action="{{ route('crm.leads.convert', $lead->id) }}" method="POST" onsubmit="return confirm('Confirma a conversão? Isso criará um Cliente e Oportunidade.');">
                                @csrf
                                <button class="btn btn-success w-100" type="submit"><i class="bx bx-check me-1"></i> Converter em Cliente</button>
                            </form>
                            <form id="discard-form" action="{{ route('crm.leads.update', $lead->id) }}" method="POST" class="w-100">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $lead->name }}">
                                <input type="hidden" name="type" value="{{ $lead->type }}">
                                <input type="hidden" name="status" value="discarded">
                                <button type="button" class="btn btn-outline-danger w-100" onclick="confirmDiscard()"><i class="bx bx-x me-1"></i> Descartar</button>
                            </form>
                        @endif

                        <form id="delete-form-{{ $lead->id }}" action="{{ route('crm.leads.destroy', $lead->id) }}" method="POST" class="w-100">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-label-danger w-100" onclick="confirmDelete()"><i class="bx bx-trash me-1"></i> Excluir Permanentemente</button>
                        </form>

                        <a href="{{ route('crm.leads.index') }}" class="btn btn-outline-secondary w-100"><i class="bx bx-arrow-back me-1"></i> Voltar</a>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="card mb-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase">Contato</small>
                    <ul class="list-unstyled mt-2 mb-4">
                        @if($lead->phone) <li class="d-flex align-items-center mb-2"><i class="bx bx-phone me-2"></i> {{ $lead->phone }}</li> @endif
                        @if($lead->whatsapp) <li class="d-flex align-items-center mb-2"><i class="bx bxl-whatsapp me-2 text-success"></i> <a href="https://wa.me/55{{ preg_replace('/\D/', '', $lead->whatsapp) }}" target="_blank">{{ $lead->whatsapp }}</a></li> @endif
                        @if($lead->email) <li class="d-flex align-items-center mb-2"><i class="bx bx-envelope me-2"></i> {{ $lead->email }}</li> @endif
                        @if($lead->city) <li class="d-flex align-items-center mb-2"><i class="bx bx-map me-2"></i> {{ $lead->city }} / {{ $lead->uf }}</li> @endif
                    </ul>
                    
                    <small class="text-muted text-uppercase">Parceiro</small>
                    <ul class="list-unstyled mt-2">
                        <li class="d-flex align-items-center mb-2"><i class="bx bx-user-check me-2"></i> {{ $lead->partner->name ?? 'Sem parceiro' }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-xl-8 col-lg-7 col-md-7">
            
            <!-- Opportunities List -->
            @if($lead->opportunities->isNotEmpty())
            <div class="card mb-4">
                <h5 class="card-header">Oportunidades & Negociações</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Estágio</th>
                                <th>Valor</th>
                                <th>Probabilidade</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lead->opportunities as $opportunity)
                                <tr>
                                    <td><a href="{{ route('crm.opportunities.show', $opportunity->id) }}"><strong>{{ $opportunity->title }}</strong></a></td>
                                    <td>{{ ucfirst($opportunity->stage_id) }}</td>
                                    <td>R$ {{ number_format($opportunity->estimated_value, 2, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress w-100 me-2" style="height: 6px;">
                                                <div class="progress-bar bg-info" style="width: {{ $opportunity->probability }}%"></div>
                                            </div>
                                            <small>{{ $opportunity->probability }}%</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($opportunity->status == 'won') <span class="badge bg-label-success">Ganha</span>
                                        @elseif($opportunity->status == 'lost') <span class="badge bg-label-danger">Perdida</span>
                                        @else <span class="badge bg-label-primary">Aberta</span> @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('crm.opportunities.show', $opportunity->id) }}" class="btn btn-sm btn-icon"><i class="bx bx-show"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Interactions & Qualification (Existing Tabs) -->
            <div class="nav-align-top mb-4">
                <ul class="nav nav-tabs nav-fill" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-interactions">Interações</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-qualification">Qualificação</button>
                    </li>
                </ul>
                <div class="tab-content">
                    
                    <!-- Interactions -->
                    <div class="tab-pane fade show active" id="navs-interactions">
                         <form action="{{ route('crm.leads.interaction', $lead->id) }}" method="POST" class="mb-4">
                            @csrf
                            <input type="hidden" name="type" value="call"> <!-- Default -->
                            <div class="input-group">
                                <select name="medium" class="form-select" style="max-width: 120px;">
                                    <option value="call">Ligação</option>
                                    <option value="whatsapp">WhatsApp</option>
                                    <option value="email">Email</option>
                                    <option value="meeting">Reunião</option>
                                </select>
                                <input type="text" name="notes" class="form-control" placeholder="Registrar interação..." required>
                                <button class="btn btn-primary" type="submit">Salvar</button>
                            </div>
                        </form>
                        
                        <ul class="timeline timeline-dashed">
                             @foreach($lead->interactions->sortByDesc('created_at') as $interaction)
                                <li class="timeline-item timeline-item-transparent">
                                    <span class="timeline-point timeline-point-primary"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header mb-1">
                                            <h6 class="mb-0">{{ ucfirst($interaction->medium) }}</h6>
                                            <small class="text-muted">{{ $interaction->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                        <p class="mb-0">{{ $interaction->notes }}</p>
                                        <small class="text-muted">Por {{ $interaction->user->name }}</small>
                                    </div>
                                </li>
                            @endforeach
                             <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-success"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Lead Criado</h6>
                                        <small class="text-muted">{{ $lead->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Qualification -->
                    <div class="tab-pane fade" id="navs-qualification">
                         <div class="row">
                             <div class="col-md-6 mb-3">
                                 <label class="fw-bold">Tipo de Imóvel</label>
                                 <p>{{ ucfirst($lead->qualification->property_type ?? '-') }}</p>
                             </div>
                             <div class="col-md-6 mb-3">
                                 <label class="fw-bold">Investimento Estimado</label>
                                 <p>R$ {{ number_format($lead->qualification->estimated_investment ?? 0, 2, ',', '.') }}</p>
                             </div>
                             <div class="col-md-6 mb-3">
                                 <label class="fw-bold">Urgência</label>
                                 <p>{{ ucfirst($lead->qualification->urgency_level ?? '-') }}</p>
                             </div>
                             <div class="col-12">
                                 <label class="fw-bold">Ambientes de Interesse</label>
                                 @if(!empty($lead->qualification->environments))
                                     <div>
                                         @foreach($lead->qualification->environments as $env)
                                             <span class="badge bg-label-secondary">{{ $env }}</span>
                                         @endforeach
                                     </div>
                                 @else
                                    <p class="text-muted">Não informado</p>
                                 @endif
                             </div>
                         </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- SweetAlert script for deletion --}}
@section('page-script')
<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Você tem certeza?',
            text: "Este lead e todo o seu histórico serão excluídos permanentemente! Esta ação não pode ser desfeita.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-danger me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-{{ $lead->id }}').submit();
            }
        });
    }

    function confirmDiscard() {
        Swal.fire({
            title: 'Descartar Lead?',
            text: "Deseja realmente marcar este lead como descartado?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ff3e1d',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Sim, descartar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-danger me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('discard-form').submit();
            }
        });
    }
</script>
@endsection
