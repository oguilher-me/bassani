@extends('layouts/contentNavbarLayout')

@section('title', __('Dashboard Geral'))

@section('content')
@php $canSeePrices = Auth::check() && Auth::user()->role_id == 1; @endphp

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Dashboard Geral') }}</h4>
        <p class="text-muted mb-0">{{ __('Visão completa do desempenho do negócio') }}</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
            <i class="bx bx-printer me-1"></i> {{ __('Imprimir') }}
        </button>
    </div>
</div>

{{-- Filters Card --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form class="row g-3 align-items-end" method="GET" action="{{ route('dashboard.index') }}">
            <div class="col-lg-2 col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Período') }}</label>
                <select name="period" class="form-select form-select-sm">
                    @foreach(['day'=>'Dia','week'=>'Semana','month'=>'Mês','year'=>'Ano'] as $k=>$v)
                        <option value="{{ $k }}" {{ ($filters['period'] ?? 'month')==$k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Data Início') }}</label>
                <input type="date" name="start_date" value="{{ $filters['start'] }}" class="form-control form-control-sm">
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Data Fim') }}</label>
                <input type="date" name="end_date" value="{{ $filters['end'] }}" class="form-control form-control-sm">
            </div>
            <div class="col-lg-3 col-md-4">
                <label class="form-label small text-muted mb-1">{{ __('Vendedor') }}</label>
                <select name="representative_id" class="form-select form-select-sm">
                    <option value="">{{ __('Todos') }}</option>
                    @foreach($representatives as $r)
                        <option value="{{ $r->id }}" {{ ($filters['representativeId'] ?? '') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-4">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="bx bx-filter-alt me-1"></i> {{ __('Filtrar') }}
                    </button>
                    <a href="{{ route('dashboard.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-refresh"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- KPI Cards - Row 1 --}}
<div class="row g-3 mb-4">
    {{-- Vendas Totais --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="avatar rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px; background: linear-gradient(135deg, #DE0802 0%, #B3211A 100%);">
                            <i class="bx bx-dollar fs-4 text-white"></i>
                        </div>
                        <h4 class="mb-1 fw-bold">
                            @if($canSeePrices) R$ {{ number_format($cards['salesTotal'],2,',','.') }} @else — @endif
                        </h4>
                        <p class="text-muted small mb-0">{{ __('Vendas Totais do Período') }}</p>
                    </div>
                    <span class="badge {{ $cards['salesTotalVar']>=0 ? 'bg-success' : 'bg-danger' }} rounded-pill">
                        <i class="bx {{ $cards['salesTotalVar']>=0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }} me-1"></i>{{ $cards['salesTotalVar'] }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Pedidos Abertos --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-receipt fs-4"></i>
                        </div>
                        <h4 class="mb-1 fw-bold">{{ $cards['openOrders'] }}</h4>
                        <p class="text-muted small mb-0">{{ __('Pedidos Abertos') }}</p>
                    </div>
                    <span class="badge {{ $cards['openOrdersVar']<=0 ? 'bg-success' : 'bg-danger' }} rounded-pill">
                        <i class="bx {{ $cards['openOrdersVar']<=0 ? 'bx-down-arrow-alt' : 'bx-up-arrow-alt' }} me-1"></i>{{ $cards['openOrdersVar'] }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Pedidos em Atraso --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="avatar rounded-circle bg-label-danger d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-time-five fs-4"></i>
                        </div>
                        <h4 class="mb-1 fw-bold">{{ $cards['delayedOrders'] }}</h4>
                        <p class="text-muted small mb-0">{{ __('Pedidos em Atraso') }}</p>
                    </div>
                    <span class="badge {{ $cards['delayedOrders']>0 ? 'bg-danger' : 'bg-success' }} rounded-pill">
                        <i class="bx {{ $cards['delayedOrders']>0 ? 'bx-up-arrow-alt' : 'bx-check' }} me-1"></i>{{ $cards['delayedOrdersVar'] }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Clientes Ativos --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-user-check fs-4"></i>
                        </div>
                        <h4 class="mb-1 fw-bold">{{ $cards['activeCustomers'] }}</h4>
                        <p class="text-muted small mb-0">{{ __('Clientes Ativos') }}</p>
                    </div>
                    <span class="badge bg-info rounded-pill">
                        <i class="bx bx-trending-up me-1"></i>{{ $cards['activeCustomersVar'] }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- KPI Cards - Row 2 --}}
<div class="row g-3 mb-4">
    {{-- Montagens Hoje --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-calendar-check fs-4"></i>
                        </div>
                        <h4 class="mb-1 fw-bold">{{ $cards['assembliesToday'] }}</h4>
                        <p class="text-muted small mb-0">{{ __('Montagens Programadas Hoje') }}</p>
                    </div>
                    <span class="badge {{ $cards['assembliesTodayVar']>=0 ? 'bg-success' : 'bg-warning' }} rounded-pill">
                        <i class="bx {{ $cards['assembliesTodayVar']>=0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }} me-1"></i>{{ $cards['assembliesTodayVar'] }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Veículos em Operação --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="avatar rounded-circle bg-label-secondary d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-car fs-4"></i>
                        </div>
                        <h4 class="mb-1 fw-bold">{{ $cards['vehiclesInOperation'] }}</h4>
                        <p class="text-muted small mb-0">{{ __('Veículos em Operação') }}</p>
                    </div>
                    <span class="badge {{ $cards['vehiclesInOperationVar']>=0 ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                        <i class="bx {{ $cards['vehiclesInOperationVar']>=0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }} me-1"></i>{{ $cards['vehiclesInOperationVar'] }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Custos Operacionais --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="avatar rounded-circle bg-label-dark d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-spa fs-4"></i>
                        </div>
                        <h4 class="mb-1 fw-bold">
                            @if($canSeePrices) R$ {{ number_format($cards['operationalCosts'],2,',','.') }} @else — @endif
                        </h4>
                        <p class="text-muted small mb-0">{{ __('Custos Operacionais') }}</p>
                    </div>
                    <span class="badge {{ $cards['operationalCostsVar']<=0 ? 'bg-success' : 'bg-danger' }} rounded-pill">
                        <i class="bx {{ $cards['operationalCostsVar']<=0 ? 'bx-down-arrow-alt' : 'bx-up-arrow-alt' }} me-1"></i>{{ $cards['operationalCostsVar'] }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- NPS --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="avatar rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px; background: linear-gradient(135deg, #DE0802 0%, #B3211A 100%);">
                            <i class="bx bx-smile fs-4 text-white"></i>
                        </div>
                        <h4 class="mb-1 fw-bold">{{ $cards['nps'] }}</h4>
                        <p class="text-muted small mb-0">{{ __('Índice de Satisfação (NPS)') }}</p>
                    </div>
                    <span class="badge {{ $cards['nps']>=7 ? 'bg-success' : 'bg-danger' }} rounded-pill">
                        <i class="bx {{ $cards['npsVar']>=0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }} me-1"></i>{{ $cards['npsVar'] }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row 1 --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-line-chart text-danger me-2"></i>{{ __('Vendas Mensais (Ano Atual vs Anterior)') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartMonthlyCompare" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-pie-chart-alt text-danger me-2"></i>{{ __('Status Geral da Produção') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartStatus" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row 2 --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-calendar text-danger me-2"></i>{{ __('Montagens por Dia da Semana (30 dias)') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartWeekday" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-car text-danger me-2"></i>{{ __('Utilização da Frota') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartFleet" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- Logistics Chart --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-map text-danger me-2"></i>{{ __('Desempenho Logístico') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartLog" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- Tables Row --}}
<div class="row g-3 mb-4">
    {{-- Recent Orders --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-cart text-danger me-2"></i>{{ __('Últimos Pedidos Criados') }}
                </h6>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary">{{ __('Ver todos') }}</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 px-4">{{ __('ID') }}</th>
                                <th class="border-0 py-3">{{ __('Cliente') }}</th>
                                <th class="border-0 py-3">{{ __('Valor') }}</th>
                                <th class="border-0 py-3">{{ __('Status') }}</th>
                                <th class="border-0 py-3">{{ __('Entrega') }}</th>
                                <th class="border-0 py-3 text-center">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $o)
                                <tr>
                                    <td class="py-3 px-4">
                                        <span class="fw-semibold">#{{ $o->id }}</span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                {{ strtoupper(substr($o->customer ? ($o->customer->customer_type == 'PF' ? $o->customer->full_name : $o->customer->company_name) : 'C', 0, 1)) }}
                                            </div>
                                            <span class="text-truncate" style="max-width: 120px;">{{ $o->customer ? ($o->customer->customer_type == 'PF' ? $o->customer->full_name : $o->customer->company_name) : '' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        @if($canSeePrices) 
                                            <span class="fw-semibold text-success">R$ {{ number_format((float)$o->grand_total,2,',','.') }}</span> 
                                        @else 
                                            <span class="text-muted">—</span> 
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        @php
                                            $status = is_object($o->order_status) ? $o->order_status->label() : $o->order_status;
                                            $statusClass = match(true) {
                                                str_contains(strtolower($status), 'cancel') => 'bg-danger',
                                                str_contains(strtolower($status), 'entreg') || str_contains(strtolower($status), 'conclu') => 'bg-success',
                                                str_contains(strtolower($status), 'atras') => 'bg-warning',
                                                default => 'bg-info'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }} rounded-pill">{{ $status }}</span>
                                    </td>
                                    <td class="py-3 text-muted small">
                                        {{ optional($o->expected_delivery_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 text-center">
                                        <a href="{{ route('sales.show',$o->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bx bx-show"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Next Assemblies --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-calendar text-danger me-2"></i>{{ __('Próximas Montagens') }}
                </h6>
                <a href="{{ route('assembly-schedules.all') }}" class="btn btn-sm btn-outline-primary">{{ __('Ver todas') }}</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 px-4">{{ __('Venda') }}</th>
                                <th class="border-0 py-3">{{ __('Cliente') }}</th>
                                <th class="border-0 py-3">{{ __('Endereço') }}</th>
                                <th class="border-0 py-3">{{ __('Montador(es)') }}</th>
                                <th class="border-0 py-3">{{ __('Data/Hora') }}</th>
                                <th class="border-0 py-3">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nextAssemblies as $a)
                                <tr>
                                    <td class="py-3 px-4">
                                        <span class="fw-semibold">#{{ $a->sale_id }}</span>
                                    </td>
                                    <td class="py-3">
                                        <span class="text-truncate" style="max-width: 100px;">{{ optional($a->sale->customer)->full_name ?? optional($a->sale->customer)->company_name }}</span>
                                    </td>
                                    <td class="py-3">
                                        @php $c = optional(optional($a->sale)->customer); @endphp
                                        <span class="text-muted small text-truncate d-block" style="max-width: 150px;">
                                            {{ trim(($c->address_city ?? '') . '/' . ($c->address_state ?? '')) }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        @if($a->assemblers)
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($a->assemblers->take(2) as $assembler)
                                                    <span class="badge bg-label-secondary rounded-pill">{{ $assembler->name }}</span>
                                                @endforeach
                                                @if($a->assemblers->count() > 2)
                                                    <span class="badge bg-secondary rounded-pill">+{{ $a->assemblers->count() - 2 }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <div>
                                            <span class="fw-semibold">{{ optional($a->scheduled_date)->format('d/m') }}</span>
                                        </div>
                                        <small class="text-muted">{{ optional($a->start_time)->format('H:i') }}</small>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-success rounded-pill">{{ __('Agendado') }}</span>
                                    </td>
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
// Bassani Color Palette for Charts
const bassaniRed = '#DE0802';
const bassaniRedDark = '#B3211A';
const bassaniNavy = '#1F2A44';
const bassaniGray = '#D2D4DA';

// Chart Theme
const chartTheme = {
    colors: [bassaniRed, bassaniNavy, bassaniRedDark, '#4a5568', '#718096', bassaniGray],
    chart: {
        toolbar: {
            show: true,
            tools: {
                download: true,
                selection: false,
                zoom: false,
                zoomin: false,
                zoomout: false,
                pan: false,
                reset: false
            }
        },
        fontFamily: 'Public Sans, sans-serif',
        background: 'transparent'
    },
    grid: {
        borderColor: '#f1f1f1',
        strokeDashArray: 3
    },
    dataLabels: {
        enabled: false
    },
    legend: {
        fontSize: '13px',
        fontWeight: 500,
        position: 'top'
    },
    plotOptions: {
        bar: {
            borderRadius: 4,
            columnWidth: '60%'
        }
    }
};

// Monthly Comparison
new ApexCharts(document.querySelector('#chartMonthlyCompare'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'area', height: 320 },
    series: [
        { name: 'Ano Atual', data: {!! json_encode(array_values($monthlyCurrentYear)) !!} },
        { name: 'Ano Anterior', data: {!! json_encode(array_values($monthlyPrevYear)) !!} }
    ],
    xaxis: { categories: {!! json_encode(array_keys($monthlyCurrentYear)) !!} },
    colors: [bassaniRed, bassaniGray],
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.4,
            opacityTo: 0.1,
            stops: [0, 90, 100]
        }
    },
    stroke: { curve: 'smooth', width: [3, 2] },
    tooltip: {
        y: { formatter: function(val) { return 'R$ ' + val.toLocaleString('pt-BR', {minimumFractionDigits: 2}); } }
    }
}).render();

// Status Distribution
new ApexCharts(document.querySelector('#chartStatus'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'donut', height: 320 },
    series: {!! json_encode(array_values($statusDist)) !!},
    labels: {!! json_encode(array_keys($statusDist)) !!},
    colors: [bassaniRed, bassaniNavy, '#1cc88a', '#f6c23e', bassaniRedDark, bassaniGray],
    plotOptions: { pie: { donut: { size: '65%' } } },
    legend: { position: 'bottom' }
}).render();

// Weekday Assemblies
new ApexCharts(document.querySelector('#chartWeekday'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 320 },
    series: [{ name: 'Montagens', data: {!! json_encode(array_values($weekdayCounts)) !!} }],
    xaxis: { categories: {!! json_encode(array_keys($weekdayCounts)) !!} },
    colors: [bassaniRed],
    plotOptions: { bar: { columnWidth: '50%' } }
}).render();

// Fleet Utilization
new ApexCharts(document.querySelector('#chartFleet'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'radialBar', height: 320 },
    series: [{!! json_encode($fleetUtilizationPct['ativos']) !!}, {!! json_encode($fleetUtilizationPct['manutencao']) !!}, {!! json_encode($fleetUtilizationPct['disponiveis']) !!}],
    labels: ['Ativos', 'Manutenção', 'Disponíveis'],
    colors: [bassaniRed, '#f6c23e', '#1cc88a'],
    plotOptions: {
        radialBar: {
            dataLabels: {
                name: { fontSize: '14px' },
                value: { fontSize: '20px', fontWeight: 600 }
            }
        }
    }
}).render();

// Logistics Performance
new ApexCharts(document.querySelector('#chartLog'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 300 },
    series: [{ name: 'Quantidade', data: [{!! json_encode($logPerf['on_time']) !!}, {!! json_encode($logPerf['late']) !!}, {!! json_encode($logPerf['pending']) !!}, {!! json_encode($logPerf['returns']) !!}] }],
    xaxis: { categories: ['No prazo', 'Atrasadas', 'Pendentes', 'Devoluções'] },
    colors: ['#1cc88a', '#e74a3b', '#f6c23e', bassaniGray],
    plotOptions: { bar: { columnWidth: '45%' } }
}).render();
</script>
@endsection
