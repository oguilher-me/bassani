@extends('layouts/contentNavbarLayout')

@section('title', __('Relatório Detalhado do Veículo'))

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">{{ __('Relatórios') }} /</span> {{ __('Detalhado do Veículo') }}</h4>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">{{ __('Filtros de Relatório') }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('fleet_report.vehicle_detailed_report') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="vehicle_id" class="form-label">{{ __('Veículo') }}</label>
                    <select class="form-select select2" id="vehicle_id" name="vehicle_id" required>
                        <option value="">{{ __('Selecione um Veículo') }}</option>
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ request('vehicle_id') == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->placa }} - {{ $vehicle->modelo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">{{ __('Data Inicial') }}</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">{{ __('Data Final') }}</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-2 mt-9">
                    <button type="submit" class="btn btn-danger">{{ __('Gerar Relatório') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if ($selectedVehicle)
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">{{ __('Detalhes do Veículo') }}: {{ $selectedVehicle->placa }} - {{ $selectedVehicle->modelo }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>{{ __('Marca') }}:</strong> {{ $selectedVehicle->carBrand->name }}</p>
                <p><strong>{{ __('Ano') }}:</strong> {{ $selectedVehicle->ano_fabricacao }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>{{ __('KM Atual') }}:</strong> {{ number_format($selectedVehicle->quilometragem_atual, 0, ',', '.') }}</p>
                <p><strong>{{ __('Status') }}:</strong> {{ $selectedVehicle->status }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ __('Histórico de Abastecimentos') }}</h5>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('Data') }}</th>
                    <th class="text-center">{{ __('Combustível') }}</th>
                    <th class="text-center">{{ __('Litros') }}</th>
                    <th class="text-center">{{ __('R$') }}</th>
                    <th class="text-center">{{ __('KM Atual') }}</th>
                    <th class="text-center">{{ __('KM Anterior') }}</th>
                    <th class="text-center">{{ __('Dist. Percorrida') }}</th>
                    <th class="text-center">{{ __('(Km/L)') }}</th>
                    <th class="text-center">{{ __('R$/KM') }}</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse ($fuelUps as $fuelUp)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($fuelUp->fuel_up_date)->format('d/m/Y H:i') }}</td>
                        <td class="text-center">{{ $fuelUp->fuel_type }}</td>
                        <td class="text-center">{{ number_format($fuelUp->quantity, 0, ',', '.') }}</td>
                        <td class="text-center">R$ {{ number_format($fuelUp->total_value, 2, ',', '.') }}</td>
                        <td class="text-center">{{ number_format($fuelUp->current_km, 0, ',', '.') }}</td>
                        <td class="text-center">{{ number_format($fuelUp->previous_km, 0, ',', '.') ?? 'N/A' }}</td>
                        <td class="text-center">{{ number_format($fuelUp->distance_traveled, 0, ',', '.') ?? 'N/A' }}</td>
                        <td class="text-center">{{ number_format($fuelUp->consumption_km_l, 2, ',', '.') ?? 'N/A' }}</td>
                        <td class="text-center">R$ {{ number_format($fuelUp->cost_per_km, 2, ',', '.') ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">{{ __('Nenhum abastecimento encontrado para este veículo e período.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection