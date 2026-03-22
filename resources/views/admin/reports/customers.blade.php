@extends('layouts/contentNavbarLayout')

@section('title', __('Relatórios de Clientes'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card"> 
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Relatórios de Clientes') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card bg-label-success text-white">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <i class="bx bx-user-check bx-lg"></i>
                                    </div>
                                </div>
                                <span class="d-block mb-1">{{ __('Clientes Ativos') }}</span>
                                <h3 class="card-title text-nowrap mb-2">{{ $activeCustomers }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-label-danger text-white">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <i class="bx bx-user-minus bx-lg"></i>
                                    </div>
                                </div>
                                <span class="d-block mb-1">{{ __('Clientes Inativos') }}</span>
                                <h3 class="card-title text-nowrap mb-2">{{ $inactiveCustomers }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-label-info text-white">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <i class="bx bx-user-plus bx-lg"></i>
                                    </div>
                                </div>
                                <span class="d-block mb-1">{{ __('Novos Clientes (últimos 30 dias)') }}</span>
                                <h3 class="card-title text-nowrap mb-2">{{ $newCustomersLast30Days }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection