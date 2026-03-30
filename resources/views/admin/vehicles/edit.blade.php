@extends('layouts/contentNavbarLayout')

@section('title', __('Editar Veículo'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Editar Veículo') }}</h4>
        <p class="text-muted mb-0">{{ $vehicle->placa }} - {{ $vehicle->modelo }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('vehicles.show', $vehicle->id) }}" class="btn btn-outline-secondary">
            <i class="bx bx-show me-1"></i> {{ __('Ver Detalhes') }}
        </a>
        <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Identificação do Veículo --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-car me-2 text-danger"></i>{{ __('Identificação do Veículo') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="placa" class="form-label">{{ __('Placa') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="placa" name="placa" value="{{ old('placa', $vehicle->placa) }}" required style="text-transform: uppercase;">
                            @error('placa')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-5">
                            <label for="modelo" class="form-label">{{ __('Modelo') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modelo" name="modelo" value="{{ old('modelo', $vehicle->modelo) }}" required>
                            @error('modelo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="car_brand_id" class="form-label">{{ __('Marca') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="car_brand_id" name="car_brand_id" required>
                                <option value="">{{ __('Selecione a Marca') }}</option>
                                @foreach ($carBrands as $carBrand)
                                    <option value="{{ $carBrand->id }}" {{ old('car_brand_id', $vehicle->car_brand_id) == $carBrand->id ? 'selected' : '' }}>{{ $carBrand->name }}</option>
                                @endforeach
                            </select>
                            @error('car_brand_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    {{-- Especificações Técnicas --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-slider-alt me-2 text-danger"></i>{{ __('Especificações Técnicas') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="ano_fabricacao" class="form-label">{{ __('Ano Fabricação') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="ano_fabricacao" name="ano_fabricacao" value="{{ old('ano_fabricacao', $vehicle->ano_fabricacao) }}" required min="1900" max="{{ date('Y') + 1 }}">
                            @error('ano_fabricacao')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="quilometragem_atual" class="form-label">{{ __('Quilometragem Atual') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="quilometragem_atual" name="quilometragem_atual" value="{{ old('quilometragem_atual', $vehicle->quilometragem_atual) }}" required min="0">
                                <span class="input-group-text">KM</span>
                            </div>
                            @error('quilometragem_atual')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="cubic_capacity" class="form-label">{{ __('Capacidade Cúbica') }}</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control" id="cubic_capacity" name="cubic_capacity" value="{{ old('cubic_capacity', $vehicle->cubic_capacity) }}" placeholder="0.00">
                                <span class="input-group-text">m³</span>
                            </div>
                            @error('cubic_capacity')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Ativo" {{ old('status', $vehicle->status) == 'Ativo' ? 'selected' : '' }}>{{ __('Ativo') }}</option>
                                <option value="Em manutenção" {{ old('status', $vehicle->status) == 'Em manutenção' ? 'selected' : '' }}>{{ __('Em manutenção') }}</option>
                                <option value="Inativo" {{ old('status', $vehicle->status) == 'Inativo' ? 'selected' : '' }}>{{ __('Inativo') }}</option>
                            </select>
                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    {{-- Informações Adicionais --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-calendar me-2 text-danger"></i>{{ __('Informações Adicionais') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="data_aquisicao" class="form-label">{{ __('Data de Aquisição') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="data_aquisicao" name="data_aquisicao" value="{{ old('data_aquisicao', $vehicle->data_aquisicao) }}" required>
                            @error('data_aquisicao')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <label for="observacoes" class="form-label">{{ __('Observações') }}</label>
                            <textarea class="form-control" id="observacoes" name="observacoes" rows="2">{{ old('observacoes', $vehicle->observacoes) }}</textarea>
                            @error('observacoes')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary">
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