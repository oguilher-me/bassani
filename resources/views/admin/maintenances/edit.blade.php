@extends('layouts/contentNavbarLayout')

@section('title', __('Editar Manutenção'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Editar Manutenção') }}</h5>
                <a href="{{ route('maintenances.index') }}" class="btn btn-primary">{{ __('Voltar para Manutenções') }}</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('maintenances.update', $maintenance->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="vehicle_id" class="form-label">{{ __('Veículo') }}</label>
                            <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ $maintenance->vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->placa }} - {{ $vehicle->modelo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vehicle_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="type" class="form-label">{{ __('Tipo de Manutenção') }}</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="Corretiva" {{ $maintenance->type == 'Corretiva' ? 'selected' : '' }}>{{ __('Corretiva') }}</option>
                                <option value="Preventiva" {{ $maintenance->type == 'Preventiva' ? 'selected' : '' }}>{{ __('Preventiva') }}</option>
                            </select>
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="maintenance_date" class="form-label">{{ __('Data da Manutenção') }}</label>
                            <input class="form-control" type="date" id="maintenance_date" name="maintenance_date" value="{{ old('maintenance_date', \Carbon\Carbon::parse($maintenance->maintenance_date)->format('Y-m-d')) }}" required>
                            @error('maintenance_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="mileage" class="form-label">{{ __('Quilometragem') }}</label>
                            <input class="form-control" type="number" id="mileage" name="mileage" value="{{ old('mileage', $maintenance->mileage) }}" required>
                            @error('mileage')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="cost" class="form-label">{{ __('Custo') }}</label>
                            <input class="form-control" type="number" step="0.01" id="cost" name="cost" value="{{ old('cost', $maintenance->cost) }}" required>
                            @error('cost')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="supplier" class="form-label">{{ __('Fornecedor') }}</label>
                            <input class="form-control" type="text" id="supplier" name="supplier" value="{{ old('supplier', $maintenance->supplier) }}">
                            @error('supplier')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="status" class="form-label">{{ __('Status') }}</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Agendada" {{ $maintenance->status == 'Agendada' ? 'selected' : '' }}>{{ __('Agendada') }}</option>
                                <option value="Em execução" {{ $maintenance->status == 'Em execução' ? 'selected' : '' }}>{{ __('Em execução') }}</option>
                                <option value="Concluída" {{ $maintenance->status == 'Concluída' ? 'selected' : '' }}>{{ __('Concluída') }}</option>
                                <option value="Cancelada" {{ $maintenance->status == 'Cancelada' ? 'selected' : '' }}>{{ __('Cancelada') }}</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="service_proof" class="form-label">{{ __('Comprovante de Serviço') }}</label>
                            <input class="form-control" type="file" id="service_proof" name="service_proof">
                            @if ($maintenance->service_proof)
                                <div class="mt-2">
                                    <a href="{{ Storage::url($maintenance->service_proof) }}" target="_blank">{{ __('Visualizar Comprovante Atual') }}</a>
                                </div>
                            @endif
                            @error('service_proof')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="description" class="form-label">{{ __('Descrição') }}</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $maintenance->description) }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="observations" class="form-label">{{ __('Observações') }}</label>
                            <textarea class="form-control" id="observations" name="observations" rows="3">{{ old('observations', $maintenance->observations) }}</textarea>
                            @error('observations')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">{{ __('Salvar Alterações') }}</button>
                        <a href="{{ route('maintenances.index') }}" class="btn btn-outline-secondary">{{ __('Cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection