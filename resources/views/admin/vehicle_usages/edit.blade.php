@extends('layouts/contentNavbarLayout')

@section('title', __('Editar Uso de Veículo'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Editar Uso de Veículo') }}</h4>
        <p class="text-muted mb-0">{{ $vehicleUsage->vehicle->modelo ?? '-' }} ({{ $vehicleUsage->vehicle->placa ?? '-' }})</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('vehicle_usages.show', $vehicleUsage->id) }}" class="btn btn-outline-secondary">
            <i class="bx bx-show me-1"></i> {{ __('Ver Detalhes') }}
        </a>
        <a href="{{ route('vehicle_usages.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('vehicle_usages.update', $vehicleUsage->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Veículo e Motorista --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-user-pin me-2 text-danger"></i>{{ __('Veículo e Motorista') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="vehicle_id" class="form-label">{{ __('Veículo') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="vehicle_id" name="vehicle_id" required disabled>
                                <option value="{{ $vehicleUsage->vehicle->id }}" selected>{{ $vehicleUsage->vehicle->modelo ?? '-' }} ({{ $vehicleUsage->vehicle->placa ?? '-' }})</option>
                            </select>
                            <input type="hidden" name="vehicle_id" value="{{ $vehicleUsage->vehicle->id }}">
                            @error('vehicle_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="driver_id" class="form-label">{{ __('Motorista') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="driver_id" name="driver_id" required>
                                <option value="">{{ __('Selecione o Motorista') }}</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ (old('driver_id', $vehicleUsage->driver_id) == $driver->id) ? 'selected' : '' }}>{{ $driver->full_name }} ({{ $driver->cpf }})</option>
                                @endforeach
                            </select>
                            @error('driver_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    {{-- Dados de Saída --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-log-out me-2 text-danger"></i>{{ __('Dados de Saída') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="departure_date" class="form-label">{{ __('Data e Hora de Saída') }} <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="departure_date" name="departure_date" value="{{ old('departure_date', \Carbon\Carbon::parse($vehicleUsage->departure_date)->format('Y-m-d\\TH:i')) }}" required>
                            @error('departure_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="departure_mileage" class="form-label">{{ __('Quilometragem de Saída') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="departure_mileage" name="departure_mileage" value="{{ old('departure_mileage', $vehicleUsage->departure_mileage) }}" required min="0">
                                <span class="input-group-text">KM</span>
                            </div>
                            @error('departure_mileage')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    {{-- Dados de Retorno --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-log-in me-2 text-danger"></i>{{ __('Dados de Retorno') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="return_date" class="form-label">{{ __('Data e Hora de Retorno') }}</label>
                            <input type="datetime-local" class="form-control" id="return_date" name="return_date" value="{{ old('return_date', $vehicleUsage->return_date ? \Carbon\Carbon::parse($vehicleUsage->return_date)->format('Y-m-d\\TH:i') : '') }}">
                            @error('return_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="return_mileage" class="form-label">{{ __('Quilometragem de Retorno') }}</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="return_mileage" name="return_mileage" value="{{ old('return_mileage', $vehicleUsage->return_mileage) }}" min="0">
                                <span class="input-group-text">KM</span>
                            </div>
                            @error('return_mileage')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    {{-- Informações Adicionais --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-info-circle me-2 text-danger"></i>{{ __('Informações Adicionais') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="route_destination" class="form-label">{{ __('Rota/Destino') }}</label>
                            <input type="text" class="form-control" id="route_destination" name="route_destination" value="{{ old('route_destination', $vehicleUsage->route_destination) }}">
                            @error('route_destination')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="trip_status" class="form-label">{{ __('Status da Viagem') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="trip_status" name="trip_status" required>
                                <option value="Em andamento" {{ (old('trip_status', $vehicleUsage->trip_status) == 'Em andamento') ? 'selected' : '' }}>{{ __('Em andamento') }}</option>
                                <option value="Finalizada" {{ (old('trip_status', $vehicleUsage->trip_status) == 'Finalizada') ? 'selected' : '' }}>{{ __('Finalizada') }}</option>
                            </select>
                            @error('trip_status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="observations" class="form-label">{{ __('Observações') }}</label>
                            <textarea class="form-control" id="observations" name="observations" rows="3">{{ old('observations', $vehicleUsage->observations) }}</textarea>
                            @error('observations')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('vehicle_usages.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-x me-1"></i> {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-check me-1"></i> {{ __('Atualizar') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection