@extends('layouts.app')

@section('title', 'Visão 360º - ' . $entity->name)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Entity Profile -->
        <div class="col-xl-4 col-lg-5 col-md-5">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="user-avatar-section">
                        <div class=" d-flex align-items-center flex-column">
                            <div class="avatar avatar-xl mb-3">
                                <span class="avatar-initial rounded-circle bg-label-primary display-4">{{ substr($entity->name, 0, 2) }}</span>
                            </div>
                            <h5 class="mb-2">{{ $entity->name }}</h5>
                            <span class="badge bg-label-secondary">{{ ucfirst($entity->type) }}</span>
                        </div>
                    </div>
                    <div class="py-3 border-bottom border-top mt-4">
                        <div class="info-container">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><span class="fw-bold me-2">Segmento:</span> {{ ucfirst($entity->segment) }}</li>
                                <li class="mb-2"><span class="fw-bold me-2">Documento:</span> {{ $entity->document ?? '-' }}</li>
                                <li class="mb-2"><span class="fw-bold me-2">Responsável:</span> {{ $entity->assignedUser->name ?? 'N/A' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="col-xl-8 col-lg-7 col-md-7">
            <div class="card card-action mb-4">
                <div class="card-header align-items-center">
                    <h5 class="card-action-title mb-0"><i class="bx bx-list-ul me-2"></i>Linha do Tempo 360º</h5>
                </div>
                <div class="card-body">
                    <ul class="timeline ms-2">
                        @forelse($timeline as $item)
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-{{ $item['type'] == 'interaction' ? 'primary' : 'warning' }}"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">
                                            @if($item['type'] == 'interaction')
                                                {{ ucfirst($item['data']->type) }} - {{ $item['data']->user->name }}
                                            @else
                                                Proposta #{{ $item['data']->version_number }} ({{ $item['data']->status }})
                                            @endif
                                        </h6>
                                        <small class="text-muted">{{ $item['date']->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <p class="mb-2">{{ $item['opportunity'] }}</p>
                                    @if($item['type'] == 'interaction')
                                        <p>{{ $item['data']->content }}</p>
                                    @else
                                        <p>Valor: R$ {{ number_format($item['data']->total_value, 2, ',', '.') }}</p>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-secondary"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Nenhuma atividade registrada.</h6>
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
