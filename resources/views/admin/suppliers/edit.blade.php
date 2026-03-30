@extends('layouts/contentNavbarLayout')

@section('title', __('Editar Fornecedor'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Editar Fornecedor') }}</h4>
        <p class="text-muted mb-0">{{ $supplier->company_name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-outline-secondary">
            <i class="bx bx-show me-1"></i> {{ __('Ver Detalhes') }}
        </a>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Dados do Fornecedor --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-building me-2 text-danger"></i>{{ __('Dados do Fornecedor') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label">{{ __('Razão Social / Nome Fantasia') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $supplier->company_name) }}" required>
                            @error('company_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="document_number" class="form-label">{{ __('CNPJ / CPF') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="document_number" name="document_number" value="{{ old('document_number', $supplier->document_number) }}" required>
                            @error('document_number')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="supplier_type" class="form-label">{{ __('Tipo') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="supplier_type" name="supplier_type" required>
                                <option value="">{{ __('Selecione') }}</option>
                                <option value="Pessoa Física" {{ (old('supplier_type', $supplier->supplier_type) == 'Pessoa Física') ? 'selected' : '' }}>{{ __('Pessoa Física') }}</option>
                                <option value="Pessoa Jurídica" {{ (old('supplier_type', $supplier->supplier_type) == 'Pessoa Jurídica') ? 'selected' : '' }}>{{ __('Pessoa Jurídica') }}</option>
                            </select>
                            @error('supplier_type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    {{-- Contato --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-phone me-2 text-danger"></i>{{ __('Informações de Contato') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="contact_person" class="form-label">{{ __('Pessoa de Contato') }}</label>
                            <input type="text" class="form-control" id="contact_person" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}">
                            @error('contact_person')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="phone" class="form-label">{{ __('Telefone') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control phone-mask" id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}" required>
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="email" class="form-label">{{ __('E-mail') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $supplier->email) }}" required>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    {{-- Endereço --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-map me-2 text-danger"></i>{{ __('Endereço') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label for="address" class="form-label">{{ __('Endereço Completo') }}</label>
                            <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $supplier->address) }}</textarea>
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    {{-- Serviços e Situação --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-briefcase me-2 text-danger"></i>{{ __('Serviços e Situação') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label for="services_offered" class="form-label">{{ __('Serviços Oferecidos') }}</label>
                            <textarea class="form-control" id="services_offered" name="services_offered" rows="2">{{ old('services_offered', $supplier->services_offered) }}</textarea>
                            @error('services_offered')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">{{ __('Situação') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Ativo" {{ (old('status', $supplier->status) == 'Ativo') ? 'selected' : '' }}>{{ __('Ativo') }}</option>
                                <option value="Inativo" {{ (old('status', $supplier->status) == 'Inativo') ? 'selected' : '' }}>{{ __('Inativo') }}</option>
                            </select>
                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
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