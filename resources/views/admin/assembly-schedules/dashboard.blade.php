@extends('layouts/contentNavbarLayout')

@section('title', __('Dashboard de Montagem'))

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <form class="row g-2" method="GET" action="{{ route('assembly-schedules.dashboard') }}">
            <div class="col-md-2">
                <label class="form-label">{{ __('Início') }}</label>
                <input type="date" name="start_date" value="{{ $filters['start'] }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('Fim') }}</label>
                <input type="date" name="end_date" value="{{ $filters['end'] }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('Montador') }}</label>
                <select name="assembler_id" class="form-select">
                    <option value="">{{ __('Todos') }}</option>
                    @foreach($assemblers as $a)
                        <option value="{{ $a->id }}" {{ $filters['assembler_id']==$a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('Cidade') }}</label>
                <input type="text" name="city" value="{{ $filters['city'] }}" class="form-control">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">{{ __('Filtrar') }}</button>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">

        <div class="col-lg-2 col-sm-6" bis_skin_checked="1">
            <div class="card card-border-shadow-primary h-100" bis_skin_checked="1">
                <div class="card-body" bis_skin_checked="1">
                    <div class="d-flex align-items-center mb-2" bis_skin_checked="1">
                        <div class="avatar me-4" bis_skin_checked="1"> 
                            <span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-cabinet icon-lg"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $cards['totalMonth'] }}</h4>
                    </div>
                    <p class="mb-2">{{ __('Total no mês') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6" bis_skin_checked="1">
            <div class="card card-border-shadow-success h-100" bis_skin_checked="1">
                <div class="card-body" bis_skin_checked="1">
                    <div class="d-flex align-items-center mb-2" bis_skin_checked="1">
                        <div class="avatar me-4" bis_skin_checked="1"> 
                            <span class="avatar-initial rounded bg-label-success"><i class="icon-base bx bx-check icon-lg"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $cards['completed'] }}</h4>
                    </div>
                    <p class="mb-2">{{ __('Concluídas') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6" bis_skin_checked="1">
            <div class="card card-border-shadow-danger h-100" bis_skin_checked="1">
                <div class="card-body" bis_skin_checked="1">
                    <div class="d-flex align-items-center mb-2" bis_skin_checked="1">
                        <div class="avatar me-4" bis_skin_checked="1"> 
                            <span class="avatar-initial rounded bg-label-danger"><i class="icon-base bx bx-time icon-lg"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $cards['late'] }}</h4>
                    </div>
                    <p class="mb-2">{{ __('Atrasadas') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6" bis_skin_checked="1">
            <div class="card card-border-shadow-warning h-100" bis_skin_checked="1">
                <div class="card-body" bis_skin_checked="1">
                    <div class="d-flex align-items-center mb-2" bis_skin_checked="1">
                        <div class="avatar me-4" bis_skin_checked="1"> 
                            <span class="avatar-initial rounded bg-label-warning"><i class="icon-base bx bx-calendar icon-lg"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $cards['next7'] }}</h4>
                    </div>
                    <p class="mb-2">{{ __('Próximos 7 dias') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6" bis_skin_checked="1">
            <div class="card card-border-shadow-info h-100" bis_skin_checked="1">
                <div class="card-body" bis_skin_checked="1">
                    <div class="d-flex align-items-center mb-2" bis_skin_checked="1">
                        <div class="avatar me-4" bis_skin_checked="1"> 
                            <span class="avatar-initial rounded bg-label-info"><i class="icon-base bx bx-bar-chart icon-lg"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $cards['completionRate'] }}</h4>
                    </div>
                    <p class="mb-2">{{ __('Taxa de Conclusão') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6" bis_skin_checked="1">
            <div class="card card-border-shadow-warning h-100" bis_skin_checked="1">
                <div class="card-body" bis_skin_checked="1">
                    <div class="d-flex align-items-center mb-2" bis_skin_checked="1">
                        <div class="avatar me-4" bis_skin_checked="1"> 
                            <span class="avatar-initial rounded bg-label-warning"><i class="icon-base bx bx-smile icon-lg"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $cards['npsAvg'] }}</h4>
                    </div>
                    <p class="mb-2">{{ __('NPS Médio') }}</p>
                </div>
            </div>
        </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('Concluídas por montador') }}</div>
            <div class="card-body"><div id="chartCompletedByAssembler"></div></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('NPS por montador') }}</div>
            <div class="card-body"><div id="chartNpsByAssembler"></div></div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('Distribuição por status') }}</div>
            <div class="card-body"><div id="chartStatus"></div></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('Montagens por dia') }}</div>
            <div class="card-body"><div id="chartTimeline"></div></div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card"><div class="card-header">{{ __('Índice de atrasos') }}</div>
            <div class="card-body">
                <div>{{ __('Dentro do prazo') }}: {{ $delays['onTime'] }}</div>
                <div>{{ __('Atrasadas') }}: {{ $delays['delayed'] }}</div>
                <div>{{ __('Atraso médio (h)') }}: {{ $delays['avgHours'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card"><div class="card-header">{{ __('Distribuição NPS') }}</div>
            <div class="card-body"><div id="chartNpsDist"></div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card"><div class="card-header">{{ __('Comentários recentes') }}</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    @foreach($evals as $e)
                        <li class="mb-2">
                            <div><strong>{{ __('NPS') }}:</strong> {{ $e->nps_score }} <span class="text-muted">{{ optional($e->submitted_at)->format('d/m/Y') }}</span></div>
                            @if($e->comments)<div class="small">{{ $e->comments }}</div>@endif
                            @php $photos = $e->photo_paths ? json_decode($e->photo_paths,true) : [] @endphp
                            @if(!empty($photos))
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    @foreach($photos as $p)
                                        <img src="{{ Storage::url($p) }}" style="width: 60px; height: 60px; object-fit: cover;" class="rounded" />
                                    @endforeach
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card"><div class="card-header">{{ __('Hoje') }}</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    @foreach($todayList as $s)
                        <li class="mb-1"><a href="{{ route('assembly-schedules.showDetails', $s->id) }}">#{{ $s->id }} {{ optional($s->sale)->erp_code }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card"><div class="card-header">{{ __('Próximos dias') }}</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    @foreach($nextDays as $s)
                        <li class="mb-1"><a href="{{ route('assembly-schedules.showDetails', $s->id) }}">#{{ $s->id }} {{ optional($s->sale)->erp_code }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card"><div class="card-header">{{ __('Em andamento') }}</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    @foreach($inProgress as $s)
                        <li class="mb-1"><a href="{{ route('assembly-schedules.showDetails', $s->id) }}">#{{ $s->id }} {{ optional($s->sale)->erp_code }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card"><div class="card-header">{{ __('Aguardando reprogramação') }}</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    @foreach($needsReschedule as $s)
                        <li class="mb-1"><a href="{{ route('assembly-schedules.showDetails', $s->id) }}">#{{ $s->id }} {{ optional($s->sale)->erp_code }}</a></li>
                    @endforeach
                </ul>
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
new ApexCharts(document.querySelector('#chartCompletedByAssembler'), { chart:{ type:'bar', height:300 }, series:[{ name:'Concluídas', data:completedData }], xaxis:{ categories:completedLabels } }).render();

const npsLabels = {!! json_encode(array_values(array_map(function($x){return $x['name'];}, $npsByAssembler))) !!};
const npsData = {!! json_encode(array_values(array_map(function($x){return $x['avg'];}, $npsByAssembler))) !!};
new ApexCharts(document.querySelector('#chartNpsByAssembler'), { chart:{ type:'bar', height:300 }, series:[{ name:'NPS', data:npsData }], xaxis:{ categories:npsLabels }, yaxis:{ min:0, max:10 } }).render();

const statusLabels = ['Planejada','Em andamento','Aguardando cliente','Atrasada','Concluída','Cancelada'];
const statusData = {!! json_encode(array_values($statusDist)) !!};
new ApexCharts(document.querySelector('#chartStatus'), { chart:{ type:'donut', height:300 }, series:statusData, labels:statusLabels, colors:['#858796','#36b9cc','#f6c23e','#e74a3b','#1cc88a','#6c757d'] }).render();

const timelineLabels = {!! json_encode(array_keys($timeline)) !!};
const timelineData = {!! json_encode(array_values($timeline)) !!};
new ApexCharts(document.querySelector('#chartTimeline'), { chart:{ type:'line', height:300 }, series:[{ name:'Montagens', data:timelineData }], xaxis:{ categories:timelineLabels } }).render();

const npsDistData = {!! json_encode([ (int)$promoters, (int)$passives, (int)$detractors ]) !!};
new ApexCharts(document.querySelector('#chartNpsDist'), { chart:{ type:'pie', height:300 }, series:npsDistData, labels:['Promotores','Neutros','Detratores'], colors:['#1cc88a','#f6c23e','#e74a3b'] }).render();
</script>
@endsection
