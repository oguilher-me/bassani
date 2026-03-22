@extends('layouts/contentNavbarLayout')

@section('title', __('Relatório de Performance da Frota'))

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">{{ __('Relatórios') }} /</span> {{ __('Performance da Frota') }}</h4>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">{{ __('Filtros de Relatório') }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('fleet_report.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">{{ __('Data Inicial') }}</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">{{ __('Data Final') }}</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="vehicle_id" class="form-label">{{ __('Veículo') }}</label>
                    <select class="form-select" id="vehicle_id" name="vehicle_id">
                        <option value="">{{ __('Todos') }}</option>
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ request('vehicle_id') == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->placa }} - {{ $vehicle->modelo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="fuel_type" class="form-label">{{ __('Tipo de Combustível') }}</label>
                    <select class="form-select" id="fuel_type" name="fuel_type">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="Diesel" {{ request('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="Etanol" {{ request('fuel_type') == 'Etanol' ? 'selected' : '' }}>Etanol</option>
                        <option value="Gasolina" {{ request('fuel_type') == 'Gasolina' ? 'selected' : '' }}>Gasolina</option>
                        <option value="Híbrido" {{ request('fuel_type') == 'Híbrido' ? 'selected' : '' }}>Híbrido</option>
                    </select>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">{{ __('Gerar Relatório') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ __('Resultados do Relatório') }}</h5>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('Veículo') }}</th>
                    <th>{{ __('Consumo Médio (Km/L)') }}</th>
                    <th>{{ __('Custo Total de Abastecimento') }}</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse ($fleetReport as $report)
                    <tr>
                        <td>{{ $report['vehicle']->placa }} - {{ $report['vehicle']->modelo }}</td>
                        <td>{{ number_format($report['average_consumption'], 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($report['total_cost'], 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">{{ __('Nenhum dado encontrado para os filtros selecionados.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection