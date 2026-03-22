@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard de Atividades - CRM')

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css">
<style>
    .kanban-column {
        min-height: 400px;
        background-color: #f5f5f9;
        border-radius: 0.5rem;
        padding: 1rem;
    }
    .kanban-card {
        cursor: grab;
        margin-bottom: 0.75rem;
        border-left: 4px solid #696cff;
    }
    .kanban-card.overdue {
        border-left-color: #ff3e1d;
    }
    .fc .fc-button-primary {
        background-color: #696cff;
        border-color: #696cff;
    }
    .fc .fc-button-primary:hover {
        background-color: #5f61e6;
        border-color: #5f61e6;
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">CRM /</span> Dashboard de Atividades</h4>
        
        @if($isAdminOrManager)
        <div class="d-flex align-items-center">
            <label class="me-2 fw-medium">Vendedor:</label>
            <form action="{{ route('crm.activities.dashboard') }}" method="GET" id="userFilterForm">
                <select name="user_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ $targetUserId == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        @endif
    </div>

    <!-- Alert Cards -->
    <div class="row mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-border-shadow-info h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-calendar-event"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $todayAgenda->count() }}</h4>
                    </div>
                    <p class="mb-1 fw-medium">Para Hoje</p>
                    <p class="mb-0 text-muted small">Compromissos agendados</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-border-shadow-danger h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-error-circle"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $overdueActivities->count() }}</h4>
                    </div>
                    <p class="mb-1 fw-medium">Atrasadas</p>
                    <p class="mb-0 text-muted small">Requerem atenção imediata</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-border-shadow-success h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-check-double"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $completedThisWeekCount }}</h4>
                    </div>
                    <p class="mb-1 fw-medium">Concluídas (Semana)</p>
                    <p class="mb-0 text-muted small">Total de interações</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-border-shadow-primary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-task"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $kanbanTasks['pending']->count() }}</h4>
                    </div>
                    <p class="mb-1 fw-medium">Tarefas Pendentes</p>
                    <p class="mb-0 text-muted small">No fluxo de trabalho</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Upcoming Appointments -->
        <div class="col-12 col-xl-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Próximos Compromissos & Agenda de Hoje</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover border-top">
                        <thead>
                            <tr>
                                <th>Horário/Data</th>
                                <th>Tipo</th>
                                <th>Assunto/Oportunidade</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayAgenda->merge($overdueActivities)->sortBy('due_date')->take(10) as $activity)
                            <tr id="activity-row-{{ $activity->id }}" class="{{ $activity->due_date->isPast() && $activity->status === 'pending' ? 'table-light' : '' }}">
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium text-nowrap">{{ $activity->due_date->format('H:i') }}</span>
                                        <small class="text-muted">{{ $activity->due_date->format('d/m/Y') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $typeIcons = [
                                            'call' => ['icon' => 'bx-phone', 'color' => 'primary'],
                                            'email' => ['icon' => 'bx-envelope', 'color' => 'info'],
                                            'meeting' => ['icon' => 'bx-calendar-event', 'color' => 'warning'],
                                            'task' => ['icon' => 'bx-task', 'color' => 'secondary'],
                                            'whatsapp' => ['icon' => 'bxl-whatsapp', 'color' => 'success'],
                                            'visit' => ['icon' => 'bx-map', 'color' => 'danger'],
                                        ];
                                        $type = $typeIcons[$activity->type] ?? ['icon' => 'bx-dots-horizontal-rounded', 'color' => 'secondary'];
                                    @endphp
                                    <span class="badge bg-label-{{ $type['color'] }} p-1">
                                        <i class="bx {{ $type['icon'] }}"></i>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium">{{ $activity->subject }}</span>
                                        @if($activity->opportunity)
                                        <a href="{{ route('crm.opportunities.show', $activity->opportunity_id) }}" class="small">
                                            <i class="bx bx-link-external me-1"></i>{{ $activity->opportunity->title }}
                                        </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-icon btn-success complete-task-btn" 
                                            data-id="{{ $activity->id }}" title="Marcar como concluído">
                                        <i class="bx bx-check"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">A agenda está limpa para hoje.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Weekly Effort Metrics -->
        <div class="col-12 col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Esforço na Semana</h5>
                </div>
                <div class="card-body">
                    <div id="effortChart"></div>
                    <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-phone"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Ligações</h6>
                                    <small class="text-muted">Realizadas</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">{{ $weeklyMetrics['calls'] }}</small>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-success"><i class="bxl-whatsapp"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">WhatsApp</h6>
                                    <small class="text-muted">Mensagens</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">{{ $weeklyMetrics['whatsapp'] }}</small>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-calendar"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Reuniões</h6>
                                    <small class="text-muted">Realizadas</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">{{ $weeklyMetrics['meetings'] }}</small>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-map"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Visitas</h6>
                                    <small class="text-muted">Técnicas/Comerciais</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">{{ $weeklyMetrics['visits'] }}</small>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Mini-Kanban for Tasks ONLY -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-2">
                    <h5 class="card-title mb-0">Fluxo de Tarefas (Exclusivo Task)</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Pending -->
                        <div class="col-md-4">
                            <div class="kanban-column bg-label-secondary">
                                <h6 class="text-uppercase fw-bold mb-3"><i class="bx bx-list-ul me-2"></i>Pendentes ({{ $kanbanTasks['pending']->count() }})</h6>
                                <div class="kanban-items" data-status="pending" id="kanban-pending">
                                    @foreach($kanbanTasks['pending'] as $task)
                                    <div class="card kanban-card shadow-sm" data-id="{{ $task->id }}">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <span class="badge bg-label-primary p-1 small"><i class="bx bx-task me-1"></i>Tarefa</span>
                                                <small class="text-muted">{{ $task->due_date ? $task->due_date->format('d/m') : 'S/ Data' }}</small>
                                            </div>
                                            <p class="mb-1 fw-medium small text-truncate" title="{{ $task->subject }}">{{ $task->subject }}</p>
                                            @if($task->opportunity)
                                            <a href="{{ route('crm.opportunities.show', $task->opportunity_id) }}" class="x-small text-muted d-block text-truncate">
                                                {{ $task->opportunity->title }}
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- In Progress (Mapped as Overdue for now) -->
                        <div class="col-md-4">
                            <div class="kanban-column bg-label-warning">
                                <h6 class="text-uppercase fw-bold mb-3 text-warning"><i class="bx bx-time-five me-2"></i>Em Andamento ({{ $kanbanTasks['overdue']->count() }})</h6>
                                <div class="kanban-items" data-status="pending" id="kanban-inprogress">
                                    @foreach($kanbanTasks['overdue'] as $task)
                                    <div class="card kanban-card overdue shadow-sm" data-id="{{ $task->id }}">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <span class="badge bg-label-warning p-1 small"><i class="bx bx-task me-1"></i>Tarefa</span>
                                                <small class="text-danger fw-bold">{{ $task->due_date->format('d/m') }}</small>
                                            </div>
                                            <p class="mb-1 fw-medium small text-truncate">{{ $task->subject }}</p>
                                            @if($task->opportunity)
                                            <a href="{{ route('crm.opportunities.show', $task->opportunity_id) }}" class="x-small text-muted d-block text-truncate">
                                                {{ $task->opportunity->title }}
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Completed -->
                        <div class="col-md-4">
                            <div class="kanban-column bg-label-success">
                                <h6 class="text-uppercase fw-bold mb-3 text-success"><i class="bx bx-check-circle me-2"></i>Concluídas (7 dias)</h6>
                                <div class="kanban-items" data-status="completed" id="kanban-completed">
                                    @foreach($kanbanTasks['completed'] as $task)
                                    <div class="card kanban-card border-success shadow-none opacity-75" data-id="{{ $task->id }}" style="border-left-color: #71dd37;">
                                        <div class="card-body p-2">
                                            <p class="mb-1 text-decoration-line-through small text-truncate">{{ $task->subject }}</p>
                                            <small class="text-muted d-block x-small">Concluído {{ $task->completed_at->format('d/m') }}</small>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Calendar -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">Calendário de Reuniões e Visitas</h5>
                </div>
                <div class="card-body">
                    <div id="calendar" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('vendor-script')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 0. Kanban Sortable Initialization
    const columns = document.querySelectorAll('.kanban-items');
    columns.forEach(column => {
        new Sortable(column, {
            group: 'kanban',
            animation: 150,
            ghostClass: 'bg-label-primary',
            onEnd: function (evt) {
                const itemEl = evt.item;
                const newStatus = evt.to.dataset.status;
                const activityId = itemEl.dataset.id;
                
                // If moved to COMPLETED column
                if (newStatus === 'completed') {
                    markAsComplete(activityId, itemEl, evt.from);
                }
                // Moves between pending/inprogress don't trigger server change for now
                // unless we want to update due_date.
            }
        });
    });

    function markAsComplete(id, element, fromColumn) {
        fetch(`/admin/crm/activities/${id}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            const res = await response.json();
            if (response.ok && res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Tarefa Concluída!',
                    timer: 1000,
                    showConfirmButton: false
                });
                // Optional: transform card style to 'completed' style if not reloaded
                element.classList.add('border-success', 'shadow-none', 'opacity-75');
                const body = element.querySelector('.card-body p');
                if (body) body.classList.add('text-decoration-line-through');
            } else {
                Swal.fire('Erro', res.message || 'Erro ao atualizar', 'error');
                fromColumn.appendChild(element); // Revert
            }
        })
        .catch(err => {
            console.error(err);
            fromColumn.appendChild(element); // Revert
        });
    }

    // 1. Effort Chart (ApexCharts)
    const effortOptions = {
        series: [{
            name: 'Interações',
            data: [
                {{ $weeklyMetrics['calls'] }}, 
                {{ $weeklyMetrics['whatsapp'] }}, 
                {{ $weeklyMetrics['meetings'] }}, 
                {{ $weeklyMetrics['visits'] }}, 
                {{ $weeklyMetrics['tasks'] }}
            ]
        }],
        chart: {
            height: 250,
            type: 'radar',
            toolbar: { show: false }
        },
        xaxis: {
            categories: ['Ligações', 'WhatsApp', 'Reuniões', 'Visitas', 'Tarefas']
        },
        colors: ['#696cff'],
        fill: {
            opacity: 0.4
        }
    };
    const effortChart = new ApexCharts(document.querySelector("#effortChart"), effortOptions);
    effortChart.render();

    // 2. FullCalendar Integration
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                day: 'Dia'
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            events: {!! json_encode($calendarEvents) !!},
            eventClick: function(info) {
                if (info.event.url) {
                    info.jsEvent.preventDefault();
                    window.location.href = info.event.url;
                }
            }
        });
        calendar.render();
    }

    // 3. Quick 'Complete' Button (AJAX)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.complete-task-btn')) {
            const btn = e.target.closest('.complete-task-btn');
            const id = btn.dataset.id;
            const row = document.getElementById(`activity-row-${id}`);

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

            fetch(`/admin/crm/activities/${id}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                const res = await response.json();
                if (response.ok && res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        if (row) {
                            row.style.opacity = '0';
                            setTimeout(() => row.remove(), 500);
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire('Erro', res.message || 'Não foi possível concluir', 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bx bx-check"></i>';
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Erro', 'Falha na comunicação com o servidor.', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="bx bx-check"></i>';
            });
        }
    });

    // 4. User Filter Auto-submit
    const userFilter = document.querySelector('select[name="user_id"]');
    if (userFilter) {
        userFilter.addEventListener('change', function() {
            this.form.submit();
        });
    }
});
</script>
@endsection
