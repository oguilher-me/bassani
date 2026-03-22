@extends('layouts/contentNavbarLayout')
@section('title', __('Agenda de Motoristas'))
@section('content')
<div class="row mb-3">
  <div class="col-12">
    <form class="row g-2" method="GET" action="{{ route('driver-schedules.all') }}">
      <div class="col-md-3">
        <label class="form-label">{{ __('Motorista') }}</label>
        <select name="driver_id" class="form-select select2">
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
        <button class="btn btn-primary w-100">{{ __('Filtrar') }}</button>
      </div>
    </form>
  </div>
</div>


<div class="row mt-4">
  <div class="col-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ __('Calendário') }}</span>
      </div>
      <div class="card-body">
        <div id="calendar" style="min-height:400px"></div>
      </div>
    </div>
  </div>

   <div class="col-4">
    <div class="card">
      <div class="card-header">{{ __('Entregas Programadas') }}</div>
      <div class="card-body">

        <ul class="list-group">
          @foreach($destinations as $d)
            <li class="list-group-item">
                <strong>Horário:</strong> {{ optional($d->window_start)->format('d/m/Y H:i') }} - {{ optional($d->window_end)->format('d/m/Y H:i') }}<br>
                <i class="bx bx-id-card"></i> {{ optional($d->plannedShipment->driver)->full_name ?? optional($d->plannedShipment->driver)->name }}<br>
                <i class="bx bx-user"></i> {{ $d->contact_name }}<br>
                <i class="bx bx-phone"></i> {{ $d->contact_phone }}<br>
                <strong>Status: <span class="badge bg-label-primary">{{ $d->confirmation_status ?? 'Planejada' }}</span></strong>
                <a href="{{ route('driver.destinations.show', $d->id) }}" class="btn btn-sm btn-primary mt-2">{{ __('Ver Detalhes') }}</a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
 
@section('page-script')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
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
    events: {
      url: '{{ route('driver-schedules.events') }}',
      extraParams: {
        driver_id: '{{ request('driver_id') }}',
        date: '{{ request('date') }}',
        status: '{{ request('status') }}'
      }
    }
  });
  calendar.render();
});
</script>
@endsection
