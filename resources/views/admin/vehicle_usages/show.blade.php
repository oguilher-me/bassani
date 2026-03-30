@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes do Uso de Veículo'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Detalhes do Uso de Veículo') }}</h4>
        <p class="text-muted mb-0">{{ $vehicleUsage->vehicle->modelo ?? '-' }} ({{ $vehicleUsage->vehicle->placa ?? '-' }})</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('vehicle_usages.edit', $vehicleUsage->id) }}" class="btn btn-primary">
            <i class="bx bx-edit me-1"></i> {{ __('Editar') }}
        </a>
        <a href="{{ route('vehicle_usages.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- Trip Info Card --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted mb-4">
                    <i class="bx bx-trip me-2 text-danger"></i>{{ __('Informações da Viagem') }}
                </h6>
                
                <div class="row g-4">
                    {{-- Vehicle --}}
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-car"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Veículo') }}</small>
                                <span class="fw-semibold">{{ $vehicleUsage->vehicle->modelo ?? '-' }}</span>
                                <small class="text-muted d-block">{{ $vehicleUsage->vehicle->placa ?? '-' }}</small>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Driver --}}
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-user"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Motorista') }}</small>
                                <span class="fw-semibold">{{ $vehicleUsage->driver->full_name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Route --}}
                    <div class="col-md-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-map"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Rota/Destino') }}</small>
                                <span class="fw-semibold">{{ $vehicleUsage->route_destination ?? __('Não informado') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                {{-- Departure Section --}}
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-log-out me-2 text-danger"></i>{{ __('Dados de Saída') }}
                </h6>
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bx bx-calendar text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Data e Hora') }}</small>
                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($vehicleUsage->departure_date)->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bx bx-tachometer text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Quilometragem') }}</small>
                                <span class="fw-semibold">{{ number_format($vehicleUsage->departure_mileage, 0, ',', '.') }} km</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Return Section --}}
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-log-in me-2 text-danger"></i>{{ __('Dados de Retorno') }}
                </h6>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bx bx-calendar text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Data e Hora') }}</small>
                                @if($vehicleUsage->return_date)
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($vehicleUsage->return_date)->format('d/m/Y H:i') }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bx bx-tachometer text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Quilometragem') }}</small>
                                @if($vehicleUsage->return_mileage)
                                    <span class="fw-semibold">{{ number_format($vehicleUsage->return_mileage, 0, ',', '.') }} km</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($vehicleUsage->departure_mileage && $vehicleUsage->return_mileage)
                <hr class="my-4">
                
                {{-- Trip Summary --}}
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-bar-chart-alt-2 me-2 text-danger"></i>{{ __('Resumo da Viagem') }}
                </h6>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="bg-light rounded-3 p-3 text-center">
                            <span class="d-block mb-1 small text-muted">{{ __('KM Percorridos') }}</span>
                            <span class="fw-bold fs-5 text-primary">{{ number_format($vehicleUsage->return_mileage - $vehicleUsage->departure_mileage, 0, ',', '.') }} km</span>
                        </div>
                    </div>
                    @if($vehicleUsage->return_date && $vehicleUsage->departure_date)
                    <div class="col-md-4">
                        <div class="bg-light rounded-3 p-3 text-center">
                            <span class="d-block mb-1 small text-muted">{{ __('Duração') }}</span>
                            <span class="fw-bold fs-5 text-primary">{{ \Carbon\Carbon::parse($vehicleUsage->departure_date)->diff(\Carbon\Carbon::parse($vehicleUsage->return_date))->format('%hh %im') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Status & Observations Card --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                {{-- Status --}}
                <div class="text-center mb-4 pb-4 border-bottom">
                    <div class="mb-3">
                        @if($vehicleUsage->trip_status == 'Em andamento')
                            <span class="badge bg-warning rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-loader-alt me-1"></i>{{ __('Em andamento') }}
                            </span>
                        @else
                            <span class="badge bg-success rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-check-circle me-1"></i>{{ __('Finalizada') }}
                            </span>
                        @endif
                    </div>
                    @if($vehicleUsage->trip_status == 'Em andamento')
                        <p class="text-muted small mb-0">{{ __('Viagem ainda em andamento') }}</p>
                    @endif
                </div>
                
                {{-- Observations --}}
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-note me-2 text-danger"></i>{{ __('Observações') }}
                </h6>
                <p class="mb-4 small">
                    {{ $vehicleUsage->observations ?: __('Nenhuma observação registrada') }}
                </p>
                
                <hr class="my-4">
                
                {{-- Timestamps --}}
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-time me-2 text-danger"></i>{{ __('Informações do Sistema') }}
                </h6>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">{{ __('Criado em') }}</span>
                    <span class="fw-semibold small">{{ $vehicleUsage->created_at ? $vehicleUsage->created_at->format('d/m/Y H:i') : '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">{{ __('Atualizado em') }}</span>
                    <span class="fw-semibold small">{{ $vehicleUsage->updated_at ? $vehicleUsage->updated_at->format('d/m/Y H:i') : '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">{{ __('Precisa fazer alterações?') }}</span>
                    <div class="d-flex gap-2">
                        @if($vehicleUsage->trip_status == 'Em andamento')
                            <a href="{{ route('vehicle_usages.edit', $vehicleUsage->id) }}" class="btn btn-success">
                                <i class="bx bx-check-circle me-1"></i> {{ __('Finalizar Viagem') }}
                            </a>
                        @endif
                        <a href="{{ route('vehicle_usages.edit', $vehicleUsage->id) }}" class="btn btn-outline-primary">
                            <i class="bx bx-edit me-1"></i> {{ __('Editar Registro') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection