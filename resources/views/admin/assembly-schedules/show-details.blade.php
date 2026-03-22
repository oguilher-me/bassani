@extends('layouts/contentNavbarLayout')


@section('title', __('Detalhes do Agendamento de Montagem'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-0">
        <div class="card mb-4"> 
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Detalhes do Agendamento de Montagem') }}</h5>
                <a href="{{ route('assembly-schedules.all') }}" class="btn btn-primary">{{ __('Voltar') }}</a>
            </div>
        </div>
    </div>
</div>
@if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
<div class="row mb-6 gy-6">
    <!-- Left Column -->
    <div class="col-xl-8 col-lg-8 col-md-8 order-0 order-md-0">
        <!-- Assembly Schedule details -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Agendamento #') }}{{ $assemblySchedule->id }}</h5>
                <div class="d-flex gap-2">
                    @if(!Auth::user()->hasRole('Montador'))
                        <a href="javascript:void(0);" class="btn btn-label-primary btn-sm">{{ __('Editar') }}</a>
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
                            <button type="submit" class="btn btn-success btn-sm">{{ __('Confirmar Montagem') }}</button>
                        </form>
                    @endif
                    @if($loggedInAssembler && $loggedInAssembler->pivot->confirmation_status === 'confirmed' && $assemblySchedule->status !== 'started')
                        <a href="{{ route('assembler.my-schedule.start.form', $assemblySchedule->id) }}" class="btn btn-primary btn-sm">{{ __('Iniciar Montagem') }}</a>
                    @endif
                    @if($loggedInAssembler && $loggedInAssembler->pivot->confirmation_status === 'started')
                        <a href="{{ route('assembler.my-schedule.finish.form', $assemblySchedule->id) }}" class="btn btn-success btn-sm">{{ __('Concluir Montagem') }}</a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <p><strong>{{ __('Data Agendada:') }}</strong> {{ \Carbon\Carbon::parse($assemblySchedule->scheduled_date)->format('d/m/Y') }}</p>
                    <p><strong>{{ __('Início Previsto:') }}</strong> {{ \Carbon\Carbon::parse($assemblySchedule->start_time)->format('H:i') }} </p>
                    <p><strong>{{ __('Término Previsto:') }}</strong> {{ \Carbon\Carbon::parse($assemblySchedule->end_time)->format('H:i') }}</p>
                    <p><strong>{{ __('Status:') }}</strong> {{ $assemblySchedule->status }}</p>
                    @if($assemblySchedule->notes)
                        <p><strong>{{ __('Observações:') }}</strong> {{ $assemblySchedule->notes }}</p>
                    @endif
                </div>
                <hr>
                <h6>{{ __('Montadores:') }}</h6>
                <ul>
                    @forelse ($assemblySchedule->assemblers as $assembler)
                        <li>{{ $assembler->name }}</li>
                    @empty
                        <li>{{ __('Nenhum montador atribuído.') }}</li>
                    @endforelse
                </ul>

                
            </div>
        </div>
        <!-- /Assembly Schedule details -->
        <div class="card mb-4">
            {{-- Avaliação --}}

            @php
                $hasCompleted = $assemblySchedule->assemblers->contains(function($a) {
                    return in_array($a->pivot->confirmation_status, ['completed', 'completed_with_pendencies']);
                });

                $evaluation = \App\Models\AssemblyScheduleEvaluation::where('assembly_schedule_id', $assemblySchedule->id)->first();
                $evaluationUrl = $evaluation ? route('assembly-evaluation.show', $evaluation->token) : null;
                // dd($evaluationUrl);
            @endphp
                

            @if ($evaluation )
                <div class="mb-3 mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">{{ __('Avaliação do Cliente') }}</h6>
                    </div>
                            <div class="card-body">

                                @if ($hasCompleted)
                    <div class="alert alert-info mt-3">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div>
                                <strong>{{ __('Link de Avaliação do Cliente') }}</strong>
                                @if ($evaluationUrl)
                                    <div class="small">{{ $evaluationUrl }}</div>
                                @else
                                    <div class="small">{{ __('O link será gerado automaticamente ao concluir a montagem.') }}</div>
                                @endif
                            </div>
                            @if ($evaluationUrl)
                                <button class="btn btn-sm btn-outline-primary" type="button" onclick="navigator.clipboard.writeText('{{ $evaluationUrl }}')">{{ __('Copiar Link') }}</button>
                            @endif
                        </div>
                    </div>

                            @if ($evaluation->submitted_at)
                                <div class="mb-2"><strong>{{ __('NPS:') }}</strong>
                                    <span class="badge px-2 bg-label-{{ $evaluation->nps_score >= 7 ? 'success' : ($evaluation->nps_score >= 4 ? 'warning' : 'danger') }} text-capitalized">
                                        {{ $evaluation->nps_score }}
                                    </span>
                                </div>
                                @if ($evaluation->comments)
                                    <div class="mb-2"><strong>{{ __('Comentários:') }}</strong> {{ $evaluation->comments }}</div>
                                @endif
                                @php $evalPhotos = $evaluation->photo_paths ? json_decode($evaluation->photo_paths, true) : []; @endphp
                                @if (!empty($evalPhotos))
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($evalPhotos as $photo)
                                            <img src="{{ Storage::url($photo) }}" alt="Foto da avaliação" class="img-thumbnail" style="max-width: 200px; height: auto;">
                                        @endforeach
                                    </div>
                                @endif
                                <div class="mt-2 text-muted small">{{ __('Enviado em:') }} {{ \Carbon\Carbon::parse($evaluation->submitted_at)->format('d/m/Y H:i') }}</div>
                            @endif
                            </div>
                        </div>
                    @endif
                @endif
        </div>

          <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Linha do Tempo da Montagem') }}</h5>
            </div>
            <div class="card-body">
                @forelse ($assemblySchedule->assemblers as $assembler)
                    <div class="mb-4">
                        <h6 class="mb-2">{{ $assembler->name }}</h6>

                        @if($assembler->pivot->started_at)
                            <div class="mb-2">
                                <strong>{{ __('Início:') }}</strong>
                                {{ \Carbon\Carbon::parse($assembler->pivot->started_at)->format('d/m/Y H:i') }}
                                @if($assembler->pivot->start_latitude && $assembler->pivot->start_longitude)
                                    <a href="https://www.google.com/maps?q={{ $assembler->pivot->start_latitude }},{{ $assembler->pivot->start_longitude }}" target="_blank" class="ms-2">{{ __('Ver no Maps') }}</a>
                                @endif
                            </div>
                            @if($assembler->pivot->start_photo_path)
                                <div class="mb-3">
                                    <img src="{{ Storage::url($assembler->pivot->start_photo_path) }}" alt="Foto de início" class="img-thumbnail" style="max-width: 200px; height: auto;">
                                </div>
                            @endif
                        @else
                            <div class="mb-2 text-muted">{{ __('Sem registro de início para este montador.') }}</div>
                        @endif

                        @if($assembler->pivot->finished_at)
                            <div class="mb-2">
                                <strong>{{ __('Conclusão:') }}</strong>
                                {{ \Carbon\Carbon::parse($assembler->pivot->finished_at)->format('d/m/Y H:i') }}
                            </div>
                            @php $finishPhotos = $assembler->pivot->finish_photo_paths ? json_decode($assembler->pivot->finish_photo_paths, true) : []; @endphp
                            @if(!empty($finishPhotos))
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    @foreach($finishPhotos as $photo)
                                        <img src="{{ Storage::url($photo) }}" alt="Foto de conclusão" class="img-thumbnail" style="max-width: 200px; height: auto;">
                                    @endforeach
                                </div>
                            @endif
                            @if($assembler->pivot->finish_notes)
                                <div class="mb-2">
                                    <strong>{{ __('Observação da conclusão:') }}</strong>
                                    <span>{{ $assembler->pivot->finish_notes }}</span>
                                </div>
                            @endif
                            @if($assembler->pivot->finish_pending_reason)
                                <div class="mb-2">
                                    <strong>{{ __('Pendências:') }}</strong>
                                    <span>{{ $assembler->pivot->finish_pending_reason }}</span>
                                </div>
                            @endif
                        @else
                            <div class="mb-2 text-muted">{{ __('Sem registro de conclusão para este montador.') }}</div>
                        @endif

                        <hr>
                    </div>
                @empty
                    <div class="text-muted">{{ __('Nenhum montador para exibir a linha do tempo.') }}</div>
                @endforelse
            </div>
        </div>
    </div>
    </div>
    </div>
    <!-- /Left Column -->

    <!-- Right Column -->
    <div class="col-xl-4 col-lg-4 col-md-4 order-1 order-md-1">
        <!-- Customer details -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Detalhes do Cliente') }}</h5>
                @if(!Auth::user()->hasRole('Montador'))
                    <a href="javascript:void(0);" class="btn btn-label-primary btn-sm">{{ __('Editar') }}</a>
                @endif
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="d-flex flex-column">
                        <h6 class="mb-1">{{ $assemblySchedule->sale->customer->full_name ?? $assemblySchedule->sale->customer->company_name }}</h6>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                    @if ($assemblySchedule->sale->customer->customer_type === 'individual')
                        <small class="text-muted">{{ __('CPF: ') }}{{ $assemblySchedule->sale->customer->cpf ? sprintf('%s.%s.%s-%s', substr($assemblySchedule->sale->customer->cpf, 0, 3), substr($assemblySchedule->sale->customer->cpf, 3, 3), substr($assemblySchedule->sale->customer->cpf, 6, 3), substr($assemblySchedule->sale->customer->cpf, 9, 2)) : '-' }}</small>
                    @elseif ($assemblySchedule->sale->customer->customer_type === 'company')
                        <div>
                            <div class="row"><small class="text-muted">{{ __('CNPJ: ') }}{{ $assemblySchedule->sale->customer->cnpj ? sprintf('%s.%s.%s/%s-%s', substr($assemblySchedule->sale->customer->cnpj, 0, 2), substr($assemblySchedule->sale->customer->cnpj, 2, 3), substr($assemblySchedule->sale->customer->cnpj, 5, 3), substr($assemblySchedule->sale->customer->cnpj, 8, 4), substr($assemblySchedule->sale->customer->cnpj, 12, 2)) : '-' }}</small></div>
                            <div class="row mt-2"><small class="text-muted">{{ __('IE: ') }}{{ $assemblySchedule->sale->customer->ie ?? '-' }}</small></div>
                        </div>
                    @endif
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="bx bx-cart me-2"></i>
                    <span class="fw-medium">{{ __('Pedidos:') }}</span>
                    <span class="ms-auto">12</span> {{-- Placeholder for number of orders --}}
                </div>
                <hr>
                <h6 class="mb-3">{{ __('Contato para Entrega') }}</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-medium">{{ __('Nome:') }}</span>
                    <span>{{ $assemblySchedule->sale->contact_name ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-medium">{{ __('Email:') }}</span>
                    <span>{{ $assemblySchedule->sale->contact_email ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="fw-medium">{{ __('Telefone:') }}</span>
                    <span><a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $assemblySchedule->sale->contact_phone) }}?text=Olá%2C%20aqui%20é%20da%20Bassani%20Móveis" target="_blank">{{ $assemblySchedule->sale->contact_phone ?? '-' }}</a></span>
                </div>
            </div>
        </div>
        <!-- /Customer details -->

        <!-- Sale details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Detalhes da Venda Associada') }}</h5>
            </div>
            <div class="card-body">
                <p><strong>{{ __('Código da Venda:') }}</strong> {{ $assemblySchedule->sale->erp_code }}</p>
                <p><strong>{{ __('Data da Venda:') }}</strong> {{ $assemblySchedule->sale->issue_date->format('d/m/Y') }}</p>
                <p><strong>{{ __('Produtos:') }}</strong></p>
                <ul>
                    @forelse ($assemblySchedule->sale->saleItems as $item)
                        <li>{{ $item->product->name }} (QTD: {{ ceil($item->quantity) }})</li>
                    @empty
                        <li>{{ __('Nenhum produto encontrado para esta venda.') }}</li>
                    @endforelse
                </ul>
                <p><strong>{{ __('Status da Venda:') }}</strong> {{ $assemblySchedule->sale->order_status }}</p>
            </div>
        </div>
        <!-- /Sale details -->
        <!-- Shipping address -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Endereço de Envio') }}</h5>
                @if(!Auth::user()->hasRole('Montador'))
                    <a href="javascript:void(0);" class="btn btn-label-primary btn-sm">{{ __('Editar') }}</a>
                @endif
            </div>
            <div class="card-body">
                <p class="mb-0">
                    {{ $assemblySchedule->sale->customer->shippingAddress->address_line_1 ?? 'N/A' }}<br>
                    {{ $assemblySchedule->sale->customer->shippingAddress->city ?? '' }}, {{ $assemblySchedule->sale->customer->shippingAddress->state ?? '' }} {{ $assemblySchedule->sale->customer->shippingAddress->zip_code ?? '' }}<br>
                    {{ $assemblySchedule->sale->customer->shippingAddress->country ?? '' }}
                </p>
            </div>
        </div>
        <!-- /Shipping address -->

        <!-- Billing address -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Endereço de Cobrança') }}</h5>
                @if(!Auth::user()->hasRole('Montador'))
                    <a href="javascript:void(0);" class="btn btn-label-primary btn-sm">{{ __('Editar') }}</a>
                @endif
            </div>
            <div class="card-body">
                <p class="mb-0">
                    {{ $assemblySchedule->sale->customer->address_street ?? 'N/A' }}, {{ $assemblySchedule->sale->customer->address_number ?? '' }}<br>
                    {{ $assemblySchedule->sale->customer->address_neighborhood ?? '' }}, {{ $assemblySchedule->sale->customer->address_city ?? '' }} - {{ $assemblySchedule->sale->customer->address_state ?? '' }}<br>
                    {{ $assemblySchedule->sale->customer->address_zip_code ?? '' }}
                </p>
            </div>
        </div>
        <!-- /Billing address -->
    </div>
    <!-- /Right Column -->
</div>

{{-- ═══ Despesas de Campo ═══ --}}
@php
    $expenseAssembler = null;
    if (Auth::user()->hasRole('Montador') && Auth::user()->assembler) {
        $expAssembler = $assemblySchedule->assemblers->where('user_id', Auth::user()->id)->first();
        if ($expAssembler) {
            $expenseAssembler = $expAssembler;
        }
    }
@endphp

@if($expenseAssembler)
    {{-- Assembler view: form + their own expenses --}}
    <div class="row">
        <div class="col-xl-6 col-lg-8 col-md-10 mx-auto">
            @include('admin.assembly-expenses._expense_card', [
                'assemblySchedule' => $assemblySchedule,
                'assembler'        => $expenseAssembler,
            ])
        </div>
    </div>
@else
    {{-- Admin view: read-only expense summary --}}
    @php
        $allExpenses = $assemblySchedule->expenses()->with('assembler')->latest()->get();
    @endphp
    @if($allExpenses->count() > 0)
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="bx bx-receipt me-2 text-primary"></i>Despesas de Campo</h6>
            <div class="d-flex gap-2">
                <span class="badge bg-label-warning">{{ $allExpenses->where('status','pendente')->count() }} pendente(s)</span>
                <span class="badge bg-label-success">R$ {{ number_format($allExpenses->where('status','aprovado')->sum('amount'), 2, ',', '.') }} aprovados</span>
                <a href="{{ route('assembly-expenses.index', ['assembly_schedule_id' => $assemblySchedule->id]) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-list-ul me-1"></i>Conciliar
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Montador</th>
                            <th>Categoria</th>
                            <th>Data</th>
                            <th class="text-end">Valor</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allExpenses as $exp)
                            <tr>
                                <td class="small">{{ $exp->assembler->name ?? '—' }}</td>
                                <td>
                                    <i class="bx {{ $exp->category_icon }} me-1 text-primary"></i>
                                    <span class="small">{{ $exp->category }}</span>
                                </td>
                                <td class="small text-muted">{{ $exp->date->format('d/m/Y') }}</td>
                                <td class="text-end small fw-semibold">R$ {{ number_format($exp->amount, 2, ',', '.') }}</td>
                                <td><span class="badge {{ $exp->status_badge }}">{{ $exp->status_label }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end small">Total Aprovado:</td>
                            <td class="text-end text-success">R$ {{ number_format($allExpenses->where('status','aprovado')->sum('amount'), 2, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif
@endif

@endsection
