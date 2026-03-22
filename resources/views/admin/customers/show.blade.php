@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes do Cliente'))

@section('content')

<div class="row mb-6 gy-6" style="margin-bottom: 10px !important;">
    <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-0">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Detalhes do Cliente') }}</h5>
                <a href="{{ route('customers.index') }}" class="btn btn-primary">{{ __('Voltar') }}</a>
            </div> 
        </div>
    </div>
</div>
<div class="row mb-6 gy-6">
    <!-- Left Column: Customer Details -->
    <div class="col-xl-8 col-lg-8 col-md-8 order-0 order-md-0">
        <!-- About User Card -->
        <div class="card mb-4">

            <div class="card-body">
                <small class="card-text text-uppercase">{{ __('Contatos') }}</small>
                <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-user"></i><span class="fw-medium mx-2">{{ __('Nome Completo:') }}</span>
                        <span>{{ $customer->customer_type == 'PF' ? $customer->full_name : $customer->company_name }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-briefcase"></i><span class="fw-medium mx-2">{{ __('Tipo de Cliente:') }}</span>
                        <span>{{ $customer->customer_type == 'PF' ? 'Pessoa Física' : 'Pessoa Jurídica' }}</span>
                    </li>
                    @if($customer->customer_type == 'PF')
                        <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-id-card"></i><span class="fw-medium mx-2">{{ __('CPF:') }}</span>
                            <span>{{ $customer->cpf ? substr($customer->cpf, 0, 3) . '.' . substr($customer->cpf, 3, 3) . '.' . substr($customer->cpf, 6, 3) . '-' . substr($customer->cpf, 9, 2) : '-' }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-id-card"></i><span class="fw-medium mx-2">{{ __('RG:') }}</span>
                            <span>{{ $customer->rg }}</span>
                        </li>
                    @else
                        <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-building"></i><span class="fw-medium mx-2">{{ __('CNPJ:') }}</span>
                            <span>{{ $customer->cnpj ? substr($customer->cnpj, 0, 2) . '.' . substr($customer->cnpj, 2, 3) . '.' . substr($customer->cnpj, 5, 3) . '/' . substr($customer->cnpj, 8, 4) . '-' . substr($customer->cnpj, 12, 2) : '-' }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-building"></i><span class="fw-medium mx-2">{{ __('Inscrição Estadual:') }}</span>
                            <span>{{ $customer->ie }}</span>
                        </li>
                    @endif
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-map"></i><span class="fw-medium mx-2">{{ __('Endereço:') }}</span>
                        <span>{{ $customer->address_street }}, {{ $customer->address_number }} - {{ $customer->address_neighborhood }}, {{ $customer->address_city }} - {{ $customer->address_state }}, {{ $customer->address_zip_code }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-home"></i><span class="fw-medium mx-2">{{ __('Tipo de Endereço:') }}</span>
                        <span>{{ $customer->address_type }}</span>
                    </li>
                    @if ($customer->customer_type == 'PJ')
                        
                        <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-home"></i><span class="fw-medium mx-2">{{ __('Representante:') }}</span>
                            <span>{{ $customer->representative_name }}</span>
                        </li>
                    @endif
                     
                </ul>
            </div>
        </div>
        <!-- /About User Card -->
    </div>
    <!--/ Left Column: Customer Details -->

    <!-- Right Column: Activity Timeline -->
    <div class="col-xl-4 col-lg-4 col-md-4 order-1 order-md-1">
        <div class="card card-action mb-4">
            
            <div class="card-header align-items-center">
                
                <div class="card-action-element">
                    <small class="card-text text-uppercase">{{ __('Contatos') }}</small>
                <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-phone"></i><span class="fw-medium mx-2">{{ __('Contato:') }}</span>
                        <a style="color: #696cff" href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $customer->phone) }}?text=Ol%C3%A1%2C%20aqui%20%C3%A9%20da%20Bassani%20M%C3%B3veis" target="_blank">{{ $customer->phone }}</a>
                    </li>

                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-envelope"></i><span class="fw-medium mx-2">{{ __('Email:') }}</span>
                        <span>{{ $customer->email }}</span>
                    </li>
                </ul>
                <small class="card-text text-uppercase mt-5">{{ __('Outras Informações') }}</small>
                <ul class="list-unstyled mb-4 mt-3">
                     <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-check"></i><span class="fw-medium mx-2">{{ __('Status:') }}</span>
                        <span class="badge {{ $customer->status == 'Ativo' ? 'bg-label-success' : 'bg-label-danger' }}">{{ $customer->status }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar"></i><span class="fw-medium mx-2">{{ __('Criado em:') }}</span>
                        <span>{{ $customer->created_at ? $customer->created_at->format('d/m/Y H:i:s') : '-' }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar-check"></i><span class="fw-medium mx-2">{{ __('Atualizado em:') }}</span>
                        <span>{{ $customer->updated_at ? $customer->updated_at->format('d/m/Y H:i:s') : '-' }}</span>
                    </li>
                </ul>
                </div>
            </div>
            
        </div>
    </div>
    <!--/ Right Column: Activity Timeline -->
</div>
@endsection