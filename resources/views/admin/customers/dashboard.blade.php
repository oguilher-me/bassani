@extends('layouts/contentNavbarLayout')

@section('title', __('Dashboard de Clientes'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Dashboard de Clientes') }}</h4>
        <p class="text-muted mb-0">{{ __('Análise completa do seu relacionamento com clientes') }}</p>
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
        <form class="row g-3 align-items-end" method="GET" action="{{ route('customers.dashboard') }}">
            <div class="col-lg-2 col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Data Início') }}</label>
                <input type="date" name="start_date" value="{{ $filters['start'] }}" class="form-control form-control-sm">
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Data Fim') }}</label>
                <input type="date" name="end_date" value="{{ $filters['end'] }}" class="form-control form-control-sm">
            </div>
            <div class="col-lg-3 col-md-4">
                <label class="form-label small text-muted mb-1">{{ __('Cidade') }}</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bx bx-map"></i></span>
                    <input type="text" name="city" value="{{ $filters['city'] }}" class="form-control" placeholder="{{ __('Digite a cidade...') }}">
                </div>
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Tipo de Cliente') }}</label>
                <select name="customer_type" class="form-select form-select-sm">
                    <option value="">{{ __('Todos') }}</option>
                    <option value="PF" {{ ($filters['type'] ?? '')=='PF' ? 'selected' : '' }}>{{ __('Pessoa Física') }}</option>
                    <option value="PJ" {{ ($filters['type'] ?? '')=='PJ' ? 'selected' : '' }}>{{ __('Pessoa Jurídica') }}</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-4">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="bx bx-filter-alt me-1"></i> {{ __('Filtrar') }}
                    </button>
                    <a href="{{ route('customers.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-refresh"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- KPI Cards --}}
