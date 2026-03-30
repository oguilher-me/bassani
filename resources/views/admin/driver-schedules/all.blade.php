@extends('layouts/contentNavbarLayout')

@section('title', __('Agenda de Motoristas'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Agenda de Motoristas') }}</h4>
        <p class="text-muted mb-0">{{ __('Calendário e entregas programadas') }}</p>
    </div>
</div>

{{-- Filter Card --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form class="row g-3" method="GET" action="{{ route('driver-schedules.all') }}">
            <div class="col-md-3">
                <label class="form-label">{{ __('Motorista') }}</label>
                <select name="driver_id" class="form-select">
                    <option value="">{{ __('Todos') }}</option>
                    @foreach($drivers as $d)
                        <option value="{{ $d->id }}" {{ request('driver_id') == $d->id ? 'selected' : '' }}>{{ $d->full_name ?? $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('Data') }}</label>
                <input type="date" name="date" value="{{ request('date') }}" class="form-control" />
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('Status') }}</label>
                <select name="status" class="form-select">
                    <option value="">{{ __('Todos') }}</option>
                    @foreach(['Planned','In Transit','Delivered','Returned','Cancelled'] as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary w-100">
                    <i class="bx bx-filter me-1"></i> {{ __('Filtrar') }}
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    {{-- Calendar --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-calendar text-danger me-2"></i>{{ __('Calendário') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="calendar" style="min-height: 500px"></div>
            </div>
        </div>
    </div>

    {{-- Scheduled Deliveries --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-list-check text-danger me-2"></i>{{ __('Entregas Programadas') }}
                </h6>
                <span class="badge bg-label-primary">{{ $destinations->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($destinations->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($destinations as $d)
                            <div class="list-group-item border-start-0 border-end-0 py-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <span class="badge bg-label-info rounded-pill px-2 py-1 small">
                                            <i class="bx bx-time-five me-1"></i>
                                            {{ optional($d->window_start)->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                    <span class="badge {{ ($d->confirmation_status ?? 'Planejada') == 'Delivered' ? 'bg-success' : (($d->confirmation_status ?? 'Planejada') == 'Cancelled' ? 'bg-danger' : 'bg-warning') }} rounded-pill px-2 py-1 small">
                                        {{ $d->confirmation_status ?? 'Planejada' }}
                                    </span>
                                </div>
                                
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <i class="bx bx-user"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">{{ __('Cliente') }}</small>
                                        <span class="fw-semibold small">{{ $d->contact_name }}</span>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <i class="bx bx-phone text-primary"></i>
                                    </div>
                                    <span class="small">{{ $d->contact_phone }}</span>
                                </div>
                                
                                @if(optional($d->plannedShipment->driver)->full_name)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <i class="bx bx-car text-primary"></i>
                                    </div>
                                    <span class="small">{{ optional($d->plannedShipment->driver)->full_name }}</span>
                                </div>
                                @endif
                                
                                <a href="{{ route('driver.destinations.show', $d->id) }}" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="bx bx-show me-1"></i> {{ __('Ver Detalhes') }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bx bx-calendar-x fs-1 text-muted opacity-50"></i>
                        <p class="text-muted mt-2">{{ __('Nenhuma entrega programada') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<style>
    .fc .fc-button-primary {
        background-color: #DE0802;
        border-color: #DE0802;
    }
    .fc .fc-button-primary:hover {
        background-color: #B3211A;
        border-color: #B3211A;
    }
    .fc .fc-button-primary:disabled {
        background-color: #DE0802;
        border-color: #DE0802;
        opacity: 0.6;
    }
    .fc .fc-day-today {
        background-color: rgba(222, 8, 2, 0.05) !important;
    }
    .fc-event {
        cursor: pointer;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonIcons: {
            prev: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            prevYear: 'bx bx-chevron-left',
            nextYear: 'bx bx-chevron-right'
        },
        events: {
            url: '{{ route('driver-schedules.events') }}',
            extraParams: {
                driver_id: '{{ request('driver_id') }}',
                date: '{{ request('date') }}',
                status: '{{ request('status') }}'
            }
        },
        eventClick: function(info) {
            if (info.event.url) {
                window.open(info.event.url, '_blank');
                info.jsEvent.preventDefault();
            }
        }
    });
    calendar.render();
});
</script>
@endsection