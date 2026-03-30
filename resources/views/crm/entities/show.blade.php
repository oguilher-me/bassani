@extends('layouts/contentNavbarLayout')

@section('title', 'Visão 360º - ' . $entity->name)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ url()->previous() }}" class="btn btn-icon btn-outline-secondary me-3">
                <i class="bx bx-arrow-back"></i>
            </a>
            <div>
                <h4 class="fw-bold mb-1">{{ $entity->name }}</h4>
                <p class="text-muted mb-0">{{ __('Visão 360º') }} - CRM</p>
            </div>
        </div>
        <span class="badge bg-label-primary rounded-pill px-3 py-2 fs-6">{{ ucfirst($entity->type) }}</span>
    </div>

    <div class="row">
        {{-- Entity Profile --}}
        <div class="col-xl-4 col-lg-5 col-md-5 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="avatar rounded-circle d-inline-flex align-items-center justify-content-center bg-label-primary mb-3" style="width: 80px; height: 80px;">
                        <span class="display-4 text-white">{{ substr($entity->name, 0, 2) }}</span>
                    </div>
                    <h5 class="mb-2 fw-bold">{{ $entity->name }}</h5>
                    <span class="badge bg-label-secondary rounded-pill mb-3">{{ ucfirst($entity->type) }}</span>
                    
                    <hr class="my-3">
                    
                    <div class="text-start px-3">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">{{ __('Segmento') }}</span>
                            <span class="fw-semibold">{{ ucfirst($entity->segment) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">{{ __('Documento') }}</span>
                            <span class="fw-semibold">{{ $entity->document ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">{{ __('Responsável') }}</span>
                            <span class="fw-semibold">{{ $entity->assignedUser->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Timeline --}}
        <div class="col-xl-8 col-lg-7 col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bx bx-history text-danger me-2"></i>{{ __('Linha do Tempo 360º') }}
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="timeline ms-2">
                        @forelse($timeline as $item)
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-{{ $item['type'] == 'interaction' ? 'primary' : 'warning' }}"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0 fw-semibold">
                                            @if($item['type'] == 'interaction')
                                                {{ ucfirst($item['data']->type) }} - {{ $item['data']->user->name }}
                                            @else
                                                {{ __('Proposta') }} #{{ $item['data']->version_number }} ({{ $item['data']->status }})
                                            @endif
                                        </h6>
                                        <small class="text-muted">{{ $item['date']->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <p class="mb-2">{{ $item['opportunity'] }}</p>
                                    @if($item['type'] == 'interaction')
                                        <p class="mb-0 text-muted">{{ $item['data']->content }}</p>
                                    @else
                                        <p class="mb-0 fw-bold" style="color: #DE0802;">
                                            {{ __('Valor') }}: R$ {{ number_format($item['data']->total_value, 2, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-secondary"></span>
                                <div class="timeline-event">
                                    <div class="text-center py-4">
                                        <i class="bx bx-time fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">{{ __('Nenhuma atividade registrada.') }}</p>
                                    </div>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
