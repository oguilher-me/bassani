@extends('layouts/contentNavbarLayout')

@section('title', __('Dashboard de Clientes'))

@section('content')
<div class="row mb-3">
  <div class="col-12">
    <form class="row g-2" method="GET" action="{{ route('customers.dashboard') }}">
      <div class="col-md-2">
        <label class="form-label">{{ __('Início') }}</label>
        <input type="date" name="start_date" value="{{ $filters['start'] }}" class="form-control">
      </div>
      <div class="col-md-2">
        <label class="form-label">{{ __('Fim') }}</label>
        <input type="date" name="end_date" value="{{ $filters['end'] }}" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">{{ __('Cidade') }}</label>
        <input type="text" name="city" value="{{ $filters['city'] }}" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">{{ __('Tipo de Cliente') }}</label>
        <select name="customer_type" class="form-select">
          <option value="">{{ __('Todos') }}</option>
          <option value="PF" {{ ($filters['type'] ?? '')=='PF' ? 'selected' : '' }}>PF</option>
          <option value="PJ" {{ ($filters['type'] ?? '')=='PJ' ? 'selected' : '' }}>PJ</option>
        </select>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">{{ __('Filtrar') }}</button>
      </div>
    </form>
  </div>
</div>

<div class="row mb-4">
  <div class="col-lg-2 col-sm-6"><div class="card card-border-shadow-primary h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-group icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['totalCustomers'] }}</h4></div><p class="mb-2">{{ __('Total de clientes') }}</p></div></div></div>
  <div class="col-lg-2 col-sm-6"><div class="card card-border-shadow-success h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-success"><i class="icon-base bx bx-user-check icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['activeCustomers'] }}</h4></div><p class="mb-2">{{ __('Clientes ativos') }}</p></div></div></div>
  <div class="col-lg-2 col-sm-6"><div class="card card-border-shadow-secondary h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-secondary"><i class="icon-base bx bx-user-minus icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['inactiveCustomers'] }}</h4></div><p class="mb-2">{{ __('Clientes inativos') }}</p></div></div></div>
  <div class="col-lg-2 col-sm-6"><div class="card card-border-shadow-info h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-info"><i class="icon-base bx bx-user-plus icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['newCustomersMonth'] }}</h4></div><p class="mb-2">{{ __('Novos clientes (mês)') }}</p></div></div></div>
  <div class="col-lg-2 col-sm-6"><div class="card card-border-shadow-success h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-success"><i class="icon-base bx bx-refresh icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['retentionRate'] }}%</h4></div><p class="mb-2">{{ __('Taxa de retenção') }}</p></div></div></div>
  <div class="col-lg-2 col-sm-6"><div class="card card-border-shadow-danger h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-danger"><i class="icon-base bx bx-trending-down icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['churnRate'] }}%</h4></div><p class="mb-2">{{ __('Taxa de churn') }}</p></div></div></div>
</div>

<div class="row mb-4">
  <div class="col-lg-3 col-sm-6"><div class="card card-border-shadow-warning h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-warning"><i class="icon-base bx bx-purchase-tag icon-lg"></i></span></div><h4 class="mb-0">R$ {{ number_format($cards['ticketMedio'],2,',','.') }}</h4></div><p class="mb-2">{{ __('Ticket médio') }}</p></div></div></div>
  <div class="col-lg-3 col-sm-6"><div class="card card-border-shadow-primary h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-smile icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['npsGeral'] }}</h4></div><p class="mb-2">{{ __('NPS geral') }}</p></div></div></div>
  <div class="col-lg-3 col-sm-6"><div class="card card-border-shadow-primary h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-wrench icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['npsMontagem'] }}</h4></div><p class="mb-2">{{ __('NPS pós-montagem') }}</p></div></div></div>
  <div class="col-lg-3 col-sm-6"><div class="card card-border-shadow-danger h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-danger"><i class="icon-base bx bx-message-error icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['complaintsCount'] }}</h4></div><p class="mb-2">{{ __('Reclamações (nps<=6)') }}</p></div></div></div>
