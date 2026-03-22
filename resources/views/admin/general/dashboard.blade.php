@extends('layouts/contentNavbarLayout')

@section('title', __('Dashboard Geral'))

@section('content')
@php $canSeePrices = Auth::check() && Auth::user()->role_id == 1; @endphp
<div class="row mb-3">
  <div class="col-12">
    <form class="row g-2" method="GET" action="{{ route('dashboard.index') }}">
      <div class="col-md-2">
        <label class="form-label">{{ __('Período') }}</label>
        <select name="period" class="form-select">
          @foreach(['day'=>'Dia','week'=>'Semana','month'=>'Mês','year'=>'Ano'] as $k=>$v)
            <option value="{{ $k }}" {{ ($filters['period'] ?? 'month')==$k ? 'selected' : '' }}>{{ $v }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">{{ __('Início') }}</label>
        <input type="date" name="start_date" value="{{ $filters['start'] }}" class="form-control">
      </div>
      <div class="col-md-2">
        <label class="form-label">{{ __('Fim') }}</label>
        <input type="date" name="end_date" value="{{ $filters['end'] }}" class="form-control">
      </div>
      <div class="col-md-2">
        <label class="form-label">{{ __('Vendedor') }}</label>
        <select name="representative_id" class="form-select">
          <option value="">{{ __('Todos') }}</option>
          @foreach($representatives as $r)
            <option value="{{ $r->id }}" {{ ($filters['representativeId'] ?? '') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
          @endforeach
        </select>
      </div>
      {{-- <div class="col-md-2">
        <label class="form-label">{{ __('Canal de venda') }}</label>
        <input type="text" name="sales_division" value="{{ $filters['division'] }}" class="form-control">
      </div>
      <div class="col-md-2">
        <label class="form-label">{{ __('Região (UF)') }}</label>
        <input type="text" name="region_state" value="{{ $filters['region_state'] }}" class="form-control">
      </div>
      <div class="col-md-2">
        <label class="form-label">{{ __('Cidade') }}</label>
        <input type="text" name="region_city" value="{{ $filters['region_city'] }}" class="form-control">
      </div> --}}
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">{{ __('Aplicar') }}</button>
      </div>
    </form>
  </div>
  
</div>

<div class="row mb-4">
  <div class="col-lg-3 col-sm-6"><div class="card card-border-shadow-success h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-success"><i class="icon-base bx bx-dollar icon-lg"></i></span></div><h4 class="mb-0">@if($canSeePrices) R$ {{ number_format($cards['salesTotal'],2,',','.') }} @else — @endif</h4><span class="ms-auto {{ $cards['salesTotalVar']>=0 ? 'text-success' : 'text-danger' }}">{{ $cards['salesTotalVar'] }}%</span></div><p class="mb-2">{{ __('Vendas Totais do Período') }}</p></div></div></div>
  <div class="col-lg-3 col-sm-6"><div class="card card-border-shadow-primary h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-receipt icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['openOrders'] }}</h4><span class="ms-auto {{ $cards['openOrdersVar']<=0 ? 'text-success' : 'text-danger' }}">{{ $cards['openOrdersVar'] }}%</span></div><p class="mb-2">{{ __('Pedidos Abertos') }}</p></div></div></div>
  <div class="col-lg-3 col-sm-6"><div class="card card-border-shadow-danger h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-danger"><i class="icon-base bx bx-time-five icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['delayedOrders'] }}</h4><span class="ms-auto {{ $cards['delayedOrders']>0 ? 'text-danger' : 'text-success' }}">{{ $cards['delayedOrdersVar'] }}%</span></div><p class="mb-2">{{ __('Pedidos em Atraso') }}</p></div></div></div>
  <div class="col-lg-3 col-sm-6"><div class="card card-border-shadow-info h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-info"><i class="icon-base bx bx-user-check icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['activeCustomers'] }}</h4><span class="ms-auto text-muted">{{ $cards['activeCustomersVar'] }}%</span></div><p class="mb-2">{{ __('Clientes Ativos') }}</p></div></div></div>
</div>

<div class="row mb-4">
  <div class="col-lg-3 col-sm-6"><div class="card card-border-shadow-warning h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-warning"><i class="icon-base bx bx-calendar-check icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['assembliesToday'] }}</h4><span class="ms-auto {{ $cards['assembliesTodayVar']>=0 ? 'text-success' : 'text-danger' }}">{{ $cards['assembliesTodayVar'] }}%</span></div><p class="mb-2">{{ __('Montagens Programadas Hoje') }}</p></div></div></div>
  <div class="col-lg-3 col-sm-6"><div class="card card-border-shadow-secondary h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-secondary"><i class="icon-base bx bx-car icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['vehiclesInOperation'] }}</h4><span class="ms-auto {{ $cards['vehiclesInOperationVar']>=0 ? 'text-success' : 'text-danger' }}">{{ $cards['vehiclesInOperationVar'] }}%</span></div><p class="mb-2">{{ __('Veículos em Operação') }}</p></div></div></div>
  <div class="col-lg-3 col-sm-6"><div class="card card-border-shadow-dark h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-dark"><i class="icon-base bx bx-spa icon-lg"></i></span></div><h4 class="mb-0">@if($canSeePrices) R$ {{ number_format($cards['operationalCosts'],2,',','.') }} @else — @endif</h4><span class="ms-auto {{ $cards['operationalCostsVar']<=0 ? 'text-success' : 'text-danger' }}">{{ $cards['operationalCostsVar'] }}%</span></div><p class="mb-2">{{ __('Custos Operacionais do Período') }}</p></div></div></div>
  <div class="col-lg-3 col-sm-6"><div class="card card-border-shadow-primary h-100"><div class="card-body"><div class="d-flex align-items-center mb-2"><div class="avatar me-4"><span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-smile icon-lg"></i></span></div><h4 class="mb-0">{{ $cards['nps'] }}</h4><span class="ms-auto {{ $cards['nps']>=7 ? 'text-success' : 'text-danger' }}">{{ $cards['npsVar'] }}%</span></div><p class="mb-2">{{ __('Índice Geral de Satisfação') }}</p></div></div></div>
</div>

<div class="row mb-4">
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Vendas Mensais (Ano Atual vs Anterior)') }}</div><div class="card-body"><div id="chartMonthlyCompare"></div></div></div></div>
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Status Geral da Produção') }}</div><div class="card-body"><div id="chartStatus"></div></div></div></div>
</div>

<div class="row mb-4">
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Montagens por dia da semana (30 dias)') }}</div><div class="card-body"><div id="chartWeekday"></div></div></div></div>
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Utilização da Frota') }}</div><div class="card-body"><div id="chartFleet"></div></div></div></div>
</div>

<div class="row mb-4">
  <div class="col-md-12"><div class="card"><div class="card-header">{{ __('Desempenho Logístico') }}</div><div class="card-body"><div id="chartLog"></div></div></div></div>
</div>

<div class="row mb-4">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">{{ __('Últimos Pedidos Criados') }}</div>
      <div class="card-body">
        <div class="table-responsive"><table class="table"><thead><tr><th>ID</th><th>{{ __('Cliente') }}</th><th>{{ __('Valor') }}</th><th>{{ __('Status') }}</th><th>{{ __('Previsão de entrega') }}</th><th>{{ __('Ações') }}</th></tr></thead><tbody>
          @foreach($recentOrders as $o)
            <tr>
              <td>{{ $o->id }}</td>
              <td>{{ $o->customer ? ($o->customer->customer_type == 'PF' ? $o->customer->full_name : $o->customer->company_name) : '' }}</td>
              <td>@if($canSeePrices) R$ {{ number_format((float)$o->grand_total,2,',','.') }} @else — @endif</td>
              <td>{{ is_object($o->order_status) ? $o->order_status->label() : $o->order_status }}</td>
              <td>{{ optional($o->expected_delivery_date)->format('d/m/Y') }}</td>
              <td><a href="{{ route('sales.show',$o->id) }}" class="btn btn-sm btn-primary">{{ __('Ver') }}</a></td>
            </tr>
          @endforeach
        </tbody></table></div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">{{ __('Próximas Montagens') }}</div>
      <div class="card-body">
        <div class="table-responsive"><table class="table"><thead><tr><th>{{ __('Venda') }}</th><th>{{ __('Cliente') }}</th><th>{{ __('Endereço') }}</th><th>{{ __('Montador(es)') }}</th><th>{{ __('Data/Hora') }}</th><th>{{ __('Status') }}</th></tr></thead><tbody>
          @foreach($nextAssemblies as $a)
            <tr>
              <td>{{ $a->sale_id }}</td>
              <td>{{ optional($a->sale->customer)->full_name ?? optional($a->sale->customer)->company_name }}</td>
              <td>
                @php $c = optional(optional($a->sale)->customer); @endphp
                {{ trim(($c->address_street ?? '') . ' ' . ($c->address_number ?? '') . ' - ' . ($c->address_city ?? '') . '/' . ($c->address_state ?? '')) }}
              </td>
              <td>
                @if($a->assemblers)
                  {{ $a->assemblers->pluck('name')->implode(', ') }}
                @endif
              </td>
              <td>{{ optional($a->scheduled_date)->format('d/m/Y') }} {{ optional($a->start_time)->format('H:i') }}</td>
              <td>{{ __('Agendado') }}</td>
            </tr>
          @endforeach
        </tbody></table></div>
      </div>
    </div>
  </div>
</div>


@endsection

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
new ApexCharts(document.querySelector('#chartMonthlyCompare'), {
  chart:{ type:'line', height:300 },
  series:[
    { name:'Ano Atual', data: {!! json_encode(array_values($monthlyCurrentYear)) !!} },
    { name:'Ano Anterior', data: {!! json_encode(array_values($monthlyPrevYear)) !!} },
  ],
  xaxis:{ categories: {!! json_encode(array_keys($monthlyCurrentYear)) !!} }
}).render();

new ApexCharts(document.querySelector('#chartStatus'), {
  chart:{ type:'donut', height:300 },
  series: {!! json_encode(array_values($statusDist)) !!},
  labels: {!! json_encode(array_keys($statusDist)) !!}
}).render();

new ApexCharts(document.querySelector('#chartWeekday'), {
  chart:{ type:'bar', height:300 },
  series:[{ name:'Montagens', data: {!! json_encode(array_values($weekdayCounts)) !!} }],
  xaxis:{ categories: {!! json_encode(array_keys($weekdayCounts)) !!} }
}).render();

new ApexCharts(document.querySelector('#chartFleet'), {
  chart:{ type:'radialBar', height:300 },
  series: [{!! json_encode($fleetUtilizationPct['ativos']) !!}, {!! json_encode($fleetUtilizationPct['manutencao']) !!}, {!! json_encode($fleetUtilizationPct['disponiveis']) !!}],
  labels: ['Ativos','Manutenção','Disponíveis']
}).render();

new ApexCharts(document.querySelector('#chartLog'), {
  chart:{ type:'bar', height:300 },
  series:[{ name:'Qtd', data: [{!! json_encode($logPerf['on_time']) !!}, {!! json_encode($logPerf['late']) !!}, {!! json_encode($logPerf['pending']) !!}, {!! json_encode($logPerf['returns']) !!}] }],
  xaxis:{ categories: ['No prazo','Atrasadas','Pendentes','Devoluções'] }
}).render();

new ApexCharts(document.querySelector('#sparkRevenue'), {
  chart: { type:'line', height:120, sparkline:{ enabled:true } },
  series: [{ name:'Receita', data: {!! json_encode(array_values($finance['sparkMonthly'])) !!} }],
  xaxis: { categories: {!! json_encode(array_keys($finance['sparkMonthly'])) !!} }
}).render();
</script>
@endsection
