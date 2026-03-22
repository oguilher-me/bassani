@extends('layouts/contentNavbarLayout')

@section('title', __('Cadastro de Fornecedores'))
 
@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl"> 
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Cadastro de Fornecedores') }}</h5>
            </div> 
            <div class="card-body">
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="company_name" class="form-label">{{ __('Razão Social / Nome Fantasia') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                            @error('company_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="document_number" class="form-label">{{ __('CNPJ / CPF') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="document_number" name="document_number" value="{{ old('document_number') }}" required>
                            @error('document_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="supplier_type" class="form-label">{{ __('Tipo de Fornecedor') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="supplier_type" name="supplier_type" required>
                                <option value="">{{ __('Selecione o Tipo') }}</option>
                                <option value="Pessoa Física" {{ old('supplier_type') == 'Pessoa Física' ? 'selected' : '' }}>{{ __('Pessoa Física') }}</option>
                                <option value="Pessoa Jurídica" {{ old('supplier_type') == 'Pessoa Jurídica' ? 'selected' : '' }}>{{ __('Pessoa Jurídica') }}</option>
                            </select>
                            @error('supplier_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="contact_person" class="form-label">{{ __('Pessoa de Contato') }}</label>
                            <input type="text" class="form-control" id="contact_person" name="contact_person" value="{{ old('contact_person') }}">
                            @error('contact_person')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="phone" class="form-label">{{ __('Telefone') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control phone-mask" id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">{{ __('E-mail') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="address" class="form-label">{{ __('Endereço Completo') }}</label>
                            <textarea class="form-control" id="address" name="address">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="services_offered" class="form-label">{{ __('Serviços Oferecidos') }}</label>
                            <textarea class="form-control" id="services_offered" name="services_offered">{{ old('services_offered') }}</textarea>
                            @error('services_offered')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="status" class="form-label">{{ __('Situação do Cadastro') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="status" name="status" required>
                                <option value="Ativo" {{ old('status') == 'Ativo' ? 'selected' : '' }}>{{ __('Ativo') }}</option>
                                <option value="Inativo" {{ old('status') == 'Inativo' ? 'selected' : '' }}>{{ __('Inativo') }}</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">{{ __('Salvar') }}</button>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection