@extends('layouts/contentNavbarLayout')

@section('title', __('Registrar Uso de Veículo'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Registrar Uso de Veículo') }}</h4>
        <p class="text-muted mb-0">{{ __('Registrar saída de veículo da frota') }}</p>
    </div>
    <a href="{{ route('vehicle_usages.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('vehicle_usages.store') }}" method="POST">
                    @csrf
                    
                    {{-- Veículo e Motorista --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-user-pin me-2 text-danger"></i>{{ __('Veículo e Motorista') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="vehicle_id" class="form-label">{{ __('Veículo') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="vehicle_id" name="vehicle_id" required {{ request()->query('vehicle_id') ? 'disabled' : '' }}>
                                <option value="">{{ __('Selecione o Veículo') }}</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ (old('vehicle_id') == $vehicle->id || request()->query('vehicle_id') == $vehicle->id) ? 'selected' : '' }}>{{ $vehicle->modelo }} ({{ $vehicle->placa }})</option>
                                @endforeach
                            </select>
                            @if(request()->query('vehicle_id'))
                                <input type="hidden" name="vehicle_id" value="{{ request()->query('vehicle_id') }}">
                            @endif
                            @error('vehicle_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="driver_id" class="form-label">{{ __('Motorista') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="driver_id" name="driver_id" required>
                                <option value="">{{ __('Selecione o Motorista') }}</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->full_name }} ({{ $driver->cpf }})</option>
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
                            <input type="datetime-local" class="form-control" id="departure_date" name="departure_date" value="{{ old('departure_date') }}" required>
                            @error('departure_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="departure_mileage" class="form-label">{{ __('Quilometragem de Saída') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="departure_mileage" name="departure_mileage" value="{{ old('departure_mileage') }}" required min="0">
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
                        <i class="bx bx-log-in me-2 text-danger"></i>{{ __('Dados de Retorno') }} <small class="text-muted fw-normal">({{ __('preencher ao finalizar') }})</small>
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="return_date" class="form-label">{{ __('Data e Hora de Retorno') }}</label>
                            <input type="datetime-local" class="form-control" id="return_date" name="return_date" value="{{ old('return_date') }}">
                            @error('return_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="return_mileage" class="form-label">{{ __('Quilometragem de Retorno') }}</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="return_mileage" name="return_mileage" value="{{ old('return_mileage') }}" min="0">
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
                            <input type="text" class="form-control" id="route_destination" name="route_destination" value="{{ old('route_destination') }}" placeholder="Ex: Entrega Centro, Manutenção...">
                            @error('route_destination')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="trip_status" class="form-label">{{ __('Status da Viagem') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="trip_status" name="trip_status" required>
                                <option value="Em andamento" {{ old('trip_status') == 'Em andamento' ? 'selected' : '' }}>{{ __('Em andamento') }}</option>
                                <option value="Finalizada" {{ old('trip_status') == 'Finalizada' ? 'selected' : '' }}>{{ __('Finalizada') }}</option>
                            </select>
                            @error('trip_status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="observations" class="form-label">{{ __('Observações') }}</label>
                            <textarea class="form-control" id="observations" name="observations" rows="3" placeholder="Informações adicionais sobre o uso do veículo...">{{ old('observations') }}</textarea>
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
                            <i class="bx bx-check me-1"></i> {{ __('Registrar Uso') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection