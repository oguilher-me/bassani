@extends('layouts/contentNavbarLayout')

@section('title', __('Agendamentos de Montagem'))

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('vendor-script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
@endsection

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card"> 
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Agenda de Montagem') }}</h5>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
               
                <form id="filter-form" class="filter-form" action="{{ route('assembly-schedules.all') }}" method="GET">
                    <div class="row">
                        @if (Auth::check() && Auth::user()->role_id != 4)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="assembler_id">Montador:</label>
                                    <select name="assembler_id" id="assembler_id" class="form-control">
                                        <option value="">Todos</option>
                                        @foreach ($assemblers as $assembler)
                                            <option value="{{ $assembler->id }}" {{ request('assembler_id') == $assembler->id ? 'selected' : '' }}>{{ $assembler->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date">Data:</label>
                                <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status de Confirmação:</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                                    <option value="started" {{ request('status') == 'started' ? 'selected' : '' }}>Em Andamento</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>
                        </div>
                    
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary">Filtrar</button>

                        </div>
                </form>
                 
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="mb-0">{{ __('Calendário de Agendamentos') }}</h5>
                
                        <div id='calendar'></div>
                    </div>
                   
                    <div class="col-md-4">
                        
                                <h5 class="mb-0">{{ __('Agendamentos') }}</h5>
                           
                                @if ($schedules->isEmpty())
                                    <p>Nenhum agendamento encontrado com os filtros aplicados.</p>
                                @else
                                    @php
                                        $groupedSchedules = $schedules->groupBy(function($item) {
                                            return \Carbon\Carbon::parse($item->scheduled_date)->format('Y-m-d');
                                        });
                                    @endphp

                                    @foreach ($groupedSchedules as $date => $dailySchedules)
                                        <div class="mb-3">
                                            <h5>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h5>
                                            <ul class="list-group">
                                                @foreach ($dailySchedules as $schedule)
                                                    <li class="list-group-item">
                                                        <strong>Horário:</strong> {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}<br>
                                                        <strong>Cliente:</strong> @if($schedule->sale->customer->customer_type == 'PF'){{ $schedule->sale->customer->full_name ?? 'N/A' }}@else{{ $schedule->sale->customer->company_name ?? 'N/A' }}@endif<br>
                                                        <strong>Montador(es):</strong>
                                                        @foreach ($schedule->assemblers as $assembler)
                                                            {{ $assembler->name }} ({{ ucfirst($assembler->pivot->confirmation_status) }})<br>
                                                        @endforeach
                                                        <strong>Status:</strong>
                                                        @foreach ($schedule->assemblers as $assembler)
                                                            {{ ucfirst($assembler->pivot->confirmation_status) }}<br>
                                                        @endforeach
                                                        <strong>Observações:</strong>
                                                        @foreach ($schedule->assemblers as $assembler)
                                                            {{ $assembler->pivot->assembler_notes ?? 'N/A' }}<br>
                                                        @endforeach
                                                        <a href="{{ route('assembly-schedules.showDetails', $schedule->id) }}" class="btn btn-sm btn-primary mt-2">Ver Detalhes</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                @endif
                           
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
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
                            params.assembler_id = ''; // Ou defina um valor padrão apropriado, se necessário
                        }
                    }
                    console.log('FullCalendar sending params:', params);
                    return params;
                },
                failure: function() {
                    alert('Houve um erro ao carregar os agendamentos!');
                },
                success: function(response) {
                    console.log('FullCalendar received events:', response);
                    return response; // Assuming the response is already an array of event objects
                }
            },
            eventClick: function(info) {
                    // Populate modal with event data
                    document.getElementById('modalTitle').innerText = info.event.title;
                    document.getElementById('modalCustomerName').innerText = info.event.extendedProps.customer_name;
                    document.getElementById('modalProductName').innerText = info.event.extendedProps.product_name;
                    document.getElementById('modalStart').innerText = moment(info.event.start).format('DD/MM/YYYY HH:mm');
                    document.getElementById('modalEnd').innerText = moment(info.event.end).format('DD/MM/YYYY HH:mm');
                    document.getElementById('modalNotes').innerText = info.event.extendedProps.assemblers.map(a => a.assembler_notes ?? 'N/A').join(', ');
                    document.getElementById('modalAssemblers').innerText = info.event.extendedProps.assemblers.map(a => a.name).join(', ');
                    document.getElementById('modalStatus').innerText = info.event.extendedProps.assemblers.map(a => a.confirmation_status).join(', ');
                    document.getElementById('modalSaleId').innerText = info.event.extendedProps.sale_id;

                    // Display the modal
                    document.getElementById('scheduleSideModal').style.display = 'block';
                },
            eventDidMount: function(info) {
                // Tooltip for events
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
                    <span class="close-button">\u0026times;</span>
                    <h5 style="font-weight: 700;" id="modalTitle"></h5>
                    <p><strong>Cliente:</strong> <span id="modalCustomerName"></span></p>
                    <p><strong>Produto:</strong> <span id="modalProductName"></span></p>
                    <p><strong>Início:</strong> <span id="modalStart"></span></p>
                    <p><strong>Fim:</strong> <span id="modalEnd"></span></p>
                    <p><strong>Notas:</strong> <span id="modalNotes"></span></p>
                    <p><strong>Montadores:</strong> <span id="modalAssemblers"></span></p>
                    <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                    <p><strong>ID da Venda:</strong> <span id="modalSaleId"></span></p>
                </div>
            </div>
        `;
        $('body').append(sideModal);

        // Side Modal CSS
        const sideModalCss = `
            .side-modal {
                display: none; /* Hidden by default */
                position: fixed; /* Stay in place */
                z-index: 1050; /* Sit on top */
                left: 0;
                top: 0;
                width: 100%; /* Full width */
                height: 100%; /* Full height */
                overflow: auto; /* Enable scroll if needed */
                background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            }
            .side-modal-content {
                background-color: #fefefe;
                margin: auto;
                padding: 20px;
                border: 1px solid #888;
                width: 30%; /* Adjust as needed */
                height: 100%;
                position: absolute;
                right: 0;
                top: 0;
                box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
                -webkit-animation-name: slideIn;
                -webkit-animation-duration: 0.4s;
                animation-name: slideIn;
                animation-duration: 0.4s
            }
            .close-button {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }
            .close-button:hover,
            .close-button:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
            @-webkit-keyframes slideIn {
                from {right: -300px; opacity: 0}
                to {right: 0; opacity: 1}
            }
            @keyframes slideIn {
                from {right: -300px; opacity: 0}
                to {right: 0; opacity: 1}
            }
        `;
        $('head').append('<style type="text/css">' + sideModalCss + '</style>');

        // Get the modal
        const modal = document.getElementById("scheduleSideModal");

        // Get the <span> element that closes the modal
        const span = document.getElementsByClassName("close-button")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        if (userType != 'Montador') {
            // Recarregar eventos quando os filtros mudarem
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

        // Adicionar evento de submit para o formulário de filtros para recarregar o calendário
        document.querySelector('.filter-form').addEventListener('submit', function(e) {

            calendar.refetchEvents();
        });

    });
</script>
@endsection