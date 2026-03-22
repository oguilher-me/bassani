@extends('layouts/contentNavbarLayout')

@section('title', __('Dashboard de Vendas'))

@section('content')
@php $canSeePrices = Auth::check() && Auth::user()->role_id == 1; @endphp
<div class="row mb-3">
  <div class="col-12">
    <form class="row g-2" method="GET" action="{{ route('sales.dashboard') }}">
      <div class="col-md-2">
        <label class="form-label">{{ __('Início') }}</label>
        <input type="date" name="start_date" value="{{ $filters['start'] }}" class="form-control">
      </div>
      <div class="col-md-2">
        <label class="form-label">{{ __('Fim') }}</label>
        <input type="date" name="end_date" value="{{ $filters['end'] }}" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">{{ __('Vendedor') }}</label>
        <select name="representative_id" class="form-select">
          <option value="">{{ __('Todos') }}</option>
          @foreach($representatives as $r)
            <option value="{{ $r->id }}" {{ ($filters['representativeId'] ?? '') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">{{ __('Canal de venda') }}</label>
        <input type="text" name="sales_division" value="{{ $filters['division'] }}" class="form-control" placeholder="Ex.: Loja, Online">
      </div>
      <div class="col-md-2">
        <label class="form-label">{{ __('Status') }}</label>
        <select name="order_status" class="form-select">
          <option value="">{{ __('Todos') }}</option>
          @foreach([ 'Open','In Production','In Transit','Delivered','In Assembly','Completed','Cancelled' ] as $st)
            <option value="{{ $st }}" {{ ($filters['status'] ?? '') == $st ? 'selected' : '' }}>{{ $st }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">{{ __('Filtrar') }}</button>
      </div>
    </form>
  </div>
  {{-- <div class="col-12 mt-2">
    <a href="{{ route('sales.export.excel') }}" class="btn btn-outline-secondary">{{ __('Exportar Excel') }}</a>
  </div> --}}
  
</div>

<div class="row mb-4">
  <div class="col-lg-2 col-sm-6"><div class="card card-border-shadow-primary h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-receipt icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['totalOrders'] }}</h4></div><p class="mb-2">{{ __('Pedidos') }}</p></div></div></div>
  <div class="col-lg-2 col-sm-6"><div class="card card-border-shadow-danger h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-danger"><i class="icon-base bx bx-time-five icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['lateDeliveries'] }}</h4></div><p class="mb-2">{{ __('Pedidos em atraso') }}</p></div></div></div>
  <div class="col-lg-2 col-sm-6"><div class="card card-border-shadow-info h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-info"><i class="icon-base bx bx-target-lock icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['conversionRate'] }}%</h4></div><p class="mb-2">{{ __('Taxa de conversão') }}</p></div></div></div>
  <div class="col-lg-3 col-sm-6"><div class="card card-border-shadow-success h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-success"><i class="icon-base bx bx-dollar icon-lg"></i></span></div><h4 class="mb-0">@if($canSeePrices) R$ {{ number_format($cards['totalRevenue'],2,',','.') }} @else — @endif</h4></div><p class="mb-2">{{ __('Faturamento no período') }}</p></div></div></div>
  <div class="col-lg-3 col-sm-6"><div class="card card-border-shadow-warning h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-warning"><i class="icon-base bx bx-purchase-tag icon-lg"></i></span></div><h4 class="mb-0">@if($canSeePrices) R$ {{ number_format($cards['avgTicket'],2,',','.') }} @else — @endif</h4></div><p class="mb-2">{{ __('Ticket médio') }}</p></div></div></div>
  {{-- <div class="col-lg-2 col-sm-6"><div class="card card-border-shadow-secondary h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-secondary"><i class="icon-base bx bx-trending-up icon-lg"></i></span></div><h4 class="mb-0">@if($canSeePrices) R$ {{ number_format($cards['projectionRevenue'],2,',','.') }} @else — @endif</h4></div><p class="mb-2">{{ __('Projeção do mês') }}</p></div></div></div> --}}
  
</div>

<div class="row mb-4">
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Funil por status') }}</div><div class="card-body"><div id="chartStatus"></div></div></div></div>
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Faturamento mensal (12 meses)') }}</div><div class="card-body"><div id="chartMonthlyRevenue"></div></div></div></div>
</div>

<div class="row mb-4">
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Vendas por canal') }}</div><div class="card-body"><div id="chartDivision"></div></div></div></div>
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Top vendedores por faturamento') }}</div><div class="card-body"><div id="chartTopReps"></div></div></div></div>
</div>

<div class="row mb-4">
  <div class="col-md-12"><div class="card"><div class="card-header">{{ __('Top produtos') }}</div><div class="card-body"><div id="chartTopProducts"></div></div></div></div>
</div>

<div class="row mb-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">{{ __('Últimos pedidos') }}</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>{{ __('Data') }}</th>
                <th>{{ __('Cliente') }}</th>
                <th>{{ __('Vendedor') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Total') }}</th>
                <th>{{ __('Ações') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($recentOrders as $o)
              <tr>
                <td>{{ $o->id }}</td>
                <td>{{ optional($o->issue_date)->format('d/m/Y') }}</td>
                <td>{{ $o->customer ? ($o->customer->customer_type == 'PF' ? $o->customer->full_name : $o->customer->company_name) : '' }}</td>
                <td>{{ $o->representative ? $o->representative->name : '' }}</td>
                <td>{{ is_object($o->order_status) ? $o->order_status->label() : $o->order_status }}</td>
                <td>@if($canSeePrices) R$ {{ number_format((float)$o->grand_total,2,',','.') }} @else — @endif</td>
                <td><a href="{{ route('sales.show', $o->id) }}" class="btn btn-sm btn-primary">{{ __('Ver') }}</a></td>
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

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
const statusDist = {!! json_encode($statusDist) !!};
new ApexCharts(document.querySelector('#chartStatus'), { chart:{ type:'bar', height:300 }, series:[{ name:'Pedidos', data: Object.values(statusDist) }], xaxis:{ categories: Object.keys(statusDist) } }).render();

const monthlyRev = {!! json_encode(array_values($monthlyRevenue)) !!};
const monthlyLabels = {!! json_encode(array_keys($monthlyRevenue)) !!};
new ApexCharts(document.querySelector('#chartMonthlyRevenue'), { chart:{ type:'line', height:300 }, series:[{ name:'Faturamento', data: monthlyRev }], xaxis:{ categories: monthlyLabels } }).render();

const divisionDist = {!! json_encode($divisionDist) !!};
new ApexCharts(document.querySelector('#chartDivision'), { chart:{ type:'donut', height:300 }, series: Object.values(divisionDist), labels: Object.keys(divisionDist) }).render();

const topRepsData = {!! json_encode($topRepresentatives) !!};
new ApexCharts(document.querySelector('#chartTopReps'), { chart:{ type:'bar', height:300 }, series:[{ name:'Faturamento', data: topRepsData.map(x => x.total) }], xaxis:{ categories: topRepsData.map(x => x.name) } }).render();

const topProductsData = {!! json_encode($topProducts) !!};
new ApexCharts(document.querySelector('#chartTopProducts'), { chart:{ type:'bar', height:300 }, series:[{ name:'Faturamento', data: topProductsData.map(x => x.total) }], xaxis:{ categories: topProductsData.map(x => x.name) } }).render();
</script>
@endsection

