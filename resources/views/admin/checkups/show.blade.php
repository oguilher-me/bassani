@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes do Check-up'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">
            {{ __('Detalhes do Check-up') }} 
            <span class="{{ $checkup->status === 'passed' ? 'text-success' : 'text-danger' }}">#{{ $checkup->id }}</span>
        </h4>
        <p class="text-muted mb-0">{{ __('Informações completas da conferência do veículo') }}</p>
    </div>
    <a href="{{ route('checkups.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
    </a>
</div>

<div class="row g-4">
    {{-- Main Info --}}
    <div class="col-lg-8">
        {{-- Status Card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle d-flex align-items-center justify-content-center {{ $checkup->status === 'passed' ? 'bg-label-success' : 'bg-label-danger' }}" style="width: 64px; height: 64px;">
                        <i class="bx {{ $checkup->status === 'passed' ? 'bx-check' : 'bx-x' }} fs-2"></i>
                    </div>
                    <div class="ms-4">
                        <h4 class="mb-1">
                            {{ $checkup->status === 'passed' ? __('Check-up Aprovado') : __('Check-up Reprovado') }}
                        </h4>
                        <p class="text-muted mb-0">
                            {{ $checkup->status === 'passed' 
                                ? __('Veículo liberado para uso.') 
                                : __('Veículo bloqueado - itens restritivos com falha.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Responses --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-list-check text-danger me-2"></i>{{ __('Respostas do Checklist') }}
                </h6>
            </div>
            <div class="card-body p-0">
                @forelse($checkup->responses as $response)
                <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }} {{ !$response->is_ok ? 'bg-danger-subtle' : '' }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                {{ $response->checklistItem->description }}
                                @if($response->checklistItem->is_restrictive)
                                    <span class="badge bg-label-danger ms-2" style="font-size: 0.7rem;">
                                        <i class="bx bx-block me-1"></i>{{ __('Restritivo') }}
                                    </span>
                                @endif
                            </h6>
                            @if($response->observation)
                                <p class="text-muted small mb-0 mt-1">
                                    <i class="bx bx-note me-1"></i>{{ $response->observation }}
                                </p>
                            @endif
                        </div>
                        <div>
                            @if($response->is_ok)
                                <span class="badge bg-label-success rounded-pill px-3 py-2">
                                    <i class="bx bx-check me-1"></i>{{ __('OK') }}
                                </span>
                            @else
                                <span class="badge bg-label-danger rounded-pill px-3 py-2">
                                    <i class="bx bx-x me-1"></i>{{ __('Não OK') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <p class="text-muted mb-0">{{ __('Nenhuma resposta registrada.') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Side Info --}}
    <div class="col-lg-4">
        {{-- Vehicle Info --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-car text-danger me-2"></i>{{ __('Veículo') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-car fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $checkup->vehicle->placa ?? 'N/A' }}</h6>
                        <small class="text-muted">{{ $checkup->vehicle->modelo ?? '' }}</small>
                    </div>
                </div>
                @if($checkup->vehicle)
                <div class="small">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">{{ __('Marca:') }}</span>
                        <span>{{ $checkup->vehicle->carBrand->name ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">{{ __('Ano:') }}</span>
                        <span>{{ $checkup->vehicle->ano_fabricacao ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">{{ __('Status:') }}</span>
                        <span class="badge bg-label-{{ $checkup->vehicle->status === 'Ativo' ? 'success' : 'warning' }} rounded-pill px-2 py-1">
                            {{ $checkup->vehicle->status ?? 'N/A' }}
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Driver Info --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-user text-danger me-2"></i>{{ __('Motorista') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-secondary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        {{ strtoupper(substr($checkup->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $checkup->user->name ?? 'N/A' }}</h6>
                        <small class="text-muted">{{ $checkup->user->email ?? '' }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Date/Time --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-time text-danger me-2"></i>{{ __('Data/Hora') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <i class="bx bx-calendar text-muted me-2"></i>
                    <span>{{ $checkup->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bx bx-time-five text-muted me-2"></i>
                    <span>{{ $checkup->created_at->format('H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
