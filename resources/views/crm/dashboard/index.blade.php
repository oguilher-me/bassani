@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard CRM')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('Dashboard CRM') }}</h4>
            <p class="text-muted mb-0">{{ __('Visão geral do pipeline e desempenho') }}</p>
        </div>
        <a href="{{ route('crm.pipeline.index') }}" class="btn btn-primary">
            <i class="bx bx-kanban me-1"></i> {{ __('Ver Pipeline') }}
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        {{-- Pipeline Value --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-dollar fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">{{ __('Valor em Pipeline') }}</p>
                            <h4 class="mb-0 fw-bold" style="color: #DE0802;">R$ {{ number_format($totalPipeline, 2, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Conversion Rate --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar rounded-circle bg-label-success d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-trending-up fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">{{ __('Taxa de Conversão') }}</p>
                            <h4 class="mb-0 fw-bold">{{ number_format($conversionRate, 1) }}%</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Opportunities --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-briefcase fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">{{ __('Oportunidades') }}</p>
                            <h4 class="mb-0 fw-bold">{{ $opportunitiesCount ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Won Opportunities --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-check-circle fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">{{ __('Ganhas') }}</p>
                            <h4 class="mb-0 fw-bold">{{ $wonCount ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Architect Ranking --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bx bx-user-pin text-danger me-2"></i>{{ __('Top Arquitetos (ROI)') }}
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 py-3 px-4">{{ __('Arquiteto') }}</th>
                            <th class="border-0 py-3">{{ __('Oportunidades') }}</th>
                            <th class="border-0 py-3 text-end">{{ __('Valor Gerado') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($architectRanking as $rank)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ substr($rank->architect->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <span class="fw-semibold">{{ $rank->architect->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-label-info rounded-pill">{{ $rank->opportunities_count ?? 0 }}</span>
                                </td>
                                <td class="py-3 text-end">
                                    <span class="fw-bold" style="color: #DE0802;">
                                        R$ {{ number_format($rank->total_generated, 2, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <i class="bx bx-data fs-1 d-block mb-2 text-muted"></i>
                                    <p class="text-muted mb-0">{{ __('Nenhum dado disponível.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
