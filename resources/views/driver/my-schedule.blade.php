@extends('layouts/contentNavbarLayout')
@section('title', __('Minha Agenda'))
@section('content')
<div class="row mb-3">
  <div class="col-12">
    <div class="card">
      <div class="card-header">{{ __('Entregas de Hoje') }}</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>{{ __('Endereço') }}</th>
                <th>{{ __('Janela') }}</th>
                <th>{{ __('Veículo') }}</th>
                <th>{{ __('Carga') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($dailyDestinations as $d)
              <tr>
                <td>{{ $d->id }}</td>
                <td>{{ $d->address }}</td>
                <td>{{ optional($d->window_start)->format('d/m/Y H:i') }} - {{ optional($d->window_end)->format('d/m/Y H:i') }}</td>
                <td>{{ optional($d->plannedShipment->vehicle)->modelo }} {{ optional($d->plannedShipment->vehicle)->placa }}</td>
                <td>{{ $d->plannedShipment->shipment_number }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mb-3">
  <div class="col-12">
    <div class="card">
      <div class="card-header">{{ __('Entrega na Semana') }}</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>{{ __('Dia/Hora') }}</th>
                <th>{{ __('Endereço') }}</th>
                <th>{{ __('Carga') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($weeklyDestinations as $d)
              <tr>
                <td>{{ $d->id }}</td>
                <td>{{ optional($d->window_start)->format('d/m/Y H:i') }}</td>
                <td>{{ $d->address }}</td>
                <td>{{ $d->plannedShipment->shipment_number }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

