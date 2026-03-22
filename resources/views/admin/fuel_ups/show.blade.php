@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes do Abastecimento'))

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">{{ __('Abastecimentos') }} /</span> {{ __('Detalhes') }}</h4>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Detalhes do Abastecimento') }}</h5>
        <a href="{{ route('vehicles.show', $fuelUp->vehicle->id) }}" class="btn btn-primary">{{ __('Voltar para Detalhes do Veículo') }}</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>{{ __('Veículo') }}:</strong> {{ $fuelUp->vehicle->placa }} - {{ $fuelUp->vehicle->modelo }}</p>
                <p><strong>{{ __('Data/Hora') }}:</strong> {{ \Carbon\Carbon::parse($fuelUp->fuel_up_date)->format('d/m/Y H:i') }}</p>
                <p><strong>{{ __('Combustível') }}:</strong> {{ $fuelUp->fuel_type }}</p>
                <p><strong>{{ __('Quantidade') }}:</strong> {{ number_format($fuelUp->quantity, 2, ',', '.') }} L</p>
                <p><strong>{{ __('Valor Total') }}:</strong> R$ {{ number_format($fuelUp->total_value, 2, ',', '.') }}</p>
                <p><strong>{{ __('Valor Unitário') }}:</strong> R$ {{ number_format($fuelUp->unit_value, 2, ',', '.') }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>{{ __('KM Atual') }}:</strong> {{ number_format($fuelUp->current_km, 0, ',', '.') }} KM</p>
                <p><strong>{{ __('KM Anterior') }}:</strong> {{ number_format($fuelUp->previous_km, 0, ',', '.') ?? 'N/A' }} KM</p>
                <p><strong>{{ __('Distância Percorrida') }}:</strong> {{ number_format($fuelUp->distance_traveled, 2, ',', '.') ?? 'N/A' }} KM</p>
                <p><strong>{{ __('Consumo (Km/L)') }}:</strong> {{ number_format($fuelUp->consumption_km_l, 2, ',', '.') ?? 'N/A' }} Km/L</p>
                <p><strong>{{ __('Custo por KM') }}:</strong> R$ {{ number_format($fuelUp->cost_per_km, 2, ',', '.') ?? 'N/A' }}</p>
                <p><strong>{{ __('Tipo de Abastecimento') }}:</strong> {{ $fuelUp->fuel_up_type }}</p>
                @if($fuelUp->station_name)
                    <p><strong>{{ __('Nome do Posto') }}:</strong> {{ $fuelUp->station_name }}</p>
                @endif
                <p><strong>{{ __('Forma de Pagamento') }}:</strong> {{ $fuelUp->payment_method }}</p>
            </div>
        </div>
        @if($fuelUp->observations)
            <div class="row mt-3">
                <div class="col-12">
                    <p><strong>{{ __('Observações') }}:</strong> {{ $fuelUp->observations }}</p>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('fuel_ups.edit', $fuelUp->id) }}" class="btn btn-warning">{{ __('Editar') }}</a>
    <form action="{{ route('fuel_ups.destroy', $fuelUp->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('Tem certeza que deseja excluir este abastecimento?') }}')">{{ __('Excluir') }}</button>
    </form>
</div>
@endsection