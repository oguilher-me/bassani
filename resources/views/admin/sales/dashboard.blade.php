@extends('layouts/contentNavbarLayout')

@section('title', __('Dashboard de Vendas'))

@section('content')
@php $canSeePrices = Auth::check() && Auth::user()->role_id == 1; @endphp

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Dashboard de Vendas') }}</h4>
        <p class="text-muted mb-0">{{ __('Análise completa do desempenho comercial') }}</p>
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
        <form class="row g-3 align-items-end" method="GET" action="{{ route('sales.dashboard') }}">
            <div class="col-lg-2 col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Data Início') }}</label>
                <input type="date" name="start_date" value="{{ $filters['start'] }}" class="form-control form-control-sm">
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Data Fim') }}</label>
                <input type="date" name="end_date" value="{{ $filters['end'] }}" class="form-control form-control-sm">
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Vendedor') }}</label>
                <select name="representative_id" class="form-select form-select-sm">
                    <option value="">{{ __('Todos') }}</option>
                    @foreach($representatives as $r)
                        <option value="{{ $r->id }}" {{ ($filters['representativeId'] ?? '') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Canal de venda') }}</label>
                <input type="text" name="sales_division" value="{{ $filters['division'] }}" class="form-control form-control-sm" placeholder="Ex.: Loja, Online">
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Status') }}</label>
                <select name="order_status" class="form-select form-select-sm">
                    <option value="">{{ __('Todos') }}</option>
                    @foreach(['Open','In Production','In Transit','Delivered','In Assembly','Completed','Cancelled'] as $st)
                        <option value="{{ $st }}" {{ ($filters['status'] ?? '') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="bx bx-filter-alt me-1"></i> {{ __('Filtrar') }}
                    </button>
                    <a href="{{ route('sales.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-refresh"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- KPI Cards --}}
<div class="row g-3 mb-4">
    {{-- Total de Pedidos --}}
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bx bx-receipt fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('Pedidos') }}</p>
                        <h4 class="mb-0 fw-bold">{{ number_format($cards['totalOrders'], 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Pedidos em Atraso --}}
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bx bx-time-five fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('Pedidos em atraso') }}</p>
                        <h4 class="mb-0 fw-bold">{{ $cards['lateDeliveries'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Taxa de Conversão --}}
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bx bx-target-lock fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('Taxa de conversão') }}</p>
                        <h4 class="mb-0 fw-bold">{{ $cards['conversionRate'] }}%</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Faturamento --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #DE0802 0%, #B3211A 100%);">
                        <i class="bx bx-dollar fs-4 text-white"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('Faturamento no período') }}</p>
                        <h4 class="mb-0 fw-bold">
                            @if($canSeePrices) R$ {{ number_format($cards['totalRevenue'],2,',','.') }} @else — @endif
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Ticket Médio --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bx bx-purchase-tag fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('Ticket médio') }}</p>
                        <h4 class="mb-0 fw-bold">
                            @if($canSeePrices) R$ {{ number_format($cards['avgTicket'],2,',','.') }} @else — @endif
                        </h4>
                    </div>
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
                    <i class="bx bx-filter text-danger me-2"></i>{{ __('Funil por Status') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartStatus" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-line-chart text-danger me-2"></i>{{ __('Faturamento Mensal (12 meses)') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartMonthlyRevenue" style="min-height: 320px;"></div>
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
                    <i class="bx bx-shopping-bag text-danger me-2"></i>{{ __('Vendas por Canal') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartDivision" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-trophy text-danger me-2"></i>{{ __('Top Vendedores por Faturamento') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartTopReps" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- Top Products Chart --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-package text-danger me-2"></i>{{ __('Top Produtos') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartTopProducts" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Orders Table --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-cart text-danger me-2"></i>{{ __('Últimos Pedidos') }}
                </h6>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary">{{ __('Ver todos') }}</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 px-4">{{ __('ID') }}</th>
                                <th class="border-0 py-3">{{ __('Data') }}</th>
                                <th class="border-0 py-3">{{ __('Cliente') }}</th>
                                <th class="border-0 py-3">{{ __('Vendedor') }}</th>
                                <th class="border-0 py-3">{{ __('Status') }}</th>
                                <th class="border-0 py-3">{{ __('Total') }}</th>
                                <th class="border-0 py-3 text-center">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $o)
                                <tr>
                                    <td class="py-3 px-4">
                                        <span class="fw-semibold">#{{ $o->id }}</span>
                                    </td>
                                    <td class="py-3 text-muted small">
                                        {{ optional($o->issue_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                {{ strtoupper(substr($o->customer ? ($o->customer->customer_type == 'PF' ? $o->customer->full_name : $o->customer->company_name) : 'C', 0, 1)) }}
                                            </div>
                                            <span class="text-truncate" style="max-width: 150px;">{{ $o->customer ? ($o->customer->customer_type == 'PF' ? $o->customer->full_name : $o->customer->company_name) : '' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar rounded-circle bg-label-secondary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                {{ strtoupper(substr($o->representative ? $o->representative->name : 'V', 0, 1)) }}
                                            </div>
                                            <span class="text-truncate" style="max-width: 100px;">{{ $o->representative ? $o->representative->name : '' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        @php
                                            $status = is_object($o->order_status) ? $o->order_status->label() : $o->order_status;
                                            $statusClass = match(true) {
                                                str_contains(strtolower($status), 'cancel') => 'bg-danger',
                                                str_contains(strtolower($status), 'entreg') || str_contains(strtolower($status), 'complet') => 'bg-success',
                                                str_contains(strtolower($status), 'atras') || str_contains(strtolower($status), 'transit') => 'bg-warning',
                                                str_contains(strtolower($status), 'production') || str_contains(strtolower($status), 'assembly') => 'bg-info',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }} rounded-pill">{{ $status }}</span>
                                    </td>
                                    <td class="py-3">
                                        @if($canSeePrices) 
                                            <span class="fw-semibold text-success">R$ {{ number_format((float)$o->grand_total,2,',','.') }}</span> 
                                        @else 
                                            <span class="text-muted">—</span> 
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        <a href="{{ route('sales.show', $o->id) }}" class="btn btn-sm btn-outline-primary">
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
        position: 'bottom'
    },
    plotOptions: {
        bar: {
            borderRadius: 4,
            columnWidth: '60%'
        }
    }
};

// Status Funnel
const statusDist = {!! json_encode($statusDist) !!};
new ApexCharts(document.querySelector('#chartStatus'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 320 },
    series: [{ name: 'Pedidos', data: Object.values(statusDist) }],
    xaxis: { categories: Object.keys(statusDist) },
    colors: [bassaniNavy],
    plotOptions: { bar: { columnWidth: '50%' } }
}).render();

// Monthly Revenue
const monthlyRev = {!! json_encode(array_values($monthlyRevenue)) !!};
const monthlyLabels = {!! json_encode(array_keys($monthlyRevenue)) !!};
new ApexCharts(document.querySelector('#chartMonthlyRevenue'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'area', height: 320 },
    series: [{ name: 'Faturamento', data: monthlyRev }],
    xaxis: { categories: monthlyLabels },
    colors: [bassaniRed],
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.4,
            opacityTo: 0.1,
            stops: [0, 90, 100]
        }
    },
    stroke: { curve: 'smooth', width: 3 },
    tooltip: {
        y: { formatter: function(val) { return 'R$ ' + val.toLocaleString('pt-BR', {minimumFractionDigits: 2}); } }
    }
}).render();

// Sales by Division
const divisionDist = {!! json_encode($divisionDist) !!};
new ApexCharts(document.querySelector('#chartDivision'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'donut', height: 320 },
    series: Object.values(divisionDist),
    labels: Object.keys(divisionDist),
    colors: [bassaniRed, bassaniNavy, bassaniRedDark, bassaniGray, '#1cc88a'],
    plotOptions: { pie: { donut: { size: '65%' } } }
}).render();

// Top Representatives
const topRepsData = {!! json_encode($topRepresentatives) !!};
new ApexCharts(document.querySelector('#chartTopReps'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 320 },
    series: [{ name: 'Faturamento', data: topRepsData.map(x => x.total) }],
    xaxis: { 
        categories: topRepsData.map(x => x.name),
        labels: { style: { fontSize: '11px' } }
    },
    colors: [bassaniRed],
    plotOptions: { bar: { columnWidth: '50%' } },
    tooltip: {
        y: { formatter: function(val) { return 'R$ ' + val.toLocaleString('pt-BR', {minimumFractionDigits: 2}); } }
    }
}).render();

// Top Products
const topProductsData = {!! json_encode($topProducts) !!};
new ApexCharts(document.querySelector('#chartTopProducts'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 300 },
    series: [{ name: 'Faturamento', data: topProductsData.map(x => x.total) }],
    xaxis: { 
        categories: topProductsData.map(x => x.name),
        labels: { style: { fontSize: '10px' }, rotate: -45 }
    },
    colors: [bassaniNavy],
    plotOptions: { bar: { columnWidth: '45%' } },
    tooltip: {
        y: { formatter: function(val) { return 'R$ ' + val.toLocaleString('pt-BR', {minimumFractionDigits: 2}); } }
    }
}).render();
</script>
@endsection
