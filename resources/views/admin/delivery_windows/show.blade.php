@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes da Janela de Entrega'))

@section('content')

<div class="row mb-6 gy-6" style="margin-bottom: 10px !important;">
    <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-0">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Detalhes da Janela de Entrega') }}</h5>
                <a href="{{ route('delivery_windows.index') }}" class="btn btn-primary">{{ __('Voltar') }}</a>
            </div> 
        </div>
    </div>
</div>
<div class="row mb-6 gy-6">
    <!-- Left Column: Delivery Window Details -->
    <div class="col-xl-8 col-lg-8 col-md-8 order-0 order-md-0">
        <!-- About Delivery Window Card -->
        <div class="card mb-4">

            <div class="card-body">
                <small class="card-text text-uppercase">{{ __('Informações da Janela') }}</small>
                <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-time"></i><span class="fw-medium mx-2">{{ __('Hora de Início:') }}</span>
                        <span>{{ $deliveryWindow->start_time }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-time-five"></i><span class="fw-medium mx-2">{{ __('Hora de Fim:') }}</span>
                        <span>{{ $deliveryWindow->end_time }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar"></i><span class="fw-medium mx-2">{{ __('Dia da Semana:') }}</span>
                        <span>
                            @php
                                $days = [
                                    1 => 'Domingo',
                                    2 => 'Segunda-feira',
                                    3 => 'Terça-feira',
                                    4 => 'Quarta-feira',
                                    5 => 'Quinta-feira',
                                    6 => 'Sexta-feira',
                                    7 => 'Sábado',
                                ];
                            @endphp
                            {{ $days[$deliveryWindow->day_of_week] ?? '-' }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /About Delivery Window Card -->
    </div>
    <!--/ Left Column: Delivery Window Details -->

    <!-- Right Column: Other Information -->
    <div class="col-xl-4 col-lg-4 col-md-4 order-1 order-md-1">
        <div class="card card-action mb-4">
            
            <div class="card-header align-items-center">
                
                <div class="card-action-element">
                    <small class="card-text text-uppercase">{{ __('Outras Informações') }}</small>
                <ul class="list-unstyled mb-4 mt-3">
                     <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-check"></i><span class="fw-medium mx-2">{{ __('Status:') }}</span>
                        <span class="badge {{ $deliveryWindow->status == 'Ativo' ? 'bg-label-success' : 'bg-label-danger' }}">{{ $deliveryWindow->status }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar"></i><span class="fw-medium mx-2">{{ __('Criado em:') }}</span>
                        <span>{{ $deliveryWindow->created_at ? $deliveryWindow->created_at->format('d/m/Y H:i:s') : '-' }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar-check"></i><span class="fw-medium mx-2">{{ __('Atualizado em:') }}</span>
                        <span>{{ $deliveryWindow->updated_at ? $deliveryWindow->updated_at->format('d/m/Y H:i:s') : '-' }}</span>
                    </li>
                </ul>
                </div>
            </div>
            
        </div>
    </div>
    <!--/ Right Column: Other Information -->
</div>
@endsection