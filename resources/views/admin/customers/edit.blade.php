@extends('layouts/contentNavbarLayout')

@section('title', __('Editar Cliente'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Editar Cliente') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label for="customer_type" class="form-label">{{ __('Tipo de Cliente') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="customer_type" name="customer_type" required>
                                <option value="">{{ __('Selecione o Tipo') }}</option>
                                <option value="PF" {{ (old('customer_type', $customer->customer_type) == 'PF') ? 'selected' : '' }}>{{ __('Pessoa Física') }}</option>
                                <option value="PJ" {{ (old('customer_type', $customer->customer_type) == 'PJ') ? 'selected' : '' }}>{{ __('Pessoa Jurídica') }}</option>
                            </select>
                            @error('customer_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row" id="pf_fields" style="display: {{ (old('customer_type', $customer->customer_type) == 'PF') ? 'flex' : 'none' }};">
                        <div class="mb-3 col-md-6">
                            <label for="full_name" class="form-label">{{ __('Nome Completo') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $customer->full_name) }}">
                            @error('full_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="cpf" class="form-label">{{ __('CPF') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control cpf" id="cpf" name="cpf" value="{{ old('cpf', $customer->cpf) }}" maxlength="14">
                            @error('cpf')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="rg" class="form-label">{{ __('RG') }}</label>
                            <input type="text" class="form-control" id="rg" name="rg" value="{{ old('rg', $customer->rg) }}">
                            @error('rg')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row" id="pj_fields" style="display: {{ (old('customer_type', $customer->customer_type) == 'PJ') ? 'flex' : 'none' }};">
                        <div class="mb-3 col-md-6">
                            <label for="company_name" class="form-label">{{ __('Razão Social/Nome Fantasia') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $customer->company_name) }}">
                            @error('company_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="cnpj" class="form-label">{{ __('CNPJ') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control cnpj" id="cnpj" name="cnpj" value="{{ old('cnpj', $customer->cnpj) }}" maxlength="18">
                            @error('cnpj')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="ie" class="form-label">{{ __('Inscrição Estadual') }}</label>
                            <input type="text" class="form-control" id="ie" name="ie" value="{{ old('ie', $customer->ie) }}">
                            @error('ie')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                         <div class="mb-3 col-md-6">
                            <label for="representative_name" class="form-label">{{ __('Nome do Representante') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="representative_name" name="representative_name" value="{{ old('representative_name', $customer->representative_name) }}">
                            @error('representative_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $customer->email) }}" required>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="phone" class="form-label">{{ __('Telefone') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control phone-mask" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required>
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="address_street" class="form-label">{{ __('Endereço') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address_street" name="address_street" value="{{ old('address_street', $customer->address_street) }}" required>
                            @error('address_street')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="address_number" class="form-label">{{ __('Número') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address_number" name="address_number" value="{{ old('address_number', $customer->address_number) }}" required>
                            @error('address_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="address_neighborhood" class="form-label">{{ __('Bairro') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address_neighborhood" name="address_neighborhood" value="{{ old('address_neighborhood', $customer->address_neighborhood) }}" required>
                            @error('address_neighborhood')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="address_city" class="form-label">{{ __('Cidade') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address_city" name="address_city" value="{{ old('address_city', $customer->address_city) }}" required>
                            @error('address_city')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="address_state" class="form-label">{{ __('Estado') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address_state" name="address_state" value="{{ old('address_state', $customer->address_state) }}" maxlength="2" required>
                            @error('address_state')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="address_zip_code" class="form-label">{{ __('CEP') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address_zip_code" name="address_zip_code" value="{{ old('address_zip_code', $customer->address_zip_code) }}" required>
                            @error('address_zip_code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="status" name="status" required>
                                <option value="Ativo" {{ (old('status', $customer->status) == 'Ativo') ? 'selected' : '' }}>{{ __('Ativo') }}</option>
                                <option value="Inativo" {{ (old('status', $customer->status) == 'Inativo') ? 'selected' : '' }}>{{ __('Inativo') }}</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">{{ __('Atualizar') }}</button>
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')

@endsection