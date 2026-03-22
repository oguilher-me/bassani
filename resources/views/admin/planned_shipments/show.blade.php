@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes da Carga Planejada'))

@section('content')

<div class="row mb-6 gy-6" style="margin-bottom: 10px !important;">
    <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-0">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Detalhes da Carga Planejada') }} #{{ $plannedShipment->shipment_number }}</h5>
                <a href="{{ route('planned_shipments.index') }}" class="btn btn-primary">{{ __('Voltar') }}</a>
            </div> 
        </div>
    </div>
</div>
<div class="row mb-6 gy-6">
    <!-- Left Column: Planned Shipment Details -->
    <div class="col-xl-8 col-lg-8 col-md-8 order-0 order-md-0">
        <!-- About Planned Shipment Card -->
        <div class="card mb-4">
            <div class="card-body">
                <small class="card-text text-uppercase">{{ __('Informações da Carga') }}</small>
                <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-hash"></i><span class="fw-medium mx-2">{{ __('Número da Carga:') }}</span>
                        <span>{{ $plannedShipment->shipment_number }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-car"></i><span class="fw-medium mx-2">{{ __('Veículo:') }}</span>
                        <span>{{ $plannedShipment->vehicle->modelo ?? __('N/A') }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-user"></i><span class="fw-medium mx-2">{{ __('Motorista:') }}</span>
                        <span>{{ $plannedShipment->driver->full_name ?? __('N/A') }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar"></i><span class="fw-medium mx-2">{{ __('Saída Planejada:') }}</span>
                        <span>{{ $plannedShipment->planned_departure_date ? $plannedShipment->planned_departure_date->format('d/m/Y') : __('N/A') }}</span>
                    </li>
                   
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-box"></i><span class="fw-medium mx-2">{{ __('Peso Total:') }}</span>
                        <span>{{ $plannedShipment->total_weight }} kg</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-cube"></i><span class="fw-medium mx-2">{{ __('Volume Total:') }}</span>
                        <span>{{ $plannedShipment->total_volume }} m³</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-comment-detail"></i><span class="fw-medium mx-2">{{ __('Observações:') }}</span>
                        <span>{{ $plannedShipment->remarks ?? __('N/A') }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /About Planned Shipment Card -->

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Destinos da Carga') }}</h5>
            </div>
            <div class="card-body">
                @php $dests = $plannedShipment->destinations ?? collect(); @endphp
                @if($dests->count() > 0)
                    @foreach($dests as $d)
                        <div class="mb-3">
                            <div class="fw-bold mb-4">{{ $d->address }}</div>
                            <div class="text-muted mb-2"><i class="bx bx-user"></i> &nbsp; {{ $d->contact_name }}   •   <i class="bx bx-phone"></i> &nbsp;{{ $d->contact_phone }}</div>
                            <div class="small mb-4"><i class="bx bx-calendar"></i> &nbsp;{{ $d->window_start ? $d->window_start->format('d/m/Y H:i') : '' }} - {{ $d->window_end ? $d->window_end->format('d/m/Y H:i') : '' }}</div>
                            @php $dItems = $d->items ?? collect(); @endphp
                            @if($dItems->count() > 0)
                                <div class="table-responsive mt-2">
                                  <table class="table table-sm">
                                    <thead>
                                      <tr>
                                        <th>{{ __('Pedido') }}</th>
                                        <th>{{ __('Produto') }}</th>
                                        <th>{{ __('Descrição') }}</th>
                                        <th>{{ __('Quantidade') }}</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @foreach($dItems as $it)
                                        <tr>
                                          <td>{{ $it->sale_id }}</td>
                                          <td>{{ optional($it->product)->name }}</td>
                                          <td>{{ $it->description }}</td>
                                          <td>{{ $it->quantity }}</td>
                                        </tr>
                                      @endforeach
                                    </tbody>
                                  </table>
                                </div>
                            @else
                                <div class="text-muted">{{ __('Sem itens vinculados a este destino.') }}</div>
                            @endif
                        </div>
                        <hr/>
                    @endforeach
                @else
                    <p>{{ __('Nenhum destino informado.') }}</p>
                @endif
            </div>
        </div>

    </div>
    <!--/ Left Column: Planned Shipment Details -->

    <!-- Right Column: Other Information -->
    <div class="col-xl-4 col-lg-4 col-md-4 order-1 order-md-1">
        <div class="card card-action mb-4">
            <div class="card-header align-items-center">
                <div class="card-action-element">
                    <small class="card-text text-uppercase">{{ __('Outras Informações') }}</small>
                    <ul class="list-unstyled mb-4 mt-3">
                        <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-check"></i><span class="fw-medium mx-2">{{ __('Status:') }}</span>
                            <span class="badge {{ $plannedShipment->status == 'Planned' ? 'bg-label-info' : ($plannedShipment->status == 'In Transit' ? 'bg-label-warning' : ($plannedShipment->status == 'Delivered' ? 'bg-label-success' : 'bg-label-danger')) }}">{{ __($plannedShipment->status) }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-calendar"></i><span class="fw-medium mx-2">{{ __('Criado em:') }}</span>
                            <span>{{ $plannedShipment->created_at ? $plannedShipment->created_at->format('d/m/Y H:i:s') : '-' }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-calendar-check"></i><span class="fw-medium mx-2">{{ __('Atualizado em:') }}</span>
                            <span>{{ $plannedShipment->updated_at ? $plannedShipment->updated_at->format('d/m/Y H:i:s') : '-' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--/ Right Column: Other Information -->
</div>
@endsection
