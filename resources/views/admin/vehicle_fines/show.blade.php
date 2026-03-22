@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes da Multa de Veículo'))

@section('content')

<div class="row mb-6 gy-6" style="margin-bottom: 10px !important;">
    <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-0">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Detalhes da Multa de Veículo') }}</h5>
                <a href="{{ route('vehicle_fines.index') }}" class="btn btn-primary">{{ __('Voltar') }}</a>
            </div> 
        </div>
    </div>
</div>
<div class="row mb-6 gy-6">
    <!-- Left Column: Vehicle Fine Details -->
    <div class="col-xl-8 col-lg-8 col-md-8 order-0 order-md-0">
        <!-- About Vehicle Fine Card -->
        <div class="card mb-4">

            <div class="card-body">
                <small class="card-text text-uppercase">{{ __('Informações da Multa') }}</small>
                <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-hash"></i><span class="fw-medium mx-2">{{ __('Número da Multa:') }}</span>
                        <span>{{ $vehicleFine->fine_number }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar"></i><span class="fw-medium mx-2">{{ __('Data da Infração:') }}</span>
                        <span>{{ $vehicleFine->infraction_date ? $vehicleFine->infraction_date->format('d/m/Y') : '-' }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-money"></i><span class="fw-medium mx-2">{{ __('Valor da Multa:') }}</span>
                        <span>R$ {{ number_format($vehicleFine->fine_amount, 2, ',', '.') }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-car"></i><span class="fw-medium mx-2">{{ __('Veículo:') }}</span>
                        <span>({{ $vehicleFine->vehicle->placa }}) {{ $vehicleFine->vehicle->modelo }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-user"></i><span class="fw-medium mx-2">{{ __('Motorista:') }}</span>
                        <span>{{ $vehicleFine->driver ? $vehicleFine->driver->full_name : 'N/A' }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-credit-card"></i><span class="fw-medium mx-2">{{ __('Status do Pagamento:') }}</span>
                        <span class="badge {{ $vehicleFine->payment_status->value == 'paid' ? 'bg-label-success' : ($vehicleFine->payment_status->value == 'pending' ? 'bg-label-warning' : 'bg-label-danger') }}">{{ $vehicleFine->payment_status->getLabel() }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-user-check"></i><span class="fw-medium mx-2">{{ __('Responsável pelo Pagamento:') }}</span>
                        <span>{{ $vehicleFine->responsible_for_payment->getLabel() }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-note"></i><span class="fw-medium mx-2">{{ __('Observações:') }}</span>
                        <span>{{ $vehicleFine->comments ?? 'N/A' }}</span>
                    </li>
                    @if ($vehicleFine->document_reference)
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-file"></i><span class="fw-medium mx-2">{{ __('Referência do Documento:') }}</span>
                        <span><a href="{{ Storage::url($vehicleFine->document_reference) }}" target="_blank">{{ __('Visualizar Documento') }}</a></span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- /About Vehicle Fine Card -->
    </div>
    <!--/ Left Column: Vehicle Fine Details -->

    <!-- Right Column: Activity Timeline -->
    <div class="col-xl-4 col-lg-4 col-md-4 order-1 order-md-1">
        <div class="card card-action mb-4">
            
            <div class="card-header align-items-center">
                
                <div class="card-action-element">
                    <small class="card-text text-uppercase">{{ __('Datas') }}</small>
                <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar"></i><span class="fw-medium mx-2">{{ __('Criado em:') }}</span>
                        <span>{{ $vehicleFine->created_at ? $vehicleFine->created_at->format('d/m/Y H:i:s') : '-' }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar-check"></i><span class="fw-medium mx-2">{{ __('Atualizado em:') }}</span>
                        <span>{{ $vehicleFine->updated_at ? $vehicleFine->updated_at->format('d/m/Y H:i:s') : '-' }}</span>
                    </li>
                </ul>
                </div>
            </div>
            
        </div>
    </div>
    <!--/ Right Column: Activity Timeline -->
</div>
@endsection