<div class="row g-3 mb-4">
    {{-- Total de Clientes --}}
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-sm overflow-hidden">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bx bx-group fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted small mb-1">{{ __('Total de clientes') }}</p>
                        <h4 class="mb-0 fw-bold">{{ number_format($cards['totalCustomers'], 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 p-2">
                    <i class="bx bx-group text-primary opacity-25" style="font-size: 4rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Clientes Ativos --}}
    <!-- <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-sm overflow-hidden">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar rounded-circle bg-label-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bx bx-user-check fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted small mb-1">{{ __('Clientes ativos') }}</p>
                        <h4 class="mb-0 fw-bold">{{ number_format($cards['activeCustomers'], 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 p-2">
                    <i class="bx bx-user-check text-success opacity-25" style="font-size: 4rem;"></i>
                </div>
            </div>
        </div>
    </div> -->
    
    {{-- Clientes Inativos --}}
    <!-- <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-sm overflow-hidden">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar rounded-circle bg-label-secondary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bx bx-user-minus fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted small mb-1">{{ __('Clientes inativos') }}</p>
                        <h4 class="mb-0 fw-bold">{{ number_format($cards['inactiveCustomers'], 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 p-2">
                    <i class="bx bx-user-minus text-secondary opacity-25" style="font-size: 4rem;"></i>
                </div>
            </div>
        </div>
    </div> -->
    
    {{-- Novos Clientes --}}
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-sm overflow-hidden">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #DE0802 0%, #B3211A 100%);">
                            <i class="bx bx-user-plus fs-4 text-white"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted small mb-1">{{ __('Novos (mês)') }}</p>
                        <h4 class="mb-0 fw-bold">{{ number_format($cards['newCustomersMonth'], 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 p-2">
                    <i class="bx bx-user-plus text-danger opacity-25" style="font-size: 4rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Ticket Médio --}}
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-sm overflow-hidden">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bx bx-purchase-tag fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted small mb-1">{{ __('Ticket médio') }}</p>
                        <h4 class="mb-0 fw-bold">R$ {{ number_format($cards['ticketMedio'], 2, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 p-2">
                    <i class="bx bx-purchase-tag text-warning opacity-25" style="font-size: 4rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Retenção --}}
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-sm overflow-hidden">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bx bx-refresh fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted small mb-1">{{ __('Taxa de retenção') }}</p>
                        <h4 class="mb-0 fw-bold">{{ $cards['retentionRate'] }}%</h4>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 p-2">
                    <i class="bx bx-refresh text-info opacity-25" style="font-size: 4rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- NPS & Complaints Row --}}
<div class="row g-3 mb-4">
    {{-- NPS Geral --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px;">
                        <i class="bx bx-smile fs-3"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $cards['npsGeral'] }}</h3>
                <p class="text-muted small mb-0">{{ __('NPS Geral') }}</p>
                <div class="mt-2">
                    <span class="badge {{ $cards['npsGeral'] >= 50 ? 'bg-success' : ($cards['npsGeral'] >= 0 ? 'bg-warning' : 'bg-danger') }} rounded-pill">
                        {{ $cards['npsGeral'] >= 50 ? __('Excelente') : ($cards['npsGeral'] >= 0 ? __('Regular') : __('Precisa melhorar') ) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- NPS Montagem --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="avatar rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px; background: linear-gradient(135deg, #DE0802 0%, #B3211A 100%);">
                        <i class="bx bx-wrench fs-3 text-white"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $cards['npsMontagem'] }}</h3>
                <p class="text-muted small mb-0">{{ __('NPS Pós-montagem') }}</p>
                <div class="mt-2">
                    <span class="badge {{ $cards['npsMontagem'] >= 50 ? 'bg-success' : ($cards['npsMontagem'] >= 0 ? 'bg-warning' : 'bg-danger') }} rounded-pill">
                        {{ $cards['npsMontagem'] >= 50 ? __('Excelente') : ($cards['npsMontagem'] >= 0 ? __('Regular') : __('Precisa melhorar') ) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Reclamações --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="avatar rounded-circle bg-label-danger d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px;">
                        <i class="bx bx-message-error fs-3"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $cards['complaintsCount'] }}</h3>
                <p class="text-muted small mb-0">{{ __('Reclamações (NPS ≤ 6)') }}</p>
                <div class="mt-2">
                    <span class="badge {{ $cards['complaintsCount'] == 0 ? 'bg-success' : ($cards['complaintsCount'] <= 5 ? 'bg-warning' : 'bg-danger') }} rounded-pill">
                        {{ $cards['complaintsCount'] == 0 ? __('Nenhuma') : ($cards['complaintsCount'] <= 5 ? __('Poucas') : __('Muitas') ) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Churn --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px;">
                        <i class="bx bx-trending-down fs-3"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $cards['churnRate'] }}%</h3>
                <p class="text-muted small mb-0">{{ __('Taxa de Churn') }}</p>
                <div class="mt-2">
                    <span class="badge {{ $cards['churnRate'] <= 5 ? 'bg-success' : ($cards['churnRate'] <= 15 ? 'bg-warning' : 'bg-danger') }} rounded-pill">
                        {{ $cards['churnRate'] <= 5 ? __('Baixa') : ($cards['churnRate'] <= 15 ? __('Média') : __('Alta') ) }}
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
                    <i class="bx bx-map-pin text-danger me-2"></i>{{ __('Distribuição Geográfica (Top 10)') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartGeo" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-user text-danger me-2"></i>{{ __('Tipos de Clientes') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartTypes" style="min-height: 320px;"></div>
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
                    <i class="bx bx-bar-chart-alt text-danger me-2"></i>{{ __('Faixa de Gasto') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartSpendBuckets" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-trophy text-danger me-2"></i>{{ __('Top 10 Clientes por Faturamento') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartTopCustomers" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row 3 --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-line-chart text-danger me-2"></i>{{ __('Evolução do Faturamento (12 meses)') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartMonthlyRevenue" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-shopping-bag text-danger me-2"></i>{{ __('Vendas por Canal') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartSalesDivision" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row 4 & Comments --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-pie-chart-alt text-danger me-2"></i>{{ __('Distribuição NPS') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartNpsDist" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-comment-detail text-danger me-2"></i>{{ __('Comentários Recentes') }}
                </h6>
                <span class="badge bg-label-primary">{{ count($evals) }}</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" style="max-height: 350px; overflow-y: auto;">
                    @forelse($evals as $e)
                        <div class="list-group-item border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div>
                                    <span class="badge {{ $e->nps_score >= 9 ? 'bg-success' : ($e->nps_score >= 7 ? 'bg-warning' : 'bg-danger') }} rounded-pill me-2">
                                        NPS: {{ $e->nps_score }}
                                    </span>
                                    <span class="badge {{ $e->nps_score >= 9 ? 'bg-success' : ($e->nps_score >= 7 ? 'bg-warning' : 'bg-danger') }} rounded-pill">
                                        {{ $e->nps_score >= 9 ? __('Promotor') : ($e->nps_score >= 7 ? __('Neutro') : __('Detrator') ) }}
                                    </span>
                                </div>
                                <small class="text-muted">{{ optional($e->submitted_at)->format('d/m/Y') }}</small>
                            </div>
                            @if($e->comments)
                                <p class="mb-0 text-muted small">{{ Str::limit($e->comments, 150) }}</p>
                            @else
                                <p class="mb-0 text-muted small fst-italic">{{ __('Sem comentários') }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bx bx-message-rounded-dots fs-1 text-muted"></i>
                            <p class="text-muted mt-2">{{ __('Nenhum comentário encontrado') }}</p>
                        </div>
                    @endforelse
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
    colors: [bassaniRed, bassaniRedDark, bassaniNavy, '#4a5568', '#718096', bassaniGray],
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
        fontWeight: 500
    },
    plotOptions: {
        bar: {
            borderRadius: 4,
            columnWidth: '60%'
        }
    }
};

// Geographic Distribution
new ApexCharts(document.querySelector('#chartGeo'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 320 },
    series: [{ name: 'Clientes', data: {!! json_encode(array_values($geoDistribution)) !!} }],
    xaxis: { 
        categories: {!! json_encode(array_keys($geoDistribution)) !!},
        labels: { style: { fontSize: '11px' } }
    },
    colors: [bassaniRed],
    plotOptions: { bar: { ...chartTheme.plotOptions.bar, horizontal: true } }
}).render();

// Customer Types
new ApexCharts(document.querySelector('#chartTypes'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'donut', height: 320 },
    series: {!! json_encode(array_values($typeDistribution)) !!},
    labels: {!! json_encode(array_keys($typeDistribution)) !!},
    colors: [bassaniRed, bassaniNavy, bassaniGray],
    plotOptions: { pie: { donut: { size: '65%' } } },
    legend: { position: 'bottom' }
}).render();

// Spend Buckets
new ApexCharts(document.querySelector('#chartSpendBuckets'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 320 },
    series: [{ name: 'Clientes', data: {!! json_encode(array_values($spendBuckets)) !!} }],
    xaxis: { categories: {!! json_encode(array_keys($spendBuckets)) !!} },
    colors: [bassaniNavy],
    plotOptions: { bar: { columnWidth: '50%' } }
}).render();

// Top Customers
new ApexCharts(document.querySelector('#chartTopCustomers'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 320 },
    series: [{ name: 'Faturamento', data: {!! json_encode(array_map(function($x){ return $x['total']; }, $topCustomers)) !!} }],
    xaxis: { 
        categories: {!! json_encode(array_map(function($x){ return Str::limit($x['name'], 15); }, $topCustomers)) !!},
        labels: { style: { fontSize: '10px' }, rotate: -45 }
    },
    colors: [bassaniRed],
    plotOptions: { bar: { columnWidth: '50%' } },
    tooltip: {
        y: { formatter: function(val) { return 'R$ ' + val.toLocaleString('pt-BR', {minimumFractionDigits: 2}); } }
    }
}).render();

// Monthly Revenue
new ApexCharts(document.querySelector('#chartMonthlyRevenue'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'area', height: 320 },
    series: [{ name: 'Faturamento', data: {!! json_encode(array_values($monthlyRevenue)) !!} }],
    xaxis: { categories: {!! json_encode(array_keys($monthlyRevenue)) !!} },
    colors: [bassaniRed, bassaniRedDark],
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.4,
            opacityTo: 0.1,
            stops: [0, 90, 100]
        }
    },
    stroke: { curve: 'smooth', width: 2 },
    tooltip: {
        y: { formatter: function(val) { return 'R$ ' + val.toLocaleString('pt-BR', {minimumFractionDigits: 2}); } }
    }
}).render();

// Sales Division
new ApexCharts(document.querySelector('#chartSalesDivision'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'donut', height: 320 },
    series: {!! json_encode(array_values($salesDivision)) !!},
    labels: {!! json_encode(array_keys($salesDivision)) !!},
    colors: [bassaniRed, bassaniNavy, bassaniGray, bassaniRedDark],
    plotOptions: { pie: { donut: { size: '60%' } } },
    legend: { position: 'bottom' }
}).render();

// NPS Distribution
new ApexCharts(document.querySelector('#chartNpsDist'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'pie', height: 320 },
    series: [{{ (int)$promoters }}, {{ (int)$passives }}, {{ (int)$detractors }}],
    labels: ['Promotores', 'Passivos', 'Detratores'],
    colors: ['#1cc88a', '#f6c23e', '#e74a3b'],
    legend: { position: 'bottom' }
}).render();
</script>
@endsection
