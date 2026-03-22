@extends('layouts/contentNavbarLayout')

@section('title', __('Adicionar Novo Veículo'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl"> 
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Adicionar Novo Veículo') }}</h5>
            </div> 
            <div class="card-body">
                <form action="{{ route('vehicles.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="mb-3 col-md-2">
                            <label for="placa" class="form-label">{{ __('Placa') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="placa" name="placa" value="{{ old('placa') }}" required>
                            @error('placa')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="modelo" class="form-label">{{ __('Modelo') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modelo" name="modelo" value="{{ old('modelo') }}" required>
                            @error('modelo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="car_brand_id" class="form-label">{{ __('Marca') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="car_brand_id" name="car_brand_id" required>
                                <option value="">{{ __('Selecione a Marca') }}</option>
                                @foreach ($carBrands as $carBrand)
                                    <option value="{{ $carBrand->id }}" {{ old('car_brand_id') == $carBrand->id ? 'selected' : '' }}>{{ $carBrand->name }}</option>
                                @endforeach
                            </select>
                            @error('car_brand_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="ano_fabricacao" class="form-label">{{ __('Ano de Fabricação') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="ano_fabricacao" name="ano_fabricacao" value="{{ old('ano_fabricacao') }}" required>
                            @error('ano_fabricacao')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-2">
                             <label for="quilometragem_atual" class="form-label">{{ __('Quilometragem Atual') }} <span class="text-danger">*</span></label>
                             <input type="number" class="form-control" id="quilometragem_atual" name="quilometragem_atual" value="{{ old('quilometragem_atual') }}" required>
                            @error('quilometragem_atual')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="cubic_capacity" class="form-label">
                                <i class="bx bx-cube me-1 text-muted"></i>{{ __('Capacidade Cúbica (m³)') }}
                            </label>
                            <input type="number" step="0.01" min="0" class="form-control"
                                   id="cubic_capacity" name="cubic_capacity"
                                   value="{{ old('cubic_capacity') }}"
                                   placeholder="Ex: 45.50">
                            @error('cubic_capacity')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Ativo" {{ old('status') == 'Ativo' ? 'selected' : '' }}>Ativo</option>
                                <option value="Em manutenção" {{ old('status') == 'Em manutenção' ? 'selected' : '' }}>Em manutenção</option>
                                <option value="Inativo" {{ old('status') == 'Inativo' ? 'selected' : '' }}>Inativo</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="data_aquisicao" class="form-label">{{ __('Data de Aquisição') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="data_aquisicao" name="data_aquisicao" value="{{ old('data_aquisicao') }}" required>
                            @error('data_aquisicao')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="observacoes" class="form-label">{{ __('Observações') }}</label>
                            <textarea class="form-control" id="observacoes" name="observacoes" rows="3">{{ old('observacoes') }}</textarea>
                            @error('observacoes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">{{ __('Salvar') }}</button>
                        <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
