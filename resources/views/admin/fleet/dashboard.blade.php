@extends('layouts/contentNavbarLayout')

@section('title', __('Dashboard de Frota'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Dashboard de Frota') }}</h4>
        <p class="text-muted mb-0">{{ __('Gestão completa da sua frota de veículos') }}</p>
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
        <form class="row g-3 align-items-end" method="GET" action="{{ route('fleet.dashboard') }}">
            <div class="col-lg-2 col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Data Início') }}</label>
                <input type="date" name="start_date" value="{{ $filters['start'] }}" class="form-control form-control-sm">
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Data Fim') }}</label>
                <input type="date" name="end_date" value="{{ $filters['end'] }}" class="form-control form-control-sm">
            </div>
            <div class="col-lg-3 col-md-4">
                <label class="form-label small text-muted mb-1">{{ __('Veículo') }}</label>
                <select name="vehicle_id" class="form-select form-select-sm">
                    <option value="">{{ __('Todos') }}</option>
                    @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" {{ ($filters['vehicleId'] ?? null)==$v->id ? 'selected' : '' }}>{{ $v->placa }} - {{ $v->modelo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-4">
                <label class="form-label small text-muted mb-1">{{ __('Motorista') }}</label>
                <select name="driver_id" class="form-select form-select-sm">
                    <option value="">{{ __('Todos') }}</option>
                    @foreach($drivers as $d)
                        <option value="{{ $d->id }}" {{ ($filters['driverId'] ?? null)==$d->id ? 'selected' : '' }}>{{ $d->full_name ?? $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="bx bx-filter-alt me-1"></i> {{ __('Filtrar') }}
                    </button>
                    <a href="{{ route('fleet.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-refresh"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- KPI Cards --}}
<div class="row g-3 mb-4">
    {{-- Total de Veículos --}}
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #DE0802 0%, #B3211A 100%);">
                        <i class="bx bx-car fs-4 text-white"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('Total de veículos') }}</p>
                        <h4 class="mb-0 fw-bold">{{ $cards['totalVehicles'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Manutenções Pendentes --}}
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bx bx-wrench fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('Manutenções pendentes') }}</p>
                        <h4 class="mb-0 fw-bold">{{ $cards['pendingMaintenances'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Disponíveis --}}
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bx bx-check-circle fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('Disponíveis') }}</p>
                        <h4 class="mb-0 fw-bold">{{ $cards['availableVehicles'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- KM Rodado/Mês --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bx bx-stats fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('KM Rodado/Mês') }}</p>
                        <h4 class="mb-0 fw-bold">{{ number_format($cards['kmMonth'], 0, ',', '.') }} km</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Custo Frota/Mês --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bx bx-money fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('Custo Frota/Mês') }}</p>
                        <h4 class="mb-0 fw-bold">R$ {{ number_format($cards['fleetCostMonth'], 0, ',', '.') }}</h4>
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
                    <i class="bx bx-bar-chart-alt text-danger me-2"></i>{{ __('Custo Mensal por Categoria') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartMonthlyCosts" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-gas-pump text-danger me-2"></i>{{ __('Consumo Médio por Veículo') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartConsumptionByVehicle" style="min-height: 320px;"></div>
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
                    <i class="bx bx-user text-danger me-2"></i>{{ __('Performance dos Motoristas') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartDriversPerformance" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-dollar-circle text-danger me-2"></i>{{ __('Custo por KM') }}
                </h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div id="chartCostPerKm" style="min-height: 320px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

{{-- Health Alerts --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-error-circle text-danger me-2"></i>{{ __('Alertas de Saúde da Frota') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    {{-- Manutenções Vencidas --}}
                    <div class="col-lg-4">
                        <div class="card border-0 bg-danger bg-opacity-10 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar rounded-circle bg-label-danger d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                        <i class="bx bx-error"></i>
                                    </div>
                                    <h6 class="mb-0 text-danger">{{ __('Manutenções Vencidas') }}</h6>
                                    <span class="badge bg-danger ms-auto">{{ count($healthAlerts['overdue']) }}</span>
                                </div>
                                @if(count($healthAlerts['overdue']) > 0)
                                    <div class="list-group list-group-flush" style="max-height: 200px; overflow-y: auto;">
                                        @foreach($healthAlerts['overdue'] as $m)
                                            <div class="list-group-item bg-transparent border-0 px-0 py-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="fw-semibold">{{ optional($m->vehicle)->placa }}</span>
                                                        <small class="text-muted d-block">{{ optional($m->vehicle)->modelo }}</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <small class="text-danger">{{ optional($m->maintenance_date)->format('d/m/Y') }}</small>
                                                        <small class="text-muted d-block">R$ {{ number_format($m->cost ?? 0, 2, ',', '.') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="bx bx-check-circle fs-1 text-success"></i>
                                        <p class="text-muted mb-0 mt-2">{{ __('Nenhuma manutenção vencida') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- Revisões por KM --}}
                    <div class="col-lg-4">
                        <div class="card border-0 bg-warning bg-opacity-10 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                        <i class="bx bx-wrench"></i>
                                    </div>
                                    <h6 class="mb-0 text-warning">{{ __('Revisões por KM Próximas') }}</h6>
                                    <span class="badge bg-warning ms-auto">{{ count($healthAlerts['upcomingMileage']) }}</span>
                                </div>
                                @if(count($healthAlerts['upcomingMileage']) > 0)
                                    <div class="list-group list-group-flush" style="max-height: 200px; overflow-y: auto;">
                                        @foreach($healthAlerts['upcomingMileage'] as $v)
                                            <div class="list-group-item bg-transparent border-0 px-0 py-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="fw-semibold">{{ $v->placa }}</span>
                                                        <small class="text-muted d-block">{{ $v->modelo }}</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <small class="text-warning fw-semibold">{{ number_format($v->next_preventive_maintenance_mileage ?? 0, 0, ',', '.') }} KM</small>
                                                        <small class="text-muted d-block">{{ __('Próx. preventiva') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="bx bx-check-circle fs-1 text-success"></i>
                                        <p class="text-muted mb-0 mt-2">{{ __('Nenhuma revisão próxima') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- Documentação a Vencer --}}
                    <div class="col-lg-4">
                        <div class="card border-0 bg-info bg-opacity-10 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                        <i class="bx bx-file"></i>
                                    </div>
                                    <h6 class="mb-0 text-info">{{ __('Documentação a Vencer') }}</h6>
                                    <span class="badge bg-info ms-auto">{{ count($healthAlerts['docsDue']) }}</span>
                                </div>
                                @if(count($healthAlerts['docsDue']) > 0)
                                    <div class="list-group list-group-flush" style="max-height: 200px; overflow-y: auto;">
                                        @foreach($healthAlerts['docsDue'] as $v)
                                            <div class="list-group-item bg-transparent border-0 px-0 py-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="fw-semibold">{{ $v->placa }}</span>
                                                        <small class="text-muted d-block">{{ $v->modelo }}</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <small class="text-info">{{ __('Licenciamento') }}: {{ optional($v->licensing_due_date)->format('d/m/Y') }}</small>
                                                        <small class="text-muted d-block">{{ __('Seguro') }}: {{ optional($v->insurance_due_date)->format('d/m/Y') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="bx bx-check-circle fs-1 text-success"></i>
                                        <p class="text-muted mb-0 mt-2">{{ __('Documentação em dia') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
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
    colors: [bassaniRed, bassaniNavy, bassaniRedDark, '#f6c23e', '#1cc88a', bassaniGray],
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

// Monthly Costs (Stacked Bar)
const mcLabels = {!! json_encode($monthlyCosts['labels']) !!};
const mcFuel = {!! json_encode($monthlyCosts['fuel']) !!};
const mcMaint = {!! json_encode($monthlyCosts['maint']) !!};
const mcFines = {!! json_encode($monthlyCosts['fines']) !!};
new ApexCharts(document.querySelector('#chartMonthlyCosts'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 320, stacked: true },
    series: [
        { name: 'Combustível', data: mcFuel },
        { name: 'Manutenção', data: mcMaint },
        { name: 'Multas', data: mcFines }
    ],
    xaxis: { categories: mcLabels },
    colors: [bassaniRed, bassaniNavy, '#f6c23e'],
    plotOptions: { bar: { columnWidth: '60%' } },
    tooltip: {
        y: { formatter: function(val) { return 'R$ ' + val.toLocaleString('pt-BR', {minimumFractionDigits: 2}); } }
    }
}).render();

// Consumption by Vehicle
const consSeries = [{ name: 'Km/L', data: {!! json_encode(array_map(function($x){ return round($x['avg'],2); }, $consumptionByVehicle)) !!} }];
const consLabels = {!! json_encode(array_map(function($x){ return $x['name']; }, $consumptionByVehicle)) !!};
new ApexCharts(document.querySelector('#chartConsumptionByVehicle'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 320 },
    series: consSeries,
    xaxis: { 
        categories: consLabels,
        labels: { style: { fontSize: '11px' } }
    },
    colors: [bassaniRed],
    plotOptions: { bar: { columnWidth: '50%' } }
}).render();

// Drivers Performance
const dpNames = {!! json_encode(array_map(function($x){ return $x['name']; }, $driversPerformance)) !!};
const dpHours = {!! json_encode(array_map(function($x){ return $x['hours']; }, $driversPerformance)) !!};
const dpKms = {!! json_encode(array_map(function($x){ return $x['kms']; }, $driversPerformance)) !!};
const dpFines = {!! json_encode(array_map(function($x){ return $x['fines']; }, $driversPerformance)) !!};
new ApexCharts(document.querySelector('#chartDriversPerformance'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'line', height: 320 },
    series: [
        { name: 'Horas', data: dpHours },
        { name: 'KM', data: dpKms },
        { name: 'Multas', data: dpFines }
    ],
    xaxis: { categories: dpNames },
    colors: [bassaniRed, bassaniNavy, '#f6c23e'],
    stroke: { curve: 'smooth', width: [3, 3, 2] }
}).render();

// Cost per KM (Radial)
new ApexCharts(document.querySelector('#chartCostPerKm'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'radialBar', height: 320 },
    series: [{{ $cards['avgCostPerKm'] }}],
    labels: ['R$/KM'],
    colors: [bassaniRed],
    plotOptions: {
        radialBar: {
            hollow: { size: '70%' },
            dataLabels: {
                name: { fontSize: '16px', color: bassaniNavy },
                value: { fontSize: '28px', fontWeight: 600, formatter: function(val) { return 'R$ ' + val.toFixed(2); } }
            }
        }
    }
}).render();
</script>
@endsection
