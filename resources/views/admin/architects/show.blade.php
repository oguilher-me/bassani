@extends('layouts.app')

@section('title', 'Dashboard - ' . $architect->name)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
         <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Arquitetos /</span> {{ $architect->name }}
        </h4>
        <div>
            <a href="{{ route('crm.architects.edit', $architect->id) }}" class="btn btn-primary">Editar</a>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-primary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-money"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">R$ {{ number_format($totalSales, 2, ',', '.') }}</h4>
                    </div>
                    <p class="mb-1">Volume Total de Vendas</p>
                    <p class="mb-0">
                        <small class="text-muted">Projetos Fechados (Won)</small>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-warning h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-wallet"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">R$ {{ number_format($totalCommission, 2, ',', '.') }}</h4>
                    </div>
                    <p class="mb-1">RT Acumulada (Est.)</p>
                    <p class="mb-0">
                        <small class="text-muted">Baseado em {{ $architect->rt_percentage }}%</small>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-info h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-file"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ $architect->opportunities->count() }}</h4>
                    </div>
                    <p class="mb-1">Projetos Totais</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-4">
             <div class="card card-border-shadow-secondary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-id-card"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ $architect->document_type }}</h4>
                    </div>
                     <p class="mb-1">{{ $architect->document_number }}</p>
                     @if(isset($architect->bank_data['pix']))
                        <small class="text-muted">Pix: {{ $architect->bank_data['pix'] }}</small>
                     @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Table -->
    <div class="card">
        <h5 class="card-header">Histórico de Projetos</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Projeto</th>
                        <th>Status</th>
                        <th>Data Fechamento</th>
                        <th>Valor (R$)</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                     @forelse($architect->opportunities as $opportunity)
                        <tr>
                            <td><a href="{{ route('crm.pipeline.index') }}">{{ $opportunity->title }}</a></td>
                            <td>
                                <span class="badge bg-label-{{ 
                                    $opportunity->stage_id == 'won' ? 'success' : 
                                    ($opportunity->stage_id == 'lost' ? 'danger' : 'primary') 
                                }}">{{ ucfirst($opportunity->stage_id) }}</span>
                            </td>
                            <td>{{ $opportunity->expected_closing_date ? $opportunity->expected_closing_date->format('d/m/Y') : '-' }}</td>
                            <td>R$ {{ number_format($opportunity->estimated_value, 2, ',', '.') }}</td>
                        </tr>
                     @empty
                        <tr><td colspan="4" class="text-center">Nenhum projeto associado.</td></tr>
                     @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
