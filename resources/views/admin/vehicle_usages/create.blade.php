@extends('layouts/contentNavbarLayout')

@section('title', __('Registrar Uso de Veículo'))
 
@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl"> 
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Registrar Uso de Veículo') }}</h5>
            </div> 
            <div class="card-body">
                <form action="{{ route('vehicle_usages.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="vehicle_id" class="form-label">{{ __('Veículo') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="vehicle_id" name="vehicle_id" required {{ request()->query('vehicle_id') ? 'disabled' : '' }}>
                                <option value="">{{ __('Selecione o Veículo') }}</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ (old('vehicle_id') == $vehicle->id || request()->query('vehicle_id') == $vehicle->id) ? 'selected' : '' }}>{{ $vehicle->modelo }} ({{ $vehicle->placa }})</option>
                                @endforeach
                            </select>
                            @if(request()->query('vehicle_id'))
                                <input type="hidden" name="vehicle_id" value="{{ request()->query('vehicle_id') }}">
                            @endif
                            @error('vehicle_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="driver_id" class="form-label">{{ __('Motorista') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="driver_id" name="driver_id" required>
                                <option value="">{{ __('Selecione o Motorista') }}</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->full_name }} ({{ $driver->cpf }})</option>
                                @endforeach
                            </select>
                            @error('driver_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="departure_date" class="form-label">{{ __('Data de Saída') }} <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="departure_date" name="departure_date" value="{{ old('departure_date') }}" required>
                            @error('departure_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="departure_mileage" class="form-label">{{ __('Quilometragem de Saída') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="departure_mileage" name="departure_mileage" value="{{ old('departure_mileage') }}" required>
                            @error('departure_mileage')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="return_date" class="form-label">{{ __('Data de Retorno') }}</label>
                            <input type="datetime-local" class="form-control" id="return_date" name="return_date" value="{{ old('return_date') }}">
                            @error('return_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="return_mileage" class="form-label">{{ __('Quilometragem de Retorno') }}</label>
                            <input type="number" class="form-control" id="return_mileage" name="return_mileage" value="{{ old('return_mileage') }}">
                            @error('return_mileage')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="route_destination" class="form-label">{{ __('Rota/Destino') }}</label>
                            <input type="text" class="form-control" id="route_destination" name="route_destination" value="{{ old('route_destination') }}">
                            @error('route_destination')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="observations" class="form-label">{{ __('Observações') }}</label>
                            <textarea class="form-control" id="observations" name="observations" rows="3">{{ old('observations') }}</textarea>
                            @error('observations')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="trip_status" class="form-label">{{ __('Status da Viagem') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="trip_status" name="trip_status" required>
                                <option value="Em andamento" {{ old('trip_status') == 'Em andamento' ? 'selected' : '' }}>{{ __('Em andamento') }}</option>
                                <option value="Finalizada" {{ old('trip_status') == 'Finalizada' ? 'selected' : '' }}>{{ __('Finalizada') }}</option>
                            </select>
                            @error('trip_status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">{{ __('Salvar') }}</button>
                        <a href="{{ route('vehicle_usages.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection