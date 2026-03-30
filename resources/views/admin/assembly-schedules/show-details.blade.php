@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes do Agendamento de Montagem'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Detalhes do Agendamento de Montagem') }}</h4>
        <p class="text-muted mb-0">{{ __('Agendamento #') }}{{ $assemblySchedule->id }}</p>
    </div>
    <a href="{{ route('assembly-schedules.all') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
    </a>
</div>

{{-- Errors --}}
@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-4">
    {{-- Left Column --}}
    <div class="col-lg-8">
        {{-- Schedule Details Card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-calendar-event text-danger me-2"></i>{{ __('Informações do Agendamento') }}
                </h6>
                <div class="d-flex gap-2">
                    @if(!Auth::user()->hasRole('Montador'))
                        <a href="javascript:void(0);" class="btn btn-outline-primary btn-sm">
                            <i class="bx bx-edit me-1"></i>{{ __('Editar') }}
                        </a>
                    @endif
                    @php
                        $loggedInAssembler = null;
                        if (Auth::user()->hasRole('Montador')) {
                            $loggedInAssembler = $assemblySchedule->assemblers->where('user_id', Auth::user()->id)->first();
                        }
                    @endphp
                    @if($loggedInAssembler && $loggedInAssembler->pivot->confirmation_status === 'pending')
                        <form action="{{ route('assembler.my-schedule.confirm') }}" method="POST">
                            @csrf
                            <input type="hidden" name="assembly_schedule_id" value="{{ $assemblySchedule->id }}">
                            <input type="hidden" name="action" value="confirm">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bx bx-check me-1"></i>{{ __('Confirmar Montagem') }}
                            </button>
                        </form>
                    @endif
                    @if($loggedInAssembler && $loggedInAssembler->pivot->confirmation_status === 'confirmed' && $assemblySchedule->status !== 'started')
                        <a href="{{ route('assembler.my-schedule.start.form', $assemblySchedule->id) }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-play me-1"></i>{{ __('Iniciar Montagem') }}
                        </a>
                    @endif
                    @if($loggedInAssembler && $loggedInAssembler->pivot->confirmation_status === 'started')
                        <a href="{{ route('assembler.my-schedule.finish.form', $assemblySchedule->id) }}" class="btn btn-success btn-sm">
                            <i class="bx bx-check-double me-1"></i>{{ __('Concluir Montagem') }}
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-calendar"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Data Agendada') }}</small>
                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($assemblySchedule->scheduled_date)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-time"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Início') }}</small>
                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($assemblySchedule->start_time)->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar rounded-circle bg-label-secondary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-time-five"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Término') }}</small>
                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($assemblySchedule->end_time)->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <span class="text-muted me-2">{{ __('Status:') }}</span>
                            <span class="badge {{ $assemblySchedule->status == 'completed' ? 'bg-success' : ($assemblySchedule->status == 'started' ? 'bg-primary' : ($assemblySchedule->status == 'cancelled' ? 'bg-danger' : 'bg-warning')) }} rounded-pill px-3 py-2">
                                {{ ucfirst($assemblySchedule->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                @if($assemblySchedule->notes)
                    <div class="bg-light rounded-3 p-3">
                        <small class="text-muted d-block mb-1">{{ __('Observações') }}</small>
                        <p class="mb-0">{{ $assemblySchedule->notes }}</p>
                    </div>
                @endif
                
                <hr class="my-4">
                
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-user-pin me-2 text-danger"></i>{{ __('Montadores') }}
                </h6>
                <div class="d-flex flex-wrap gap-2">
                    @forelse ($assemblySchedule->assemblers as $assembler)
                        <span class="badge bg-light text-dark px-3 py-2">
                            <i class="bx bx-user me-1"></i>{{ $assembler->name }}
                        </span>
                    @empty
                        <span class="text-muted">{{ __('Nenhum montador atribuído.') }}</span>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Evaluation Card --}}
        @php
            $hasCompleted = $assemblySchedule->assemblers->contains(function($a) {
                return in_array($a->pivot->confirmation_status, ['completed', 'completed_with_pendencies']);
            });
            $evaluation = \App\Models\AssemblyScheduleEvaluation::where('assembly_schedule_id', $assemblySchedule->id)->first();
            $evaluationUrl = $evaluation ? route('assembly-evaluation.show', $evaluation->token) : null;
        @endphp

        @if ($evaluation)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-star text-danger me-2"></i>{{ __('Avaliação do Cliente') }}
                    </h6>
                </div>
                <div class="card-body">
                    @if ($hasCompleted)
                        <div class="bg-light rounded-3 p-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-semibold d-block mb-1">{{ __('Link de Avaliação') }}</span>
                                    @if ($evaluationUrl)
                                        <small class="text-muted">{{ $evaluationUrl }}</small>
                                    @else
                                        <small class="text-muted">{{ __('O link será gerado automaticamente ao concluir a montagem.') }}</small>
                                    @endif
                                </div>
                                @if ($evaluationUrl)
                                    <button class="btn btn-outline-primary btn-sm" type="button" onclick="navigator.clipboard.writeText('{{ $evaluationUrl }}')">
                                        <i class="bx bx-copy me-1"></i>{{ __('Copiar Link') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($evaluation->submitted_at)
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="bg-light rounded-3 p-3 text-center">
                                    <span class="d-block mb-2 small text-muted">{{ __('NPS') }}</span>
                                    <span class="badge {{ $evaluation->nps_score >= 7 ? 'bg-success' : ($evaluation->nps_score >= 4 ? 'bg-warning' : 'bg-danger') }} rounded-pill px-4 py-2 fs-5">
                                        {{ $evaluation->nps_score }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                @if ($evaluation->comments)
                                    <div class="mb-2">
                                        <small class="text-muted d-block mb-1">{{ __('Comentários') }}</small>
                                        <p class="mb-0">{{ $evaluation->comments }}</p>
                                    </div>
                                @endif
                                @php $evalPhotos = $evaluation->photo_paths ? json_decode($evaluation->photo_paths, true) : []; @endphp
                                @if (!empty($evalPhotos))
                                    <div class="d-flex flex-wrap gap-2 mt-3">
                                        @foreach ($evalPhotos as $photo)
                                            <img src="{{ Storage::url($photo) }}" alt="Foto" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="text-muted small mt-3">
                            <i class="bx bx-time me-1"></i>{{ __('Enviado em:') }} {{ \Carbon\Carbon::parse($evaluation->submitted_at)->format('d/m/Y H:i') }}
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bx bx-time fs-1 text-muted opacity-50"></i>
                            <p class="text-muted mt-2 mb-0">{{ __('Aguardando avaliação do cliente') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Timeline Card --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-time-five text-danger me-2"></i>{{ __('Linha do Tempo da Montagem') }}
                </h6>
            </div>
            <div class="card-body">
                @forelse ($assemblySchedule->assemblers as $assembler)
                    <div class="mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <h6 class="fw-semibold mb-3">
                            <i class="bx bx-user me-1 text-danger"></i>{{ $assembler->name }}
                        </h6>
                        
                        @if($assembler->pivot->started_at)
                            <div class="d-flex align-items-start mb-3">
                                <div class="avatar rounded-circle bg-label-success d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                    <i class="bx bx-play"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ __('Início') }}</span>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($assembler->pivot->started_at)->format('d/m/Y H:i') }}</small>
                                    @if($assembler->pivot->start_latitude && $assembler->pivot->start_longitude)
                                        <a href="https://www.google.com/maps?q={{ $assembler->pivot->start_latitude }},{{ $assembler->pivot->start_longitude }}" target="_blank" class="ms-2 small">
                                            <i class="bx bx-map"></i> {{ __('Ver no Maps') }}
                                        </a>
                                    @endif
                                    @if($assembler->pivot->start_photo_path)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($assembler->pivot->start_photo_path) }}" alt="Foto de início" class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-muted small mb-3">
                                <i class="bx bx-minus-circle me-1"></i>{{ __('Sem registro de início') }}
                            </div>
                        @endif

                        @if($assembler->pivot->finished_at)
                            <div class="d-flex align-items-start">
                                <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                    <i class="bx bx-check"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ __('Conclusão') }}</span>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($assembler->pivot->finished_at)->format('d/m/Y H:i') }}</small>
                                    @php $finishPhotos = $assembler->pivot->finish_photo_paths ? json_decode($assembler->pivot->finish_photo_paths, true) : []; @endphp
                                    @if(!empty($finishPhotos))
                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                            @foreach($finishPhotos as $photo)
                                                <img src="{{ Storage::url($photo) }}" alt="Foto" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($assembler->pivot->finish_notes)
                                        <div class="bg-light rounded-3 p-2 mt-2 small">
                                            <strong>{{ __('Observação:') }}</strong> {{ $assembler->pivot->finish_notes }}
                                        </div>
                                    @endif
                                    @if($assembler->pivot->finish_pending_reason)
                                        <div class="bg-warning bg-opacity-10 rounded-3 p-2 mt-2 small text-warning">
                                            <strong>{{ __('Pendências:') }}</strong> {{ $assembler->pivot->finish_pending_reason }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-muted small">
                                <i class="bx bx-minus-circle me-1"></i>{{ __('Sem registro de conclusão') }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bx bx-user fs-1 text-muted opacity-50"></i>
                        <p class="text-muted mt-2 mb-0">{{ __('Nenhum montador para exibir a linha do tempo.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-lg-4">
        {{-- Customer Card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-user text-danger me-2"></i>{{ __('Detalhes do Cliente') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4 pb-4 border-bottom">
                    <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        {{ strtoupper(substr($assemblySchedule->sale->customer->full_name ?? $assemblySchedule->sale->customer->company_name ?? 'N', 0, 1)) }}
                    </div>
                    <h6 class="fw-bold mb-1">{{ $assemblySchedule->sale->customer->full_name ?? $assemblySchedule->sale->customer->company_name }}</h6>
                    @if ($assemblySchedule->sale->customer->customer_type === 'individual')
                        <small class="text-muted">{{ __('CPF:') }} {{ $assemblySchedule->sale->customer->cpf ? sprintf('%s.%s.%s-%s', substr($assemblySchedule->sale->customer->cpf, 0, 3), substr($assemblySchedule->sale->customer->cpf, 3, 3), substr($assemblySchedule->sale->customer->cpf, 6, 3), substr($assemblySchedule->sale->customer->cpf, 9, 2)) : '-' }}</small>
                    @elseif ($assemblySchedule->sale->customer->customer_type === 'company')
                        <small class="text-muted">{{ __('CNPJ:') }} {{ $assemblySchedule->sale->customer->cnpj ? sprintf('%s.%s.%s/%s-%s', substr($assemblySchedule->sale->customer->cnpj, 0, 2), substr($assemblySchedule->sale->customer->cnpj, 2, 3), substr($assemblySchedule->sale->customer->cnpj, 5, 3), substr($assemblySchedule->sale->customer->cnpj, 8, 4), substr($assemblySchedule->sale->customer->cnpj, 12, 2)) : '-' }}</small>
                    @endif
                </div>
                
                <h6 class="text-uppercase text-muted mb-3 small">{{ __('Contato para Entrega') }}</h6>
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                        <i class="bx bx-user text-primary"></i>
                    </div>
                    <span class="small">{{ $assemblySchedule->sale->contact_name ?? '-' }}</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                        <i class="bx bx-envelope text-primary"></i>
                    </div>
                    <span class="small">{{ $assemblySchedule->sale->contact_email ?? '-' }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                        <i class="bx bx-phone text-primary"></i>
                    </div>
                    <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $assemblySchedule->sale->contact_phone) }}?text=Olá%2C%20aqui%20é%20da%20Bassani%20Móveis" target="_blank" class="small">{{ $assemblySchedule->sale->contact_phone ?? '-' }}</a>
                </div>
            </div>
        </div>

        {{-- Sale Card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-cart text-danger me-2"></i>{{ __('Detalhes da Venda') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ __('Código') }}</span>
                    <span class="fw-semibold">{{ $assemblySchedule->sale->erp_code }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">{{ __('Data') }}</span>
                    <span class="fw-semibold">{{ $assemblySchedule->sale->issue_date->format('d/m/Y') }}</span>
                </div>
                
                <hr>
                
                <small class="text-muted d-block mb-2">{{ __('Produtos') }}</small>
                <ul class="list-unstyled mb-0">
                    @forelse ($assemblySchedule->sale->saleItems as $item)
                        <li class="d-flex justify-content-between py-1 border-bottom">
                            <span class="small">{{ $item->product->name }}</span>
                            <span class="badge bg-light text-dark">Qtd: {{ ceil($item->quantity) }}</span>
                        </li>
                    @empty
                        <li class="text-muted small">{{ __('Nenhum produto') }}</li>
                    @endforelse
                </ul>
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <span class="text-muted">{{ __('Status') }}</span>
                    <span class="badge {{ $assemblySchedule->sale->order_status == 'completed' ? 'bg-success' : 'bg-warning' }} rounded-pill">{{ $assemblySchedule->sale->order_status }}</span>
                </div>
            </div>
        </div>

        {{-- Addresses --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-map text-danger me-2"></i>{{ __('Endereço de Entrega') }}
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-0 small">
                    {{ $assemblySchedule->sale->customer->shippingAddress->address_line_1 ?? 'N/A' }}<br>
                    {{ $assemblySchedule->sale->customer->shippingAddress->city ?? '' }}, {{ $assemblySchedule->sale->customer->shippingAddress->state ?? '' }}<br>
                    CEP: {{ $assemblySchedule->sale->customer->shippingAddress->zip_code ?? '' }}
                </p>
            </div>
        </div>

        {{-- Expenses Summary --}}
        @php
            $expenseAssembler = null;
            if (Auth::user()->hasRole('Montador') && Auth::user()->assembler) {
                $expAssembler = $assemblySchedule->assemblers->where('user_id', Auth::user()->id)->first();
                if ($expAssembler) {
                    $expenseAssembler = $expAssembler;
                }
            }
            $allExpenses = $assemblySchedule->expenses()->with('assembler')->latest()->get();
        @endphp

        @if($allExpenses->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-receipt text-danger me-2"></i>{{ __('Despesas') }}
                </h6>
                <a href="{{ route('assembly-expenses.index', ['assembly_schedule_id' => $assemblySchedule->id]) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bx bx-list-ul"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="d-flex justify-content-around p-3 border-bottom">
                    <div class="text-center">
                        <span class="badge bg-warning rounded-pill px-3">{{ $allExpenses->where('status','pendente')->count() }}</span>
                        <small class="d-block text-muted mt-1">{{ __('Pendentes') }}</small>
                    </div>
                    <div class="text-center">
                        <span class="fw-bold text-success">R$ {{ number_format($allExpenses->where('status','aprovado')->sum('amount'), 2, ',', '.') }}</span>
                        <small class="d-block text-muted mt-1">{{ __('Aprovados') }}</small>
                    </div>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($allExpenses->take(5) as $exp)
                        <div class="list-group-item py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bx {{ $exp->category_icon }} me-1 text-primary"></i>
                                    <span class="small">{{ $exp->category }}</span>
                                </div>
                                <span class="badge {{ $exp->status_badge }}">{{ $exp->status_label }}</span>
                            </div>
                            <small class="text-muted">{{ $exp->assembler->name ?? '—' }} · {{ $exp->date->format('d/m/Y') }} · R$ {{ number_format($exp->amount, 2, ',', '.') }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection