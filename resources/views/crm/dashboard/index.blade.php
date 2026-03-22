@extends('layouts.app')

@section('title', 'CRM Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Pipeline Value -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-dollar"></i></span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Valor Total em Pipeline</span>
                    <h3 class="card-title mb-2">R$ {{ number_format($totalPipeline, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Conversion Rate -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-trending-up"></i></span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Taxa de Conversão</span>
                    <h3 class="card-title mb-2">{{ number_format($conversionRate, 1) }}%</h3>
                </div>
            </div>
        </div>

        <!-- Architect Ranking -->
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Top Arquitetos (ROI)</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Arquiteto</th>
                                <th>Valor Gerado</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($architectRanking as $rank)
                                <tr>
                                    <td><i class="bx bx-user me-2"></i> <strong>{{ $rank->architect->name }}</strong></td>
                                    <td>R$ {{ number_format($rank->total_generated, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Nenhum dado disponível.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