</div>

<div class="row mb-4">
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Distribuição geográfica (Top 10)') }}</div><div class="card-body"><div id="chartGeo"></div></div></div></div>
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Tipos de clientes') }}</div><div class="card-body"><div id="chartTypes"></div></div></div></div>
</div>

<div class="row mb-4">
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Faixa de gasto') }}</div><div class="card-body"><div id="chartSpendBuckets"></div></div></div></div>
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Top 10 clientes por faturamento') }}</div><div class="card-body"><div id="chartTopCustomers"></div></div></div></div>
</div>

<div class="row mb-4">
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Evolução do faturamento (12 meses)') }}</div><div class="card-body"><div id="chartMonthlyRevenue"></div></div></div></div>
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Vendas por canal') }}</div><div class="card-body"><div id="chartSalesDivision"></div></div></div></div>
</div>

<div class="row mb-4">
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Distribuição NPS') }}</div><div class="card-body"><div id="chartNpsDist"></div></div></div></div>
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Comentários recentes') }}</div><div class="card-body">
    <ul class="list-unstyled mb-0">
      @foreach($evals as $e)
        <li class="mb-2">
          <div><strong>{{ __('NPS') }}:</strong> {{ $e->nps_score }} <span class="text-muted">{{ optional($e->submitted_at)->format('d/m/Y') }}</span></div>
          @if($e->comments)<div class="small">{{ $e->comments }}</div>@endif
        </li>
      @endforeach
    </ul>
  </div></div></div>
</div>
@endsection

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
new ApexCharts(document.querySelector('#chartGeo'), { chart:{ type:'bar', height:300 }, series:[{ name:'Clientes', data: {!! json_encode(array_values($geoDistribution)) !!} }], xaxis:{ categories: {!! json_encode(array_keys($geoDistribution)) !!} } }).render();
new ApexCharts(document.querySelector('#chartTypes'), { chart:{ type:'pie', height:300 }, series: {!! json_encode(array_values($typeDistribution)) !!}, labels: {!! json_encode(array_keys($typeDistribution)) !!} }).render();
new ApexCharts(document.querySelector('#chartSpendBuckets'), { chart:{ type:'bar', height:300 }, series:[{ name:'Clientes', data: {!! json_encode(array_values($spendBuckets)) !!} }], xaxis:{ categories: {!! json_encode(array_keys($spendBuckets)) !!} } }).render();
new ApexCharts(document.querySelector('#chartTopCustomers'), { chart:{ type:'bar', height:300 }, series:[{ name:'Faturamento', data: {!! json_encode(array_map(function($x){ return $x['total']; }, $topCustomers)) !!} }], xaxis:{ categories: {!! json_encode(array_map(function($x){ return $x['name']; }, $topCustomers)) !!} } }).render();
new ApexCharts(document.querySelector('#chartMonthlyRevenue'), { chart:{ type:'line', height:300 }, series:[{ name:'Faturamento', data: {!! json_encode(array_values($monthlyRevenue)) !!} }], xaxis:{ categories: {!! json_encode(array_keys($monthlyRevenue)) !!} } }).render();
new ApexCharts(document.querySelector('#chartSalesDivision'), { chart:{ type:'donut', height:300 }, series: {!! json_encode(array_values($salesDivision)) !!}, labels: {!! json_encode(array_keys($salesDivision)) !!} }).render();
new ApexCharts(document.querySelector('#chartNpsDist'), { chart:{ type:'pie', height:300 }, series: {!! json_encode([ (int)$promoters, (int)$passives, (int)$detractors ]) !!}, labels:['Promotores','Passivos','Detratores'], colors:['#1cc88a','#f6c23e','#e74a3b'] }).render();
</script>
@endsection

