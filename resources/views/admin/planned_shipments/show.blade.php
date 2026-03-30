@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes da Carga Planejada'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Detalhes da Carga Planejada') }}</h4>
        <p class="text-muted mb-0">{{ $plannedShipment->shipment_number }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('planned_shipments.edit', $plannedShipment) }}" class="btn btn-primary">
            <i class="bx bx-edit me-1"></i> {{ __('Editar') }}
        </a>
        <a href="{{ route('planned_shipments.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- Shipment Info Card --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted mb-4">
                    <i class="bx bx-package me-2 text-danger"></i>{{ __('Informações da Carga') }}
                </h6>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-hash"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Número da Carga') }}</small>
                                <span class="fw-semibold">{{ $plannedShipment->shipment_number }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-calendar"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Saída Planejada') }}</small>
                                <span class="fw-semibold">{{ $plannedShipment->planned_departure_date ? $plannedShipment->planned_departure_date->format('d/m/Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-car"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Veículo') }}</small>
                                <span class="fw-semibold">{{ $plannedShipment->vehicle->modelo ?? '-' }}</span>
                                <small class="text-muted d-block">{{ $plannedShipment->vehicle->placa ?? '' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-secondary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-user"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Motorista') }}</small>
                                <span class="fw-semibold">{{ $plannedShipment->driver->full_name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bx bx-box text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Peso Total') }}</small>
                                <span class="fw-semibold">{{ $plannedShipment->total_weight ? number_format($plannedShipment->total_weight, 2, ',', '.') . ' kg' : '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bx bx-cube text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Volume Total') }}</small>
                                <span class="fw-semibold">{{ $plannedShipment->total_volume ? number_format($plannedShipment->total_volume, 2, ',', '.') . ' m³' : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($plannedShipment->remarks)
                <hr class="my-4">
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-note me-2 text-danger"></i>{{ __('Observações') }}
                </h6>
                <p class="mb-0">{{ $plannedShipment->remarks }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Status & Dates Card --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                {{-- Status --}}
                <div class="text-center mb-4 pb-4 border-bottom">
                    <div class="mb-3">
                        @if($plannedShipment->status == 'Planned')
                            <span class="badge bg-info rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-calendar-check me-1"></i>{{ __('Planejado') }}
                            </span>
                        @elseif($plannedShipment->status == 'In Transit')
                            <span class="badge bg-warning rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-loader me-1"></i>{{ __('Em Trânsito') }}
                            </span>
                        @elseif($plannedShipment->status == 'Delivered')
                            <span class="badge bg-success rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-check-circle me-1"></i>{{ __('Entregue') }}
                            </span>
                        @elseif($plannedShipment->status == 'Returned')
                            <span class="badge bg-primary rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-undo me-1"></i>{{ __('Devolvido') }}
                            </span>
                        @else
                            <span class="badge bg-danger rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-x-circle me-1"></i>{{ __('Cancelado') }}
                            </span>
                        @endif
                    </div>
                </div>
                
                {{-- Timestamps --}}
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-time me-2 text-danger"></i>{{ __('Informações do Sistema') }}
                </h6>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">{{ __('Criado em') }}</span>
                    <span class="fw-semibold small">{{ $plannedShipment->created_at ? $plannedShipment->created_at->format('d/m/Y H:i') : '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">{{ __('Atualizado em') }}</span>
                    <span class="fw-semibold small">{{ $plannedShipment->updated_at ? $plannedShipment->updated_at->format('d/m/Y H:i') : '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Destinations --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-map text-danger me-2"></i>{{ __('Destinos da Carga') }}
                </h6>
            </div>
            <div class="card-body">
                @php $dests = $plannedShipment->destinations ?? collect(); @endphp
                @if($dests->count() > 0)
                    <div class="row g-4">
                        @foreach($dests as $d)
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                        <span class="fw-semibold">
                                            <i class="bx bx-map-pin text-danger me-1"></i>
                                            {{ __('Destino #') }}{{ $loop->iteration }}
                                        </span>
                                        <a href="{{ route('driver.destinations.show', $d->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bx bx-show"></i>
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <p class="fw-semibold mb-2">{{ $d->address }}</p>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bx bx-user text-muted me-2"></i>
                                            <span class="small">{{ $d->contact_name }}</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bx bx-phone text-muted me-2"></i>
                                            <span class="small">{{ $d->contact_phone }}</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bx-time text-muted me-2"></i>
                                            <span class="small">{{ $d->window_start ? $d->window_start->format('d/m/Y H:i') : '' }} - {{ $d->window_end ? $d->window_end->format('d/m/Y H:i') : '' }}</span>
                                        </div>
                                        
                                        @php $dItems = $d->items ?? collect(); @endphp
                                        @if($dItems->count() > 0)
                                            <hr>
                                            <small class="text-muted d-block mb-2">{{ __('Itens') }}:</small>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-borderless mb-0">
                                                    <tbody>
                                                        @foreach($dItems as $it)
                                                            <tr>
                                                                <td class="py-1 px-0">
                                                                    <span class="badge bg-light text-dark">#{{ $it->sale_id }}</span>
                                                                </td>
                                                                <td class="py-1">
                                                                    <span class="small">{{ optional($it->product)->name ?? $it->description }}</span>
                                                                </td>
                                                                <td class="py-1 text-end">
                                                                    <span class="badge bg-label-secondary">Qtd: {{ $it->quantity }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bx bx-map-pin fs-1 text-muted opacity-50"></i>
                        <p class="text-muted mt-2">{{ __('Nenhum destino informado.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection