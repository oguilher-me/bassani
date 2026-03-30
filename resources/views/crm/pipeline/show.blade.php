@extends('layouts/contentNavbarLayout')

@section('title', $opportunity->title)

@section('vendor-style')
<style>
    .pipeline-wizard {
        display: flex;
        flex-wrap: nowrap;
        background-color: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 2rem;
        padding: 0;
        width: 100%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: none;
        overflow: hidden;
    }
    .pipeline-step {
        flex: 1;
        position: relative;
        padding: 16px 10px 16px 35px;
        background: #f1f3f5;
        color: #495057;
        font-size: 0.85rem;
        font-weight: 500;
        text-align: center;
        white-space: nowrap;
        clip-path: polygon(calc(100% - 20px) 0, 100% 50%, calc(100% - 20px) 100%, 0% 100%, 20px 50%, 0% 0%);
        margin-right: -20px;
        z-index: 1;
        min-width: 150px;
        transition: all 0.2s;
        border: none;
    }
    .pipeline-step:first-child {
        clip-path: polygon(calc(100% - 20px) 0, 100% 50%, calc(100% - 20px) 100%, 0% 100%, 0% 0%);
        padding-left: 20px;
    }
    .pipeline-step:last-child {
        clip-path: polygon(100% 0, 100% 50%, 100% 100%, 0% 100%, 20px 50%, 0% 0%);
        margin-right: 0;
    }
    .pipeline-step.active {
        background: #DE0802 !important;
        color: white !important;
        z-index: 3;
        font-weight: 700;
    }
    .pipeline-step.completed {
        background: #e8f5e9;
        color: #2e7d32;
    }
    .pipeline-step.lost {
        background: #1F2A44 !important;
        color: white !important;
        z-index: 3;
    }
    .pipeline-step .step-time {
        display: block;
        font-size: 0.7rem;
        font-weight: 400;
        opacity: 0.9;
        margin-top: 2px;
    }
    .pipeline-step:hover:not(.active) {
        background: #e9ecef;
        z-index: 2;
    }
    .file-dropzone {
        border: 2px dashed #d9dee3;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        background-color: #f8f9fa;
        cursor: pointer;
        transition: all 0.2s;
    }
    .file-dropzone:hover, .file-dropzone.dragover {
        border-color: #DE0802;
        background-color: #fff5f5;
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Top Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('crm.pipeline.index') }}" class="btn btn-icon btn-outline-secondary me-3">
                <i class="bx bx-arrow-back"></i>
            </a>
            <div>
                <h4 class="fw-bold mb-0">{{ $opportunity->title }}</h4>
                <div class="d-flex align-items-center mt-1">
                    @if($opportunity->status == 'lost')
                        <span class="badge bg-label-danger rounded-pill me-2">{{ __('PERDIDA') }}</span>
                    @elseif($opportunity->status == 'won')
                        <span class="badge bg-label-success rounded-pill me-2">{{ __('GANHA') }}</span>
                    @endif
                    <span class="text-muted small">{{ __('Funil de Vendas') }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            @if($opportunity->status !== 'lost' && $opportunity->status !== 'won')
                <a href="{{ route('crm.opportunities.edit', $opportunity->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit-alt me-1"></i> {{ __('Editar') }}
                </a>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#lossModal">
                    <i class="bx bx-x-circle me-1"></i> {{ __('Perdida') }}
                </button>
                <form action="{{ route('crm.opportunities.won', $opportunity->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Confirmar ganho?') }}');">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-check-circle me-1"></i> {{ __('Marcar como Ganha') }}
                    </button>
                </form>
            @elseif($opportunity->status === 'lost')
                <form action="{{ route('crm.api.opportunity.stage', $opportunity->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="stage_id" value="new">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-refresh me-1"></i> {{ __('Retomar Negociação') }}
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Pipeline Wizard --}}
    <div class="pipeline-wizard mb-4">
        @php 
            $currentStageKey = $opportunity->stage_id;
            $isPassed = true; 
        @endphp
        @foreach($dbStages as $stage)
            @if($stage->slug == $currentStageKey)
                @php $isPassed = false; @endphp
                <div class="pipeline-step active {{ $opportunity->status == 'lost' ? 'lost' : '' }}">
                    {{ $stage->name }} 
                    @if($opportunity->updated_at)
                        <span class="step-time">{{ $opportunity->updated_at->diffForHumans() }}</span>
                    @endif
                </div>
            @else
                <div class="pipeline-step {{ $isPassed ? 'completed' : '' }}">
                    {{ $stage->name }}
                </div>
            @endif
        @endforeach
    </div>

    <div class="row">
        {{-- Left Sidebar (Details) --}}
        <div class="col-xl-4 col-md-5 col-12 mb-4">
            <div class="accordion" id="accordionDetails">
                {{-- Negociação --}}
                <div class="card border-0 shadow-sm accordion-item mb-3">
                    <h2 class="accordion-header" id="headingDeal">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDeal">
                            <i class="bx bx-dollar text-danger me-2"></i> {{ __('Negociação') }}
                        </button>
                    </h2>
                    <div id="collapseDeal" class="accordion-collapse collapse show" data-bs-parent="#accordionDetails">
                        <div class="accordion-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-5 text-muted">{{ __('Valor Total') }}</dt>
                                <dd class="col-sm-7 fw-bold" style="color: #DE0802;">R$ {{ number_format($opportunity->estimated_value, 2, ',', '.') }}</dd>
                                <dt class="col-sm-5 text-muted">{{ __('Criada em') }}</dt>
                                <dd class="col-sm-7">{{ $opportunity->created_at->format('d/m/Y H:i') }}</dd>
                                <dt class="col-sm-5 text-muted">{{ __('Fechamento') }}</dt>
                                <dd class="col-sm-7">{{ $opportunity->expected_closing_date ? $opportunity->expected_closing_date->format('d/m/Y') : '-' }}</dd>
                                <dt class="col-sm-5 text-muted">{{ __('Probabilidade') }}</dt>
                                <dd class="col-sm-7">{{ $opportunity->probability }}%</dd>
                                <dt class="col-sm-5 text-muted">{{ __('Tamanho') }}</dt>
                                <dd class="col-sm-7">{{ $opportunity->project_size ?? '-' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Contatos --}}
                <div class="card border-0 shadow-sm accordion-item mb-3">
                    <h2 class="accordion-header" id="headingContact">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseContact">
                            <i class="bx bx-user text-primary me-2"></i> {{ __('Contatos') }}
                        </button>
                    </h2>
                    <div id="collapseContact" class="accordion-collapse collapse show" data-bs-parent="#accordionDetails">
                        <div class="accordion-body">
                            @if($opportunity->customer || $opportunity->entity)
                                @php $contact = $opportunity->customer ?? $opportunity->entity; @endphp
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-sm me-2">
                                        <span class="avatar-initial rounded-circle bg-label-primary">{{ substr($contact->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $contact->name }}</h6>
                                        <small class="text-muted">{{ __('Cliente') }}</small>
                                    </div>
                                </div>
                                <div class="d-flex flex-column gap-2 ms-1">
                                    <span class="text-sm"><i class="bx bx-phone me-2 text-muted"></i> {{ $contact->mobile_phone ?? $contact->phone ?? '-' }}</span>
                                    <span class="text-sm"><i class="bx bx-envelope me-2 text-muted"></i> {{ $contact->email ?? '-' }}</span>
                                </div>
                            @else
                                <span class="text-muted">{{ __('Sem contato vinculado') }}</span>
                            @endif
                            
                            @if($opportunity->architect)
                                <hr class="my-3">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-sm me-2">
                                        <span class="avatar-initial rounded-circle bg-label-warning">{{ substr($opportunity->architect->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $opportunity->architect->name }}</h6>
                                        <small class="text-muted">{{ __('Arquiteto') }}</small>
                                    </div>
                                </div>
                                <div class="d-flex flex-column gap-2 ms-1">
                                    <span class="text-sm"><i class="bx bx-phone me-2 text-muted"></i> {{ $opportunity->architect->phone ?? '-' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Local do Projeto --}}
                @if($opportunity->address)
                <div class="card border-0 shadow-sm accordion-item mb-3">
                    <h2 class="accordion-header" id="headingAddress">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAddress">
                            <i class="bx bx-map-pin text-success me-2"></i> {{ __('Local do Projeto') }}
                        </button>
                    </h2>
                    <div id="collapseAddress" class="accordion-collapse collapse" data-bs-parent="#accordionDetails">
                        <div class="accordion-body">
                            <i class="bx bx-map-pin me-2 text-muted"></i> {{ $opportunity->address }}
                        </div>
                    </div>
                </div>
                @endif
                
                {{-- Responsáveis --}}
                <div class="card border-0 shadow-sm accordion-item">
                    <h2 class="accordion-header" id="headingOwner">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOwner">
                            <i class="bx bx-user-check text-info me-2"></i> {{ __('Responsáveis') }}
                        </button>
                    </h2>
                    <div id="collapseOwner" class="accordion-collapse collapse" data-bs-parent="#accordionDetails">
                        <div class="accordion-body">
                            <div class="mb-3">
                                <small class="text-muted d-block mb-2">{{ __('Responsável pela Negociação') }}</small>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xs me-2">
                                        <span class="avatar-initial rounded-circle bg-label-primary">{{ substr($opportunity->owner->name ?? $opportunity->user->name ?? '?', 0, 1) }}</span>
                                    </div>
                                    <span>{{ $opportunity->owner->name ?? $opportunity->user->name ?? __('Não atribuído') }}</span>
                                </div>
                            </div>
                            <div>
                                <small class="text-muted d-block mb-2">{{ __('Vendedor') }}</small>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xs me-2">
                                        <span class="avatar-initial rounded-circle bg-label-info">{{ substr($opportunity->seller->name ?? '?', 0, 1) }}</span>
                                    </div>
                                    <span>{{ $opportunity->seller->name ?? __('Não definido') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Content (Feed) --}}
        <div class="col-xl-8 col-md-7 col-12">
            {{-- Quick Activity Widget --}}
            <div class="card border-0 shadow-sm mb-4" id="activityWidget">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-outline-danger active activity-type-btn" data-type="call" title="{{ __('Ligação') }}">
                                <i class="bx bx-phone me-1"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger activity-type-btn" data-type="whatsapp" title="WhatsApp">
                                <i class="bx bxl-whatsapp me-1"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger activity-type-btn" data-type="email" title="{{ __('E-mail') }}">
                                <i class="bx bx-envelope me-1"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger activity-type-btn" data-type="meeting" title="{{ __('Reunião') }}">
                                <i class="bx bx-calendar-event me-1"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger activity-type-btn" data-type="task" title="{{ __('Tarefa') }}">
                                <i class="bx bx-task me-1"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger activity-type-btn" data-type="visit" title="{{ __('Visita') }}">
                                <i class="bx bx-map me-1"></i>
                            </button>
                        </div>
                    </div>
                    
                    <form id="quickActivityForm" action="{{ route('crm.opportunities.activities.store', $opportunity->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" id="activity_type" value="call">
                        
                        <div class="mb-2">
                            <input type="text" class="form-control" name="subject" id="activity_subject" placeholder="{{ __('Assunto...') }}" required>
                        </div>
                        
                        <div class="mb-2" id="due_date_container" style="display: none;">
                            <label class="form-label small text-muted mb-1">{{ __('Data/Hora para conclusão') }}</label>
                            <input type="datetime-local" class="form-control" name="due_date" id="activity_due_date">
                        </div>

                        <div class="input-group">
                            <textarea class="form-control" name="description" placeholder="{{ __('Descreva os detalhes...') }}" rows="2" style="resize: none;"></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="form-check" id="complete_check_container">
                                <input class="form-check-input" type="checkbox" name="status" value="completed" id="mark_completed" checked>
                                <label class="form-check-label small" for="mark_completed">
                                    {{ __('Concluído agora') }}
                                </label>
                            </div>
                            <button class="btn btn-success btn-sm" type="submit" id="saveActivityBtn">
                                <i class="bx bx-check me-1"></i> {{ __('Registrar') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabs Navigation --}}
            <div class="nav-align-top mb-4">
                <ul class="nav nav-tabs nav-fill" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-timeline">
                            <i class="bx bx-list-ul me-1"></i> {{ __('Atividades') }}
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-audit">
                            <i class="bx bx-history me-1"></i> {{ __('Histórico') }}
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-files">
                            <i class="bx bx-file me-1"></i> {{ __('Arquivos') }}
                        </button>
                    </li>
                </ul>

                <div class="tab-content text-muted p-0 pt-4 card border-0 shadow-sm">
                    {{-- Timeline Tab --}}
                    <div class="tab-pane fade show active card-body" id="navs-timeline">
                        <div class="d-flex justify-content-between align-items-center mt-3 mb-3 px-3">
                            <h6 class="mb-0 fw-semibold">{{ __('Linha do Tempo de Interações') }}</h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterTimeline" data-bs-toggle="dropdown">
                                    <i class="bx bx-filter-alt"></i> {{ __('Filtrar') }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item filter-btn active" data-filter="all" href="javascript:void(0);">{{ __('Todos') }}</a></li>
                                    <li><a class="dropdown-item filter-btn" data-filter="call" href="javascript:void(0);">{{ __('Ligações') }}</a></li>
                                    <li><a class="dropdown-item filter-btn" data-filter="meeting" href="javascript:void(0);">{{ __('Reuniões') }}</a></li>
                                    <li><a class="dropdown-item filter-btn" data-filter="whatsapp" href="javascript:void(0);">WhatsApp</a></li>
                                    <li><a class="dropdown-item filter-btn" data-filter="task" href="javascript:void(0);">{{ __('Tarefas Pendentes') }}</a></li>
                                    <li><a class="dropdown-item filter-btn" data-filter="visit" href="javascript:void(0);">{{ __('Visitas') }}</a></li>
                                </ul>
                            </div>
                        </div>

                        <ul class="timeline timeline-dashed mt-3 px-3" id="crmActivitiesTimeline">
                            @foreach($opportunity->activities as $activity)
                                @php
                                    $iconClass = match($activity->type) {
                                        'call' => 'bx-phone',
                                        'whatsapp' => 'bxl-whatsapp',
                                        'email' => 'bx-envelope',
                                        'meeting' => 'bx-calendar-event',
                                        'task' => 'bx-task',
                                        'visit' => 'bx-map',
                                        default => 'bx-info-circle',
                                    };
                                    $colorClass = match($activity->type) {
                                        'call' => 'primary',
                                        'whatsapp' => 'success',
                                        'email' => 'info',
                                        'meeting' => 'warning',
                                        'task' => 'danger',
                                        'visit' => 'secondary',
                                        default => 'secondary',
                                    };
                                    $isOverdue = $activity->status === 'pending' && $activity->due_date && $activity->due_date->isPast();
                                @endphp
                                <li class="timeline-item timeline-item-transparent activity-item @if($activity->status === 'completed') opacity-75 @endif" data-type="{{ $activity->type }}" data-status="{{ $activity->status }}">
                                    <span class="timeline-point timeline-point-{{ $colorClass }}"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header mb-1">
                                            <h6 class="mb-0">{{ $activity->subject }}</h6>
                                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="mb-2">
                                            @if($activity->description)
                                                {!! nl2br(e($activity->description)) !!}
                                            @endif
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-xs me-2">
                                                    <span class="avatar-initial rounded-circle bg-label-secondary" style="font-size: 0.6rem;">{{ substr($activity->user->name ?? '?', 0, 1) }}</span>
                                                </div>
                                                <small class="text-muted">{{ $activity->user->name ?? 'Sistema' }}</small>
                                            </div>
                                            
                                            <div class="timeline-actions">
                                                @if($activity->type === 'whatsapp')
                                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $opportunity->customer->phone ?? $opportunity->entity->phone ?? '') }}" target="_blank" class="btn btn-xs btn-label-success me-1">
                                                        <i class="bx bxl-whatsapp me-1"></i> {{ __('Whats') }}
                                                    </a>
                                                @endif

                                                @if($activity->status === 'pending')
                                                    @if($isOverdue)
                                                        <span class="badge bg-label-danger me-1">{{ __('Atrasada') }}</span>
                                                    @endif
                                                    <button type="button" class="btn btn-xs btn-success complete-activity-btn" data-id="{{ $activity->id }}">
                                                        <i class="bx bx-check me-1"></i> {{ __('Concluir') }}
                                                    </button>
                                                @else
                                                    <span class="badge bg-label-success rounded-pill">{{ __('Concluída') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($activity->due_date)
                                            <div class="mt-2">
                                                <small class="{{ $isOverdue ? 'text-danger fw-bold' : 'text-muted' }}">
                                                    <i class="bx bx-time-five me-1"></i> {{ __('Prazo:') }} {{ $activity->due_date->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </li>
                            @endforeach

                            @if($opportunity->activities->isEmpty())
                                <div class="text-center py-5 no-activities">
                                    <i class="bx bx-list-ul mb-2 fs-1 text-muted"></i>
                                    <p class="text-muted">{{ __('Nenhuma atividade registrada ainda.') }}</p>
                                </div>
                            @endif
                        </ul>
                    </div>
                    
                    {{-- Audit Logs Tab --}}
                    <div class="tab-pane fade card-body" id="navs-audit">
                        <ul class="timeline timeline-dashed mt-3 px-3">
                            @foreach($opportunity->logs as $log)
                                @php
                                    $pointColor = match($log->action) {
                                        'created' => 'success',
                                        'stage_change' => 'warning',
                                        'status_change' => ($log->after['status'] ?? '') == 'won' ? 'success' : 'danger',
                                        'seller_assigned', 'owner_assigned' => 'info',
                                        'value_updated' => 'primary',
                                        default => 'secondary',
                                    };
                                    $icon = match($log->action) {
                                        'created' => 'bx-plus-circle',
                                        'stage_change' => 'bx-git-commit',
                                        'status_change' => 'bx-flag',
                                        'seller_assigned', 'owner_assigned' => 'bx-user',
                                        'value_updated' => 'bx-dollar',
                                        default => 'bx-info-circle',
                                    };
                                @endphp
                                <li class="timeline-item timeline-item-transparent">
                                    <span class="timeline-point timeline-point-{{ $pointColor }}"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header mb-1">
                                            <h6 class="mb-0">{{ $log->description }}</h6>
                                            <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-2">
                                            <small class="text-muted">
                                                <i class="bx {{ $icon }} me-1"></i> 
                                                {{ ucfirst(str_replace('_', ' ', $log->action)) }} • {{ $log->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </p>
                                        @if($log->before || $log->after)
                                            <div class="d-flex flex-wrap gap-2">
                                                @if($log->before && !empty($log->before))
                                                    <span class="badge bg-label-secondary rounded-pill">{{ __('Antes:') }} {{ is_array($log->before) ? json_encode($log->before) : $log->before }}</span>
                                                @endif
                                                @if($log->after && !empty($log->after))
                                                    <span class="badge bg-label-{{ $pointColor }} rounded-pill">{{ __('Novo:') }} {{ is_array($log->after) ? json_encode($log->after) : $log->after }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                            
                            @if($opportunity->logs->isEmpty())
                                <div class="text-center py-5">
                                    <i class="bx bx-history mb-2 fs-1 text-muted"></i>
                                    <p class="text-muted">{{ __('Nenhum registro de auditoria encontrado.') }}</p>
                                </div>
                            @endif
                        </ul>
                    </div>
                    
                    {{-- Files Tab --}}
                    <div class="tab-pane fade card-body" id="navs-files">
                        <div class="file-dropzone mb-4" id="attachmentDropzone">
                            <div class="mb-3">
                                <i class="bx bx-cloud-upload fs-1 text-muted"></i>
                            </div>
                            <h6>{{ __('Arraste arquivos aqui ou clique para fazer upload') }}</h6>
                            <p class="text-muted small mb-0">{{ __('PDF, Imagens, Excel, Word (Máx. 10MB)') }}</p>
                            <input type="file" id="fileInput" name="file" class="d-none" multiple>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="attachmentsTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 py-3 px-4">{{ __('Arquivo') }}</th>
                                        <th class="border-0 py-3">{{ __('Tipo') }}</th>
                                        <th class="border-0 py-3">{{ __('Tamanho') }}</th>
                                        <th class="border-0 py-3">{{ __('Enviado por') }}</th>
                                        <th class="border-0 py-3 text-end">{{ __('Ações') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($opportunity->attachments as $attachment)
                                        <tr data-id="{{ $attachment->id }}">
                                            <td class="py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $icon = match(strtolower($attachment->file_type)) {
                                                            'pdf' => 'bx-file-blank text-danger',
                                                            'jpg', 'jpeg', 'png', 'gif' => 'bx-image text-success',
                                                            'xls', 'xlsx', 'csv' => 'bx-spreadsheet text-success',
                                                            'doc', 'docx' => 'bx-file text-primary',
                                                            'ppt', 'pptx' => 'bx-slideshow text-warning',
                                                            default => 'bx-file text-secondary',
                                                        };
                                                    @endphp
                                                    <i class="bx {{ $icon }} fs-4 me-2"></i>
                                                    <span class="fw-medium">{{ $attachment->file_name }}</span>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-label-secondary rounded-pill text-uppercase">{{ $attachment->file_type }}</span></td>
                                            <td>{{ $attachment->formatted_size }}</td>
                                            <td>{{ $attachment->user->name ?? 'Sistema' }}</td>
                                            <td class="text-end">
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank">
                                                            <i class="bx bx-download me-1"></i> {{ __('Baixar') }}
                                                        </a>
                                                        <button type="button" class="dropdown-item text-danger delete-attachment-btn" data-id="{{ $attachment->id }}">
                                                            <i class="bx bx-trash me-1"></i> {{ __('Excluir') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if($opportunity->attachments->isEmpty())
                                        <tr class="no-attachments">
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                {{ __('Nenhum arquivo anexado ainda.') }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Loss Modal --}}
<div class="modal fade" id="lossModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('crm.opportunities.lost', $opportunity->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Marcar como Perdida') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Motivo da Perda') }}</label>
                        <select name="reason" class="form-select" required>
                            <option value="">{{ __('Selecione...') }}</option>
                            <option value="price">{{ __('Preço') }}</option>
                            <option value="competitor">{{ __('Concorrência') }}</option>
                            <option value="timeline">{{ __('Prazo') }}</option>
                            <option value="abandoned">{{ __('Desistência') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Observação') }}</label>
                        <textarea name="notes" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Confirmar Perda') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const activityForm = document.getElementById('quickActivityForm');
    const typeButtons = document.querySelectorAll('.activity-type-btn');
    const typeInput = document.getElementById('activity_type');
    const dueDateContainer = document.getElementById('due_date_container');
    const completeCheckContainer = document.getElementById('complete_check_container');
    const markCompleted = document.getElementById('mark_completed');
    const subjectInput = document.getElementById('activity_subject');

    typeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            typeButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const type = this.dataset.type;
            typeInput.value = type;

            if (type === 'task' || type === 'meeting' || type === 'visit') {
                dueDateContainer.style.display = 'block';
                markCompleted.checked = false;
                subjectInput.placeholder = type === 'meeting' ? '{{ __("Assunto da Reunião...") }}' : '{{ __("O que precisa ser feito?") }}';
            } else {
                dueDateContainer.style.display = 'none';
                markCompleted.checked = true;
                subjectInput.placeholder = '{{ __("Assunto...") }}';
            }
        });
    });

    if (activityForm) {
        activityForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('saveActivityBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => data[key] = value);
            
            data.status = this.querySelector('#mark_completed').checked ? 'completed' : 'pending';

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(async response => {
                const res = await response.json();
                if (response.ok && res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __("Sucesso!") }}',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false,
                        confirmButtonColor: '#DE0802'
                    }).then(() => location.reload());
                } else {
                    Swal.fire('{{ __("Erro") }}', res.message || '{{ __("Erro ao salvar") }}', 'error');
                    btn.disabled = false;
                    btn.innerText = '{{ __("Registrar") }}';
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('{{ __("Erro de Conexão") }}', '{{ __("Verifique o console ou logs.") }}', 'error');
                btn.disabled = false;
                btn.innerText = '{{ __("Registrar") }}';
            });
        });
    }

    document.addEventListener('click', function(e) {
        if (e.target.closest('.complete-activity-btn')) {
            const btn = e.target.closest('.complete-activity-btn');
            const id = btn.dataset.id;
            btn.disabled = true;

            fetch(`/admin/crm/activities/${id}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                const res = await response.json();
                if (response.ok && res.success) {
                    location.reload();
                } else {
                    Swal.fire('{{ __("Erro") }}', res.message || '{{ __("Não foi possível concluir") }}', 'error');
                    btn.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('{{ __("Erro") }}', '{{ __("Falha na comunicação com o servidor.") }}', 'error');
                btn.disabled = false;
            });
        }
    });

    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            document.querySelectorAll('.activity-item').forEach(item => {
                if (filter === 'all') {
                    item.style.display = 'block';
                } else if (filter === 'task') {
                    item.style.display = (item.dataset.type === 'task' && item.dataset.status === 'pending') ? 'block' : 'none';
                } else {
                    item.style.display = item.dataset.type === filter ? 'block' : 'none';
                }
            });
        });
    });

    const dropzone = document.getElementById('attachmentDropzone');
    const fileInput = document.getElementById('fileInput');

    if (dropzone) {
        dropzone.addEventListener('click', () => fileInput.click());
        dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
        dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
        dropzone.addEventListener('drop', (e) => { e.preventDefault(); dropzone.classList.remove('dragover'); handleFiles(e.dataTransfer.files); });
        fileInput.addEventListener('change', (e) => handleFiles(e.target.files));
    }

    function handleFiles(files) {
        for (let i = 0; i < files.length; i++) uploadFile(files[i]);
    }

    function uploadFile(file) {
        const formData = new FormData();
        formData.append('file', file);

        fetch(`{{ route('crm.opportunities.attachments.store', $opportunity->id) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async response => {
            const res = await response.json();
            if (response.ok && res.success) {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __("Sucesso!") }}',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false,
                    confirmButtonColor: '#DE0802'
                }).then(() => location.reload());
            } else {
                Swal.fire('{{ __("Erro") }}', res.message || '{{ __("Erro ao enviar arquivo") }}', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('{{ __("Erro") }}', '{{ __("Falha ao enviar arquivo.") }}', 'error');
        });
    }

    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-attachment-btn')) {
            const btn = e.target.closest('.delete-attachment-btn');
            const id = btn.dataset.id;

            Swal.fire({
                title: '{{ __("Tem certeza?") }}',
                text: "{{ __('Você não poderá reverter esta ação!') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DE0802',
                cancelButtonColor: '#1F2A44',
                confirmButtonText: '{{ __("Sim, excluir!") }}',
                cancelButtonText: '{{ __("Cancelar") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/crm/attachments/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(async response => {
                        const res = await response.json();
                        if (response.ok && res.success) {
                            btn.closest('tr').remove();
                            Swal.fire('{{ __("Excluído!") }}', res.message, 'success');
                        } else {
                            Swal.fire('{{ __("Erro") }}', res.message || '{{ __("Não foi possível excluir") }}', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('{{ __("Erro") }}', '{{ __("Falha ao excluir arquivo.") }}', 'error');
                    });
                }
            });
        }
    });
});
</script>
@endsection
