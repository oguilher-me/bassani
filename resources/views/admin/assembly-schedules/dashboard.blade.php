@extends('layouts/contentNavbarLayout')

@section('title', __('Dashboard de Montagem'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Dashboard de Montagem') }}</h4>
        <p class="text-muted mb-0">{{ __('Indicadores e estatísticas de montagens') }}</p>
    </div>
</div>

{{-- Filter Card --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form class="row g-3" method="GET" action="{{ route('assembly-schedules.dashboard') }}">
            <div class="col-md-2">
                <label class="form-label">{{ __('Início') }}</label>
                <input type="date" name="start_date" value="{{ $filters['start'] }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('Fim') }}</label>
                <input type="date" name="end_date" value="{{ $filters['end'] }}" class="form-control">
            </div>
            @unless(Auth::user()->hasRole('Montador'))
            <div class="col-md-2">
                <label class="form-label">{{ __('Montador') }}</label>
                <select name="assembler_id" class="form-select">
                    <option value="">{{ __('Todos') }}</option>
                    @foreach($assemblers as $a)
                        <option value="{{ $a->id }}" {{ $filters['assembler_id']==$a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                    @endforeach
                </select>
            </div>
            @endunless
            <div class="col-md-2">
                <label class="form-label">{{ __('Cidade') }}</label>
                <input type="text" name="city" value="{{ $filters['city'] }}" class="form-control">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-filter me-1"></i> {{ __('Filtrar') }}
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Stats Cards --}}
<div class="row g-4 mb-4">
    <div class="col-lg-2 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-calendar-check fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block mb-1 small text-muted">{{ __('Total no mês') }}</span>
                        <span class="fw-bold fs-4">{{ $cards['totalMonth'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-success d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-check-circle fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block mb-1 small text-muted">{{ __('Concluídas') }}</span>
                        <span class="fw-bold fs-4">{{ $cards['completed'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-danger d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-time-five fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block mb-1 small text-muted">{{ __('Atrasadas') }}</span>
                        <span class="fw-bold fs-4">{{ $cards['late'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-calendar fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block mb-1 small text-muted">{{ __('Próximos 7 dias') }}</span>
                        <span class="fw-bold fs-4">{{ $cards['next7'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-bar-chart-alt-2 fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block mb-1 small text-muted">{{ __('Taxa de Conclusão') }}</span>
                        <span class="fw-bold fs-4">{{ $cards['completionRate'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-smile fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block mb-1 small text-muted">{{ __('NPS Médio') }}</span>
                        <span class="fw-bold fs-4">{{ $cards['npsAvg'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row 1 --}}
@unless(Auth::user()->hasRole('Montador'))
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-user-check text-danger me-2"></i>{{ __('Concluídas por montador') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartCompletedByAssembler"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-star text-danger me-2"></i>{{ __('NPS por montador') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartNpsByAssembler"></div>
            </div>
        </div>
    </div>
</div>
@endunless

{{-- Charts Row 2 --}}
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-pie-chart-alt-2 text-danger me-2"></i>{{ __('Distribuição por status') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartStatus"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-line-chart text-danger me-2"></i>{{ __('Montagens por dia') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartTimeline"></div>
            </div>
        </div>
    </div>
</div>

{{-- Info Row --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-time text-danger me-2"></i>{{ __('Índice de atrasos') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ __('Dentro do prazo') }}</span>
                    <span class="fw-semibold text-success">{{ $delays['onTime'] }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ __('Atrasadas') }}</span>
                    <span class="fw-semibold text-danger">{{ $delays['delayed'] }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">{{ __('Atraso médio (h)') }}</span>
                    <span class="fw-semibold text-warning">{{ $delays['avgHours'] }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-happy text-danger me-2"></i>{{ __('Distribuição NPS') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="chartNpsDist"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-message-rounded-dots text-danger me-2"></i>{{ __('Comentários recentes') }}
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($evals as $e)
                        <div class="list-group-item py-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge {{ $e->nps_score >= 7 ? 'bg-success' : ($e->nps_score >= 4 ? 'bg-warning' : 'bg-danger') }} rounded-pill">
                                    NPS: {{ $e->nps_score }}
                                </span>
                                <small class="text-muted">{{ optional($e->submitted_at)->format('d/m/Y') }}</small>
                            </div>
                            @if($e->comments)
                                <p class="small mb-2">{{ $e->comments }}</p>
                            @endif
                            @php $photos = $e->photo_paths ? json_decode($e->photo_paths,true) : [] @endphp
                            @if(!empty($photos))
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($photos as $p)
                                        <img src="{{ Storage::url($p) }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded" />
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Schedules Lists --}}
<div class="row g-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-calendar text-info me-2"></i>{{ __('Hoje') }}
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($todayList as $s)
                        <a href="{{ route('assembly-schedules.showDetails', $s->id) }}" class="list-group-item list-group-item-action py-2">
                            <span class="badge bg-light text-dark">#{{ $s->id }}</span>
                            <span class="small ms-1">{{ optional($s->sale)->erp_code }}</span>
                        </a>
                    @empty
                        <div class="text-center py-4 text-muted small">{{ __('Nenhum agendamento') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-calendar-plus text-warning me-2"></i>{{ __('Próximos dias') }}
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($nextDays as $s)
                        <a href="{{ route('assembly-schedules.showDetails', $s->id) }}" class="list-group-item list-group-item-action py-2">
                            <span class="badge bg-light text-dark">#{{ $s->id }}</span>
                            <span class="small ms-1">{{ optional($s->sale)->erp_code }}</span>
                        </a>
                    @empty
                        <div class="text-center py-4 text-muted small">{{ __('Nenhum agendamento') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-loader-alt text-primary me-2"></i>{{ __('Em andamento') }}
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($inProgress as $s)
                        <a href="{{ route('assembly-schedules.showDetails', $s->id) }}" class="list-group-item list-group-item-action py-2">
                            <span class="badge bg-light text-dark">#{{ $s->id }}</span>
                            <span class="small ms-1">{{ optional($s->sale)->erp_code }}</span>
                        </a>
                    @empty
                        <div class="text-center py-4 text-muted small">{{ __('Nenhum agendamento') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-calendar-exclamation text-danger me-2"></i>{{ __('Aguardando reprogramação') }}
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($needsReschedule as $s)
                        <a href="{{ route('assembly-schedules.showDetails', $s->id) }}" class="list-group-item list-group-item-action py-2">
                            <span class="badge bg-light text-dark">#{{ $s->id }}</span>
                            <span class="small ms-1">{{ optional($s->sale)->erp_code }}</span>
                        </a>
                    @empty
                        <div class="text-center py-4 text-muted small">{{ __('Nenhum agendamento') }}</div>
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
const completedData = {!! json_encode(array_values(array_map(function($x){return $x['count'];}, $completedByAssembler))) !!};
const completedLabels = {!! json_encode(array_values(array_map(function($x){return $x['name'];}, $completedByAssembler))) !!};
new ApexCharts(document.querySelector('#chartCompletedByAssembler'), { 
    chart:{ type:'bar', height:300 }, 
    series:[{ name:'Concluídas', data:completedData }], 
    xaxis:{ categories:completedLabels },
    colors:['#DE0802'],
    plotOptions: { bar: { borderRadius: 4 } }
}).render();

const npsLabels = {!! json_encode(array_values(array_map(function($x){return $x['name'];}, $npsByAssembler))) !!};
const npsData = {!! json_encode(array_values(array_map(function($x){return $x['avg'];}, $npsByAssembler))) !!};
new ApexCharts(document.querySelector('#chartNpsByAssembler'), { 
    chart:{ type:'bar', height:300 }, 
    series:[{ name:'NPS', data:npsData }], 
    xaxis:{ categories:npsLabels }, 
    yaxis:{ min:0, max:10 },
    colors:['#B3211A'],
    plotOptions: { bar: { borderRadius: 4 } }
}).render();

const statusLabels = ['Planejada','Em andamento','Aguardando cliente','Atrasada','Concluída','Cancelada'];
const statusData = {!! json_encode(array_values($statusDist)) !!};
new ApexCharts(document.querySelector('#chartStatus'), { 
    chart:{ type:'donut', height:300 }, 
    series:statusData, 
    labels:statusLabels, 
    colors:['#1F2A44','#36b9cc','#f6c23e','#e74a3b','#DE0802','#6c757d'] 
}).render();

const timelineLabels = {!! json_encode(array_keys($timeline)) !!};
const timelineData = {!! json_encode(array_values($timeline)) !!};
new ApexCharts(document.querySelector('#chartTimeline'), { 
    chart:{ type:'line', height:300 }, 
    series:[{ name:'Montagens', data:timelineData }], 
    xaxis:{ categories:timelineLabels },
    colors:['#DE0802'],
    stroke: { curve: 'smooth', width: 3 }
}).render();

const npsDistData = {!! json_encode([ (int)$promoters, (int)$passives, (int)$detractors ]) !!};
new ApexCharts(document.querySelector('#chartNpsDist'), { 
    chart:{ type:'pie', height:300 }, 
    series:npsDistData, 
    labels:['Promotores','Neutros','Detratores'], 
    colors:['#DE0802','#f6c23e','#e74a3b'] 
}).render();
</script>
@endsection