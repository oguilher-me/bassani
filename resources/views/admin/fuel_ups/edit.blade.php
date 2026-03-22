@extends('layouts/contentNavbarLayout')

@section('title', __('Editar Abastecimento'))

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">{{ __('Abastecimentos') }} /</span> {{ __('Editar Abastecimento') }}</h4>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Editar Abastecimento para o Veículo') }}: {{ $fuelUp->vehicle->placa }} - {{ $fuelUp->vehicle->modelo }}</h5>
        <a href="{{ route('vehicles.show', $fuelUp->vehicle->id) }}" class="btn btn-primary">{{ __('Voltar para Detalhes do Veículo') }}</a>
    </div>
    <div class="card-body">
        <form action="{{ route('fuel_ups.update', $fuelUp->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="vehicle_id" value="{{ $fuelUp->vehicle->id }}">

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="fuel_up_date" class="form-label">{{ __('Data/Hora do Abastecimento') }}</label>
                    <input type="datetime-local" class="form-control" id="fuel_up_date" name="fuel_up_date" value="{{ old('fuel_up_date', \Carbon\Carbon::parse($fuelUp->fuel_up_date)->format('Y-m-d\TH:i')) }}" required>
                    @error('fuel_up_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="fuel_type" class="form-label">{{ __('Combustível') }}</label>
                    <select class="form-select" id="fuel_type" name="fuel_type" required>
                        <option value="">{{ __('Selecione o tipo de combustível') }}</option>
                        <option value="Diesel" {{ old('fuel_type', $fuelUp->fuel_type) == 'Diesel' ? 'selected' : '' }}>{{ __('Diesel') }}</option>
                        <option value="Etanol" {{ old('fuel_type', $fuelUp->fuel_type) == 'Etanol' ? 'selected' : '' }}>{{ __('Etanol') }}</option>
                        <option value="Gasolina" {{ old('fuel_type', $fuelUp->fuel_type) == 'Gasolina' ? 'selected' : '' }}>{{ __('Gasolina') }}</option>
                        <option value="Híbrido" {{ old('fuel_type', $fuelUp->fuel_type) == 'Híbrido' ? 'selected' : '' }}>{{ __('Híbrido') }}</option>
                        <option value="GNV" {{ old('fuel_type', $fuelUp->fuel_type) == 'GNV' ? 'selected' : '' }}>{{ __('GNV') }}</option>
                    </select>
                    @error('fuel_type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="quantity" class="form-label">{{ __('Quantidade (Litros)') }}</label>
                    <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" value="{{ old('quantity', $fuelUp->quantity) }}" required>
                    @error('quantity')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="total_value" class="form-label">{{ __('Valor Total (R$)') }}</label>
                    <input type="number" step="0.01" class="form-control" id="total_value" name="total_value" value="{{ old('total_value', $fuelUp->total_value) }}" required>
                    @error('total_value')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="unit_value" class="form-label">{{ __('Valor Unitário (R$/L)') }}</label>
                    <input type="number" step="0.01" class="form-control" id="unit_value" name="unit_value" value="{{ old('unit_value', $fuelUp->unit_value) }}" readonly>
                    @error('unit_value')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="current_km" class="form-label">{{ __('Quilometragem (KM) Atual do Veículo') }}</label>
                    <input type="number" class="form-control" id="current_km" name="current_km" value="{{ old('current_km', $fuelUp->current_km) }}" required>
                    @error('current_km')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6"> 
                    <label for="fuel_up_type" class="form-label">{{ __('Tipo de Abastecimento') }}</label>
                    <select class="form-select" id="fuel_up_type" name="fuel_up_type" required>
                        <option value="">{{ __('Selecione o tipo de abastecimento') }}</option>
                        <option value="Proprio" {{ old('fuel_up_type', $fuelUp->fuel_up_type) == 'Proprio' ? 'selected' : '' }}>{{ __('Próprio (Bomba Interna)') }}</option>
                        <option value="Terceirizado" {{ old('fuel_up_type', $fuelUp->fuel_up_type) == 'Terceirizado' ? 'selected' : '' }}>{{ __('Posto Terceirizado') }}</option>
                    </select>
                    @error('fuel_up_type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 hide" id="station_name_field" style="">
                    <label for="station_name" class="form-label">{{ __('Local/Nome do Posto') }}</label>
                    <input type="text" class="form-control" id="station_name" name="station_name" value="{{ old('station_name', $fuelUp->station_name) }}">
                    @error('station_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="payment_method" class="form-label">{{ __('Forma de Pagamento') }}</label>
                    <select class="form-select" id="payment_method" name="payment_method" required>
                        <option value="">{{ __('Selecione a forma de pagamento') }}</option>
                        <option value="Cartao Frota" {{ old('payment_method', $fuelUp->payment_method) == 'Cartao Frota' ? 'selected' : '' }}>{{ __('Cartão Frota') }}</option>
                        <option value="Dinheiro" {{ old('payment_method', $fuelUp->payment_method) == 'Dinheiro' ? 'selected' : '' }}>{{ __('Dinheiro') }}</option>
                        <option value="Convenio" {{ old('payment_method', $fuelUp->payment_method) == 'Convenio' ? 'selected' : '' }}>{{ __('Convênio') }}</option>
                        <option value="Outro" {{ old('payment_method', $fuelUp->payment_method) == 'Outro' ? 'selected' : '' }}>{{ __('Outro') }}</option>
                    </select>
                    @error('payment_method')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="observations" class="form-label">{{ __('Observações') }}</label>
                    <textarea class="form-control" id="observations" name="observations" rows="3">{{ old('observations', $fuelUp->observations) }}</textarea>
                    @error('observations')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">{{ __('Atualizar Abastecimento') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        const totalValueInput = document.getElementById('total_value');
        const unitValueInput = document.getElementById('unit_value');

        function calculateUnitValue() {
            const quantity = parseFloat(quantityInput.value);
            const totalValue = parseFloat(totalValueInput.value);

            if (quantity > 0 && totalValue >= 0) {
                unitValueInput.value = (totalValue / quantity).toFixed(2);
            } else {
                unitValueInput.value = '';
            }
        }

        quantityInput.addEventListener('input', calculateUnitValue);
        totalValueInput.addEventListener('input', calculateUnitValue);

        // Initial calculation if values are pre-filled (e.g., old values on validation error)
        calculateUnitValue();

    });
</script>
@endsection