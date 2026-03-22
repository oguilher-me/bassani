@extends('layouts/contentNavbarLayout')

@section('title', __('Dashboard de Frota'))

@section('content')
<div class="row mb-3">
  <div class="col-12">
    <form class="row g-2" method="GET" action="{{ route('fleet.dashboard') }}">
      <div class="col-md-2">
        <label class="form-label">{{ __('Início') }}</label>
        <input type="date" name="start_date" value="{{ $filters['start'] }}" class="form-control">
      </div>
      <div class="col-md-2">
        <label class="form-label">{{ __('Fim') }}</label>
        <input type="date" name="end_date" value="{{ $filters['end'] }}" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">{{ __('Veículo') }}</label>
        <select name="vehicle_id" class="form-select">
          <option value="">{{ __('Todos') }}</option>
          @foreach($vehicles as $v)
            <option value="{{ $v->id }}" {{ ($filters['vehicleId'] ?? null)==$v->id ? 'selected' : '' }}>{{ $v->placa }} - {{ $v->modelo }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">{{ __('Motorista') }}</label>
        <select name="driver_id" class="form-select">
          <option value="">{{ __('Todos') }}</option>
          @foreach($drivers as $d)
            <option value="{{ $d->id }}" {{ ($filters['driverId'] ?? null)==$d->id ? 'selected' : '' }}>{{ $d->full_name ?? $d->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">{{ __('Filtrar') }}</button>
      </div>
    </form>
  </div>
</div>

<div class="row mb-4">
  <div class="col-lg-2 col-sm-6">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-4">
            <span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bxs-truck icon-lg"></i></span>
          </div>
          <h4 class="mb-0">{{ $cards['totalVehicles'] }}</h4>
        </div>
        <p class="mb-2">{{ __('Total de veículos') }}</p>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-sm-6">
    <div class="card card-border-shadow-warning h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-4">
            <span class="avatar-initial rounded bg-label-warning"><i class="icon-base bx bx-wrench icon-lg"></i></span>
          </div>
          <h4 class="mb-0">{{ $cards['pendingMaintenances'] }}</h4>
        </div>
        <p class="mb-2">{{ __('Manutenções pendentes') }}</p>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-sm-6">
    <div class="card card-border-shadow-success h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-4">
            <span class="avatar-initial rounded bg-label-success"><i class="icon-base bx bx-check-circle icon-lg"></i></span>
          </div>
          <h4 class="mb-0">{{ $cards['availableVehicles'] }}</h4>
        </div>
        <p class="mb-2">{{ __('Disponíveis') }}</p>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-sm-6">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-4">
            <span class="avatar-initial rounded bg-label-info"><i class="icon-base bx bx-color icon-lg"></i></span>
          </div>
          <h4 class="mb-0">{{ number_format($cards['kmMonth'], 0, ',', '.') }}</h4>
        </div>
        <p class="mb-2">{{ __('KM Rodado/Mês') }}</p>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-sm-6">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-4">
            <span class="avatar-initial rounded bg-label-info"><i class="icon-base bx bx-money-withdraw icon-lg"></i></span>
          </div>
          <h4 class="mb-0">R$ {{ number_format($cards['fleetCostMonth'], 0, ',', '.') }}</h4>
        </div>
        <p class="mb-2">{{ __('Custo Frota (R$)/Mês') }}</p>
      </div>
    </div>
  </div>
  
</div>

<div class="row mb-4">
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Custo mensal por categoria') }}</div><div class="card-body"><div id="chartMonthlyCosts"></div></div></div></div>
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Consumo médio por veículo') }}</div><div class="card-body"><div id="chartConsumptionByVehicle"></div></div></div></div>
</div>

<div class="row mb-4">
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Performance dos motoristas') }}</div><div class="card-body"><div id="chartDriversPerformance"></div></div></div></div>
  <div class="col-md-6"><div class="card"><div class="card-header">{{ __('Custo por KM') }}</div><div class="card-body"><div id="chartCostPerKm"></div></div></div></div>
</div>

<div class="row mb-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">{{ __('Alertas de Saúde da Frota') }}</div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <h6 class="mb-2">{{ __('Manutenções vencidas') }}</h6>
            <ul class="list-unstyled">
              @foreach($healthAlerts['overdue'] as $m)
                <li class="mb-1">{{ optional($m->vehicle)->placa }} - {{ optional($m->vehicle)->modelo }} | {{ optional($m->maintenance_date)->format('d/m/Y') }} | R$ {{ number_format($m->cost ?? 0, 2, ',', '.') }}</li>
              @endforeach
            </ul>
          </div>
          <div class="col-md-4">
            <h6 class="mb-2">{{ __('Revisões por KM próximas') }}</h6>
            <ul class="list-unstyled">
              @foreach($healthAlerts['upcomingMileage'] as $v)
                <li class="mb-1">{{ $v->placa }} - {{ $v->modelo }} | {{ __('Próx. preventiva') }}: {{ number_format($v->next_preventive_maintenance_mileage ?? 0, 0, ',', '.') }} KM</li>
              @endforeach
            </ul>
          </div>
          <div class="col-md-4">
            <h6 class="mb-2">{{ __('Documentação a vencer') }}</h6>
            <ul class="list-unstyled">
              @foreach($healthAlerts['docsDue'] as $v)
                <li class="mb-1">{{ $v->placa }} - {{ $v->modelo }} | {{ __('Licenciamento') }}: {{ optional($v->licensing_due_date)->format('d/m/Y') }} | {{ __('Seguro') }}: {{ optional($v->insurance_due_date)->format('d/m/Y') }}</li>
              @endforeach
            </ul>
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
const mcLabels = {!! json_encode($monthlyCosts['labels']) !!};
const mcFuel = {!! json_encode($monthlyCosts['fuel']) !!};
const mcMaint = {!! json_encode($monthlyCosts['maint']) !!};
const mcFines = {!! json_encode($monthlyCosts['fines']) !!};
new ApexCharts(document.querySelector('#chartMonthlyCosts'), { chart:{ type:'bar', height:300, stacked:true }, series:[ { name:'Combustível', data:mcFuel }, { name:'Manutenção', data:mcMaint }, { name:'Multas', data:mcFines } ], xaxis:{ categories:mcLabels } }).render();

const consSeries = [{ name:'Km/L', data: {!! json_encode(array_map(function($x){ return round($x['avg'],2); }, $consumptionByVehicle)) !!} }];
const consLabels = {!! json_encode(array_map(function($x){ return $x['name']; }, $consumptionByVehicle)) !!};
new ApexCharts(document.querySelector('#chartConsumptionByVehicle'), { chart:{ type:'bar', height:300 }, series:consSeries, xaxis:{ categories:consLabels } }).render();

const dpNames = {!! json_encode(array_map(function($x){ return $x['name']; }, $driversPerformance)) !!};
const dpHours = {!! json_encode(array_map(function($x){ return $x['hours']; }, $driversPerformance)) !!};
const dpKms = {!! json_encode(array_map(function($x){ return $x['kms']; }, $driversPerformance)) !!};
const dpFines = {!! json_encode(array_map(function($x){ return $x['fines']; }, $driversPerformance)) !!};
new ApexCharts(document.querySelector('#chartDriversPerformance'), { chart:{ type:'line', height:300 }, series:[ { name:'Horas', data:dpHours }, { name:'KM', data:dpKms }, { name:'Multas', data:dpFines } ], xaxis:{ categories:dpNames } }).render();

new ApexCharts(document.querySelector('#chartCostPerKm'), { chart:{ type:'radialBar', height:300 }, series:[ {{ $cards['avgCostPerKm'] }} ], labels:['R$/KM'] }).render();
</script>
@endsection
