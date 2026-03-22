@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes do Uso de Veículo'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Detalhes do Uso de Veículo') }}</h5>
            </div>
            <div class="card-body">
                <p class="card-text">{{ __('Veículo') }}: {{ $vehicleUsage->vehicle->brand }} {{ $vehicleUsage->vehicle->model }} ({{ $vehicleUsage->vehicle->plate }})</p>
                <p class="card-text">{{ __('Motorista') }}: {{ $vehicleUsage->driver->full_name }} ({{ $vehicleUsage->driver->cpf }})</p>
                <p class="card-text">{{ __('Data de Saída') }}: {{ \Carbon\Carbon::parse($vehicleUsage->departure_date)->format('d/m/Y H:i') }}</p>
                <p class="card-text">{{ __('Quilometragem de Saída') }}: {{ $vehicleUsage->departure_mileage }} km</p>
                <p class="card-text">{{ __('Data de Retorno') }}: 
                    @if($vehicleUsage->return_date)
                        {{ \Carbon\Carbon::parse($vehicleUsage->return_date)->format('d/m/Y H:i') }}
                    @else
                        N/A
                    @endif
                </p>
                <p class="card-text">{{ __('Quilometragem de Retorno') }}: 
                    @if($vehicleUsage->return_mileage)
                        {{ $vehicleUsage->return_mileage }} km
                    @else
                        N/A
                    @endif
                </p>
                <p class="card-text">{{ __('Rota/Destino') }}: {{ $vehicleUsage->route_destination ?? 'N/A' }}</p>
                <p class="card-text">{{ __('Observações') }}: {{ $vehicleUsage->observations ?? 'N/A' }}</p>
                <p class="card-text">{{ __('Status da Viagem') }}:
                    @if ($vehicleUsage->trip_status == 'Em andamento')
                        <span class="badge bg-label-warning">{{ __('Em andamento') }}</span>
                    @else
                        <span class="badge bg-label-success">{{ __('Finalizada') }}</span>
                    @endif
                </p>
                <a href="{{ route('vehicle_usages.index') }}" class="btn btn-primary">{{ __('Voltar para Usos de Veículos') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection