@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes da Multa de Veículo'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Detalhes da Multa de Veículo') }}</h4>
        <p class="text-muted mb-0">{{ $vehicleFine->fine_number }} - {{ $vehicleFine->vehicle->modelo ?? '-' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('vehicle_fines.edit', $vehicleFine->id) }}" class="btn btn-primary">
            <i class="bx bx-edit me-1"></i> {{ __('Editar') }}
        </a>
        <a href="{{ route('vehicle_fines.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- Fine Details Card --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted mb-4">
                    <i class="bx bx-receipt me-2 text-danger"></i>{{ __('Informações da Multa') }}
                </h6>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-hash"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Número da Multa') }}</small>
                                <span class="fw-semibold">{{ $vehicleFine->fine_number }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-category"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Tipo') }}</small>
                                <span class="fw-semibold">{{ $vehicleFine->fine_type }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-danger d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-money"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Valor da Multa') }}</small>
                                <span class="fw-semibold fs-5">R$ {{ number_format($vehicleFine->fine_amount, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-star"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Pontos') }}</small>
                                <span class="fw-semibold">{{ $vehicleFine->points }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                {{-- Vehicle & Driver --}}
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-car me-2 text-danger"></i>{{ __('Veículo e Motorista') }}
                </h6>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bx bx-car text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Veículo') }}</small>
                                <span class="fw-semibold">{{ $vehicleFine->vehicle->modelo ?? '-' }}</span>
                                <small class="text-muted d-block">{{ $vehicleFine->vehicle->placa ?? '-' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bx bx-user text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Motorista') }}</small>
                                <span class="fw-semibold">{{ $vehicleFine->driver ? $vehicleFine->driver->full_name : __('Não informado') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                {{-- Location --}}
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-map me-2 text-danger"></i>{{ __('Local da Infração') }}
                </h6>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bx bx-map-pin text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Local') }}</small>
                                <span class="fw-semibold">{{ $vehicleFine->location }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bx bx-building text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Autoridade') }}</small>
                                <span class="fw-semibold">{{ $vehicleFine->authority }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                {{-- Description --}}
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-note me-2 text-danger"></i>{{ __('Descrição') }}
                </h6>
                <p class="mb-0">{{ $vehicleFine->description }}</p>
            </div>
        </div>
    </div>
    
    {{-- Payment & Dates Card --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                {{-- Payment Status --}}
                <div class="text-center mb-4 pb-4 border-bottom">
                    <div class="mb-3">
                        @if($vehicleFine->payment_status->value == 'paid')
                            <span class="badge bg-success rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-check-circle me-1"></i>{{ $vehicleFine->payment_status->getLabel() }}
                            </span>
                        @elseif($vehicleFine->payment_status->value == 'pending')
                            <span class="badge bg-warning rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-time me-1"></i>{{ $vehicleFine->payment_status->getLabel() }}
                            </span>
                        @else
                            <span class="badge bg-danger rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-x-circle me-1"></i>{{ $vehicleFine->payment_status->getLabel() }}
                            </span>
                        @endif
                    </div>
                    <p class="text-muted small mb-0">{{ __('Responsável') }}: {{ $vehicleFine->responsible_for_payment->getLabel() }}</p>
                </div>
                
                {{-- Dates --}}
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-calendar me-2 text-danger"></i>{{ __('Datas') }}
                </h6>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                        <i class="bx bx-calendar text-primary small"></i>
                    </div>
                    <div class="flex-grow-1">
                        <small class="text-muted d-block">{{ __('Data da Infração') }}</small>
                        <span class="fw-semibold small">{{ $vehicleFine->infraction_date ? $vehicleFine->infraction_date->format('d/m/Y') : '-' }}</span>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                        <i class="bx bx-calendar text-primary small"></i>
                    </div>
                    <div class="flex-grow-1">
                        <small class="text-muted d-block">{{ __('Data de Vencimento') }}</small>
                        @if($vehicleFine->due_date)
                            @if($vehicleFine->due_date->isPast() && $vehicleFine->payment_status->value !== 'paid')
                                <span class="badge bg-danger small">{{ $vehicleFine->due_date->format('d/m/Y') }}</span>
                            @else
                                <span class="fw-semibold small">{{ $vehicleFine->due_date->format('d/m/Y') }}</span>
                            @endif
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                        <i class="bx bx-calendar-check text-primary small"></i>
                    </div>
                    <div class="flex-grow-1">
                        <small class="text-muted d-block">{{ __('Data do Pagamento') }}</small>
                        <span class="fw-semibold small">{{ $vehicleFine->payment_date ? $vehicleFine->payment_date->format('d/m/Y') : '—' }}</span>
                    </div>
                </div>
                
                @if($vehicleFine->paid_amount)
                <div class="bg-light rounded-3 p-3 text-center mt-4">
                    <span class="d-block mb-1 small text-muted">{{ __('Valor Pago') }}</span>
                    <span class="fw-bold fs-5 text-success">R$ {{ number_format($vehicleFine->paid_amount, 2, ',', '.') }}</span>
                </div>
                @endif
                
                @if($vehicleFine->document_reference)
                <hr class="my-4">
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-file me-2 text-danger"></i>{{ __('Documento') }}
                </h6>
                <a href="{{ Storage::url($vehicleFine->document_reference) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                    <i class="bx bx-download me-1"></i> {{ __('Visualizar Documento') }}
                </a>
                @endif
                
                <hr class="my-4">
                
                {{-- Timestamps --}}
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-time me-2 text-danger"></i>{{ __('Informações do Sistema') }}
                </h6>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">{{ __('Criado em') }}</span>
                    <span class="fw-semibold small">{{ $vehicleFine->created_at ? $vehicleFine->created_at->format('d/m/Y H:i') : '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">{{ __('Atualizado em') }}</span>
                    <span class="fw-semibold small">{{ $vehicleFine->updated_at ? $vehicleFine->updated_at->format('d/m/Y H:i') : '-' }}</span>
                </div>
                
                @if($vehicleFine->comments)
                <hr class="my-4">
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-note me-2 text-danger"></i>{{ __('Comentários') }}
                </h6>
                <p class="mb-0 small">{{ $vehicleFine->comments }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection