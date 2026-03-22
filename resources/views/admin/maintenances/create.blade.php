@extends('layouts/contentNavbarLayout')

@section('title', __('Registrar Manutenção'))

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('Manutenções / ') }}</span> {{ __('Registrar Manutenção') }}</h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('Registrar Nova Manutenção') }}</h5>
            <a href="{{ route('vehicles.index') }}" class="btn btn-primary">{{ __('Voltar para Veículos') }}</a>
        </div>
        <div class="card-body">
            <form action="{{ route('maintenances.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
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
                    <div class="col-md-6">
                        <label for="type" class="form-label">{{ __('Tipo de Manutenção') }}</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">{{ __('Selecione o tipo') }}</option>
                            <option value="Preventiva" {{ (old('type') == 'Preventiva') ? 'selected' : '' }}>{{ __('Preventiva') }}</option>
                            <option value="Corretiva" {{ (old('type') == 'Corretiva') ? 'selected' : '' }}>{{ __('Corretiva') }}</option>
                            <option value="Revisão" {{ (old('type') == 'Revisão') ? 'selected' : '' }}>{{ __('Revisão') }}</option>
                            <option value="Troca de Óleo" {{ (old('type') == 'Troca de Óleo') ? 'selected' : '' }}>{{ __('Troca de Óleo') }}</option>
                            <option value="Pneus" {{ (old('type') == 'Pneus') ? 'selected' : '' }}>{{ __('Pneus') }}</option>
                            <option value="Elétrica" {{ (old('type') == 'Elétrica') ? 'selected' : '' }}>{{ __('Elétrica') }}</option>
                            <option value="Funilaria" {{ (old('type') == 'Funilaria') ? 'selected' : '' }}>{{ __('Funilaria') }}</option>
                            <option value="Outros" {{ (old('type') == 'Outros') ? 'selected' : '' }}>{{ __('Outros') }}</option>
                        </select>
                        @error('type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="maintenance_date" class="form-label">{{ __('Data da Manutenção') }}</label>
                        <input type="date" class="form-control" id="maintenance_date" name="maintenance_date" value="{{ old('maintenance_date') }}" required>
                        @error('maintenance_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="mileage" class="form-label">{{ __('Quilometragem (KM)') }}</label>
                        <input type="number" class="form-control" id="mileage" name="mileage" value="{{ old('mileage') }}" required min="0">
                        @error('mileage')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="cost" class="form-label">{{ __('Custo Total (R$)') }}</label>
                        <input type="number" step="0.01" class="form-control" id="cost" name="cost" value="{{ old('cost') }}" required min="0">
                        @error('cost')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="supplier" class="form-label">{{ __('Fornecedor/Oficina') }}</label>
                        <input type="text" class="form-control" id="supplier" name="supplier" value="{{ old('supplier') }}">
                        @error('supplier')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="description" class="form-label">{{ __('Descrição do Serviço') }}</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="status" class="form-label">{{ __('Status da Manutenção') }}</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Agendada" {{ (old('status') == 'Agendada') ? 'selected' : '' }}>{{ __('Agendada') }}</option>
                            <option value="Em execução" {{ (old('status') == 'Em execução') ? 'selected' : '' }}>{{ __('Em execução') }}</option>
                            <option value="Concluída" {{ (old('status') == 'Concluída') ? 'selected' : '' }}>{{ __('Concluída') }}</option>
                            <option value="Cancelada" {{ (old('status') == 'Cancelada') ? 'selected' : '' }}>{{ __('Cancelada') }}</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="service_proof" class="form-label">{{ __('Comprovante de Serviço (PDF, Imagem)') }}</label>
                        <input type="file" class="form-control" id="service_proof" name="service_proof">
                        @error('service_proof')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="observations" class="form-label">{{ __('Observações Adicionais') }}</label>
                        <textarea class="form-control" id="observations" name="observations" rows="3">{{ old('observations') }}</textarea>
                        @error('observations')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">{{ __('Registrar Manutenção') }}</button>
                    <a href="{{ route('vehicles.show', old('vehicle_id', $selectedVehicleId)) }}" class="btn btn-outline-secondary">{{ __('Cancelar') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    $(document).ready(function() {
        // Inicializar Select2 para o campo de veículo, se necessário
        $('#vehicle_id').select2({
            placeholder: "{{ __('Selecione um veículo') }}",
            allowClear: true
        });
    });
</script>
@endsection