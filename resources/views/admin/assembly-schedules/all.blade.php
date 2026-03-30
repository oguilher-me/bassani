@extends('layouts/contentNavbarLayout')

@section('title', __('Agendamentos de Montagem'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Agenda de Montagem') }}</h4>
        <p class="text-muted mb-0">{{ __('Calendário e agendamentos de montagem') }}</p>
    </div>
</div>

{{-- Alerts --}}
@if (session('success'))
    <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
        <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show" role="alert">
        <i class="bx bx-error-circle me-2"></i>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Filter Card --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form id="filter-form" class="filter-form row g-3" action="{{ route('assembly-schedules.all') }}" method="GET">
            @if (Auth::check() && Auth::user()->role_id != 4)
                <div class="col-md-3">
                    <label for="assembler_id" class="form-label">{{ __('Montador') }}</label>
                    <select name="assembler_id" id="assembler_id" class="form-select">
                        <option value="">{{ __('Todos') }}</option>
                        @foreach ($assemblers as $assembler)
                            <option value="{{ $assembler->id }}" {{ request('assembler_id') == $assembler->id ? 'selected' : '' }}>{{ $assembler->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="col-md-3">
                <label for="date" class="form-label">{{ __('Data') }}</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">{{ __('Status de Confirmação') }}</label>
                <select name="status" id="status" class="form-select">
                    <option value="">{{ __('Todos') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pendente') }}</option>
                    <option value="started" {{ request('status') == 'started' ? 'selected' : '' }}>{{ __('Em Andamento') }}</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>{{ __('Confirmado') }}</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelado') }}</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
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
                    <i class="bx bx-calendar text-danger me-2"></i>{{ __('Calendário de Agendamentos') }}
                </h6>
            </div>
            <div class="card-body">
                <div id="calendar" style="min-height: 500px"></div>
            </div>
        </div>
    </div>

    {{-- Schedules List --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-list-check text-danger me-2"></i>{{ __('Agendamentos') }}
                </h6>
                <span class="badge bg-label-primary">{{ $schedules->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if ($schedules->isEmpty())
                    <div class="text-center py-5">
                        <i class="bx bx-calendar-x fs-1 text-muted opacity-50"></i>
                        <p class="text-muted mt-2">{{ __('Nenhum agendamento encontrado') }}</p>
                    </div>
                @else
                    @php
                        $groupedSchedules = $schedules->groupBy(function($item) {
                            return \Carbon\Carbon::parse($item->scheduled_date)->format('Y-m-d');
                        });
                    @endphp

                    <div class="list-group list-group-flush">
                        @foreach ($groupedSchedules as $date => $dailySchedules)
                            <div class="list-group-item bg-light py-2">
                                <span class="fw-semibold text-danger">
                                    <i class="bx bx-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                </span>
                            </div>
                            @foreach ($dailySchedules as $schedule)
                                <div class="list-group-item border-start-0 border-end-0 py-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-label-info rounded-pill px-2 py-1 small">
                                            <i class="bx bx-time-five me-1"></i>
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                        </span>
                                    </div>
                                    
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <i class="bx bx-user"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">{{ __('Cliente') }}</small>
                                            <span class="fw-semibold small">
                                                @if($schedule->sale->customer->customer_type == 'PF')
                                                    {{ $schedule->sale->customer->full_name ?? 'N/A' }}
                                                @else
                                                    {{ $schedule->sale->customer->company_name ?? 'N/A' }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <i class="bx bx-wrench text-primary"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">{{ __('Montadores') }}</small>
                                            <span class="small">
                                                @foreach ($schedule->assemblers as $assembler)
                                                    {{ $assembler->name }}@if(!$loop->last), @endif
                                                @endforeach
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('assembly-schedules.showDetails', $schedule->id) }}" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="bx bx-show me-1"></i> {{ __('Ver Detalhes') }}
                                    </a>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
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
    .fc-event.success { background-color: #1cc88a; border-color: #1cc88a; }
    .fc-event.warning { background-color: #f6c23e; border-color: #f6c23e; }
    .fc-event.danger { background-color: #e74a3b; border-color: #e74a3b; }
    .fc-event.info { background-color: #36b9cc; border-color: #36b9cc; }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/pt-br.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>
<script>
    var userType = "{{ Auth::check() && Auth::user()->role_id == 4 ? 'Montador' : '' }}";
    var userId = "{{ Auth::check() && Auth::user()->role_id == 4 ? Auth::user()->assembler->id : '' }}";

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
                url: '{{ route('assembly-schedules.events') }}',
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                extraParams: function() {
                    var params = {
                        date: document.getElementById('date').value,
                        status: document.getElementById('status').value,
                    };

                    if (userType === 'Montador') {
                        params.assembler_id = userId;
                    } else {
                        var assemblerIdElement = document.getElementById('assembler_id');
                        if (assemblerIdElement) {
                            params.assembler_id = assemblerIdElement.value;
                        } else {
                            params.assembler_id = '';
                        }
                    }
                    return params;
                },
                failure: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Houve um erro ao carregar os agendamentos!',
                        confirmButtonColor: '#DE0802'
                    });
                },
                success: function(response) {
                    return response;
                }
            },
            eventClick: function(info) {
                document.getElementById('modalTitle').innerText = info.event.title;
                document.getElementById('modalCustomerName').innerText = info.event.extendedProps.customer_name;
                document.getElementById('modalProductName').innerText = info.event.extendedProps.product_name;
                document.getElementById('modalStart').innerText = moment(info.event.start).format('DD/MM/YYYY HH:mm');
                document.getElementById('modalEnd').innerText = info.event.end ? moment(info.event.end).format('DD/MM/YYYY HH:mm') : '-';
                document.getElementById('modalNotes').innerText = info.event.extendedProps.assemblers.map(a => a.assembler_notes ?? 'N/A').join(', ');
                document.getElementById('modalAssemblers').innerText = info.event.extendedProps.assemblers.map(a => a.name).join(', ');
                document.getElementById('modalStatus').innerText = info.event.extendedProps.assemblers.map(a => a.confirmation_status).join(', ');
                document.getElementById('modalSaleId').innerText = info.event.extendedProps.sale_id;

                document.getElementById('scheduleSideModal').style.display = 'block';
            },
            eventDidMount: function(info) {
                $(info.el).tooltip({
                    title: info.event.title,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
            }
        });

        calendar.render();

        // Side Modal HTML
        const sideModal = `
            <div id="scheduleSideModal" class="side-modal">
                <div class="side-modal-content">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <h5 class="mb-0 fw-bold" id="modalTitle"></h5>
                        <span class="close-button fs-3">&times;</span>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                <i class="bx bx-user"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Cliente') }}</small>
                                <span class="fw-semibold" id="modalCustomerName"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                <i class="bx bx-package"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Produto') }}</small>
                                <span class="fw-semibold" id="modalProductName"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('Início') }}</small>
                            <span class="fw-semibold" id="modalStart"></span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('Fim') }}</small>
                            <span class="fw-semibold" id="modalEnd"></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">{{ __('Montadores') }}</small>
                        <span id="modalAssemblers"></span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">{{ __('Status') }}</small>
                        <span id="modalStatus"></span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">{{ __('Notas') }}</small>
                        <span id="modalNotes"></span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">{{ __('ID da Venda') }}</small>
                        <span class="fw-semibold" id="modalSaleId"></span>
                    </div>
                    <div class="mt-4 pt-3 border-top">
                        <a id="modalDetailLink" href="#" class="btn btn-primary w-100">
                            <i class="bx bx-show me-1"></i>{{ __('Ver Detalhes') }}
                        </a>
                    </div>
                </div>
            </div>
        `;
        $('body').append(sideModal);

        // Side Modal CSS
        const sideModalCss = `
            .side-modal {
                display: none;
                position: fixed;
                z-index: 1050;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0,0,0,0.4);
            }
            .side-modal-content {
                background-color: #fff;
                margin: 0;
                padding: 20px;
                border: none;
                width: 350px;
                height: 100%;
                position: absolute;
                right: 0;
                top: 0;
                box-shadow: -4px 0 15px rgba(0,0,0,0.1);
                animation-name: slideIn;
                animation-duration: 0.3s;
                overflow-y: auto;
            }
            .close-button {
                color: #aaa;
                cursor: pointer;
                transition: color 0.2s;
            }
            .close-button:hover {
                color: #DE0802;
            }
            @keyframes slideIn {
                from { right: -350px; opacity: 0; }
                to { right: 0; opacity: 1; }
            }
        `;
        $('head').append('<style type="text/css">' + sideModalCss + '</style>');

        const modal = document.getElementById("scheduleSideModal");
        const span = document.getElementsByClassName("close-button")[0];

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        if (userType != 'Montador') {
            document.getElementById('assembler_id').addEventListener('change', function() {
                calendar.refetchEvents();
            });
        }
        document.getElementById('date').addEventListener('change', function() {
            calendar.refetchEvents();
        });
        document.getElementById('status').addEventListener('change', function() {
            calendar.refetchEvents();
        });

        document.querySelector('.filter-form').addEventListener('submit', function(e) {
            calendar.refetchEvents();
        });
    });
</script>
@endsection