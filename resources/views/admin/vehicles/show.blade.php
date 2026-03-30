@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes do Veículo'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Detalhes do Veículo') }}</h4>
        <p class="text-muted mb-0">{{ $vehicle->placa }} - {{ $vehicle->modelo }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-primary">
            <i class="bx bx-edit me-1"></i> {{ __('Editar') }}
        </a>
        <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- Vehicle Info Card --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted mb-4">
                    <i class="bx bx-car me-2 text-danger"></i>{{ __('Informações do Veículo') }}
                </h6>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-car"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Placa') }}</small>
                                <span class="fw-semibold">{{ $vehicle->placa }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-tag"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Modelo') }}</small>
                                <span class="fw-semibold">{{ $vehicle->modelo }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-building"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Marca') }}</small>
                                <span class="fw-semibold">{{ $vehicle->carBrand->name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-secondary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-calendar"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Ano Fabricação') }}</small>
                                <span class="fw-semibold">{{ $vehicle->ano_fabricacao }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-danger d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-tachometer"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Quilometragem') }}</small>
                                <span class="fw-semibold">{{ number_format($vehicle->quilometragem_atual, 0, ',', '.') }} KM</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-success d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-cube"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Capacidade Cúbica') }}</small>
                                <span class="fw-semibold">{{ $vehicle->cubic_capacity ? number_format($vehicle->cubic_capacity, 2, ',', '.') . ' m³' : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Status & Additional Info Card --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                {{-- Status --}}
                <div class="text-center mb-4 pb-4 border-bottom">
                    <div class="mb-3">
                        @if($vehicle->status == 'Ativo')
                            <span class="badge bg-success rounded-pill px-3 py-2">{{ __('Ativo') }}</span>
                        @elseif($vehicle->status == 'Em manutenção')
                            <span class="badge bg-warning rounded-pill px-3 py-2">{{ __('Em manutenção') }}</span>
                        @else
                            <span class="badge bg-danger rounded-pill px-3 py-2">{{ __('Inativo') }}</span>
                        @endif
                    </div>
                </div>
                
                {{-- Additional Info --}}
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-info-circle me-2 text-danger"></i>{{ __('Informações Adicionais') }}
                </h6>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="bx bx-calendar-check text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('Data de Aquisição') }}</small>
                        <span class="fw-semibold">{{ $vehicle->data_aquisicao ? \Carbon\Carbon::parse($vehicle->data_aquisicao)->format('d/m/Y') : '-' }}</span>
                    </div>
                </div>
                
                @if($vehicle->observacoes)
                <div class="mb-4">
                    <small class="text-muted d-block mb-2">{{ __('Observações') }}</small>
                    <p class="mb-0 small">{{ $vehicle->observacoes }}</p>
                </div>
                @endif
                
                <hr class="my-4">
                
                {{-- Timestamps --}}
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-time me-2 text-danger"></i>{{ __('Informações do Sistema') }}
                </h6>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">{{ __('Criado em') }}</span>
                    <span class="fw-semibold small">{{ $vehicle->created_at ? $vehicle->created_at->format('d/m/Y H:i') : '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">{{ __('Atualizado em') }}</span>
                    <span class="fw-semibold small">{{ $vehicle->updated_at ? $vehicle->updated_at->format('d/m/Y H:i') : '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Vehicle History / Maintenance Section --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-history text-danger me-2"></i>{{ __('Histórico do Veículo') }}
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="text-center py-5">
                    <i class="bx bx-history fs-1 text-muted opacity-50"></i>
                    <p class="text-muted mt-2">{{ __('Nenhum histórico registrado') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection