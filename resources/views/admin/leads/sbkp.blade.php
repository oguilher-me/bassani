@extends('layouts/contentNavbarLayout')

@section('title', $lead->name . ' - ' . __('Detalhes do Lead'))

@section('content')
<div class="row mb-4">
    <div class="col-12">
        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <a href="{{ route('crm.leads.index') }}" class="btn btn-icon btn-outline-secondary me-3">
                    <i class="bx bx-arrow-back"></i>
                </a>
                <div>
                    <h4 class="fw-bold mb-1">{{ $lead->name }}</h4>
                    <p class="text-muted mb-0">CRM / Leads / {{ __('Detalhes') }}</p>
                </div>
            </div>
            @php
                $statusColors = [
                    'new' => 'info',
                    'contacted' => 'primary',
                    'qualified' => 'warning',
                    'converted' => 'success',
                    'lost' => 'danger',
                    'discarded' => 'secondary'
                ];
            @endphp
            <span class="badge bg-label-{{ $statusColors[$lead->status] ?? 'secondary' }} rounded-pill px-3 py-2 fs-6">
                {{ ucfirst($lead->status) }}
            </span>
        </div>

        <div class="row">
            {{-- Sidebar --}}
            <div class="col-xl-4 col-lg-5 col-md-5 mb-4">
                {{-- Lead Profile Card --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center py-4">
                        <div class="avatar rounded-circle d-inline-flex align-items-center justify-content-center bg-label-primary mb-3" style="width: 80px; height: 80px;">
                            <span class="display-4 text-white">{{ substr($lead->name, 0, 1) }}</span>
                        </div>
                        <h5 class="mb-1 fw-bold">{{ $lead->name }}</h5>
                        <span class="badge bg-label-secondary rounded-pill">{{ $lead->type }}</span>
                        
                        <div class="d-grid gap-2 mt-4">
                            <a href="{{ route('crm.leads.edit', $lead->id) }}" class="btn btn-primary">
                                <i class="bx bx-edit-alt me-1"></i> {{ __('Editar Lead') }}
                            </a>
                            
                            @if($lead->status !== 'converted' && $lead->status !== 'discarded')
                                <form action="{{ route('crm.leads.convert', $lead->id) }}" method="POST" onsubmit="return confirm('{{ __("Confirma a conversão? Isso criará um Cliente e Oportunidade.") }}');">
                                    @csrf
                                    <button class="btn btn-success w-100" type="submit">
                                        <i class="bx bx-check me-1"></i> {{ __('Converter em Cliente') }}
                                    </button>
                                </form>
                                <form id="discard-form" action="{{ route('crm.leads.update', $lead->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="name" value="{{ $lead->name }}">
                                    <input type="hidden" name="type" value="{{ $lead->type }}">
                                    <input type="hidden" name="status" value="discarded">
                                    <button type="button" class="btn btn-outline-danger w-100" onclick="confirmDiscard()">
                                        <i class="bx bx-x me-1"></i> {{ __('Descartar') }}
                                    </button>
                                </form>
                            @endif

                            <form id="delete-form-{{ $lead->id }}" action="{{ route('crm.leads.destroy', $lead->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-outline-danger w-100" onclick="confirmDelete()">
                                    <i class="bx bx-trash me-1"></i> {{ __('Excluir Permanentemente') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Contact Details --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-phone text-danger me-2"></i>{{ __('Informações de Contato') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @if($lead->phone)
                                <li class="d-flex align-items-center mb-3 pb-2 border-bottom">
                                    <i class="bx bx-phone me-3 text-muted fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block">{{ __('Telefone') }}</small>
                                        <span class="fw-medium">{{ $lead->phone }}</span>
                                    </div>
                                </li>
                            @endif
                            @if($lead->whatsapp)
                                <li class="d-flex align-items-center mb-3 pb-2 border-bottom">
                                    <i class="bx bxl-whatsapp me-3 text-success fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block">WhatsApp</small>
                                        <a href="https://wa.me/55{{ preg_replace('/\D/', '', $lead->whatsapp) }}" target="_blank" class="fw-medium text-decoration-none">{{ $lead->whatsapp }}</a>
                                    </div>
                                </li>
                            @endif
                            @if($lead->email)
                                <li class="d-flex align-items-center mb-3 pb-2 border-bottom">
                                    <i class="bx bx-envelope me-3 text-muted fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block">{{ __('Email') }}</small>
                                        <span class="fw-medium">{{ $lead->email }}</span>
                                    </div>
                                </li>
                            @endif
                            @if($lead->city)
                                <li class="d-flex align-items-center">
                                    <i class="bx bx-map me-3 text-muted fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block">{{ __('Localização') }}</small>
                                        <span class="fw-medium">{{ $lead->city }} / {{ $lead->uf }}</span>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="col-xl-8 col-lg-7 col-md-7">
                {{-- Opportunities --}}
                @if($lead->opportunities->isNotEmpty())
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bx bx-briefcase text-danger me-2"></i>{{ __('Oportunidades & Negociações') }}
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 py-3 px-4">{{ __('Título') }}</th>
                                        <th class="border-0 py-3">{{ __('Valor') }}</th>
                                        <th class="border-0 py-3">{{ __('Probabilidade') }}</th>
                                        <th class="border-0 py-3">{{ __('Status') }}</th>
                                        <th class="border-0 py-3 text-end">{{ __('Ações') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lead->opportunities as $opportunity)
                                        <tr>
                                            <td class="py-3 px-4">
                                                <a href="{{ route('crm.opportunities.show', $opportunity->id) }}" class="fw-semibold text-decoration-none">
                                                    {{ $opportunity->title }}
                                                </a>
                                            </td>
                                            <td class="py-3">
                                                <span class="fw-bold" style="color: #DE0802;">
                                                    R$ {{ number_format($opportunity->estimated_value, 2, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 6px; border-radius: 4px;">
                                                        <div class="progress-bar bg-info" style="width: {{ $opportunity->probability }}%"></div>
                                                    </div>
                                                    <small class="fw-medium">{{ $opportunity->probability }}%</small>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                @if($opportunity->status == 'won') 
                                                    <span class="badge bg-label-success rounded-pill">{{ __('Ganha') }}</span>
                                                @elseif($opportunity->status == 'lost') 
                                                    <span class="badge bg-label-danger rounded-pill">{{ __('Perdida') }}</span>
                                                @else 
                                                    <span class="badge bg-label-primary rounded-pill">{{ __('Aberta') }}</span>
                                                @endif
                                            </td>
                                            <td class="py-3 text-end">
                                                <a href="{{ route('crm.opportunities.show', $opportunity->id) }}" class="btn btn-sm btn-icon btn-outline-primary">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Tabs --}}
                <div class="card border-0 shadow-sm">
                    <div class="nav-align-top">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-interactions">
                                    <i class="bx bx-chat me-1"></i> {{ __('Interações') }}
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-qualification">
                                    <i class="bx bx-check-shield me-1"></i> {{ __('Qualificação') }}
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content p-0">
                            {{-- Interactions Tab --}}
                            <div class="tab-pane fade show active card-body" id="navs-interactions">
                                <form action="{{ route('crm.leads.interaction', $lead->id) }}" method="POST" class="mb-4">
                                    @csrf
                                    <input type="hidden" name="type" value="call">
                                    <div class="input-group">
                                        <select name="medium" class="form-select" style="max-width: 130px;">
                                            <option value="call">{{ __('Ligação') }}</option>
                                            <option value="whatsapp">WhatsApp</option>
                                            <option value="email">{{ __('Email') }}</option>
                                            <option value="meeting">{{ __('Reunião') }}</option>
                                        </select>
                                        <input type="text" name="notes" class="form-control" placeholder="{{ __('Registrar interação...') }}" required>
                                        <button class="btn btn-primary" type="submit">
                                            <i class="bx bx-send me-1"></i> {{ __('Salvar') }}
                                        </button>
                                    </div>
                                </form>
                                
                                <ul class="timeline timeline-dashed ms-2">
                                    @foreach($lead->interactions->sortByDesc('created_at') as $interaction)
                                        <li class="timeline-item timeline-item-transparent">
                                            <span class="timeline-point timeline-point-primary"></span>
                                            <div class="timeline-event">
                                                <div class="timeline-header mb-1">
                                                    <h6 class="mb-0 fw-semibold">{{ ucfirst($interaction->medium) }}</h6>
                                                    <small class="text-muted">{{ $interaction->created_at->format('d/m/Y H:i') }}</small>
                                                </div>
                                                <p class="mb-1">{{ $interaction->notes }}</p>
                                                <small class="text-muted">
                                                    <i class="bx bx-user me-1"></i>{{ $interaction->user->name }}
                                                </small>
                                            </div>
                                        </li>
                                    @endforeach
                                    <li class="timeline-item timeline-item-transparent">
                                        <span class="timeline-point timeline-point-success"></span>
                                        <div class="timeline-event">
                                            <div class="timeline-header mb-1">
                                                <h6 class="mb-0 fw-semibold">{{ __('Lead Criado') }}</h6>
                                                <small class="text-muted">{{ $lead->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            {{-- Qualification Tab --}}
                            <div class="tab-pane fade card-body" id="navs-qualification">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small">{{ __('Tipo de Imóvel') }}</label>
                                        <p class="fw-semibold mb-0">{{ ucfirst($lead->qualification->property_type ?? '-') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small">{{ __('Investimento Estimado') }}</label>
                                        <p class="fw-bold mb-0" style="color: #DE0802;">
                                            R$ {{ number_format($lead->qualification->estimated_investment ?? 0, 2, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small">{{ __('Urgência') }}</label>
                                        <p class="fw-semibold mb-0">{{ ucfirst($lead->qualification->urgency_level ?? '-') }}</p>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-muted small">{{ __('Ambientes de Interesse') }}</label>
                                        @if(!empty($lead->qualification->environments))
                                            <div class="d-flex flex-wrap gap-2 mt-1">
                                                @foreach($lead->qualification->environments as $env)
                                                    <span class="badge bg-label-secondary rounded-pill">{{ $env }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted mb-0">{{ __('Não informado') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('page-script')
<script>
    function confirmDelete() {
        Swal.fire({
            title: '{{ __("Você tem certeza?") }}',
            text: '{{ __("Este lead e todo o seu histórico serão excluídos permanentemente!") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DE0802',
            cancelButtonColor: '#1F2A44',
            confirmButtonText: '{{ __("Sim, excluir!") }}',
            cancelButtonText: '{{ __("Cancelar") }}',
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
            title: '{{ __("Descartar Lead?") }}',
            text: '{{ __("Deseja realmente marcar este lead como descartado?") }}',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#DE0802',
            cancelButtonColor: '#1F2A44',
            confirmButtonText: '{{ __("Sim, descartar") }}',
            cancelButtonText: '{{ __("Cancelar") }}',
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
