@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard de Atividades - CRM')

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css">
<style>
    .kanban-column {
        min-height: 400px;
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 1rem;
    }
    .kanban-card {
        cursor: grab;
        margin-bottom: 0.75rem;
        border-left: 4px solid #DE0802;
        border: none;
        border-radius: 8px;
    }
    .kanban-card.overdue {
        border-left-color: #1F2A44;
    }
    .fc .fc-button-primary {
        background-color: #DE0802;
        border-color: #DE0802;
    }
    .fc .fc-button-primary:hover {
        background-color: #B3211A;
        border-color: #B3211A;
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('Dashboard de Atividades') }}</h4>
            <p class="text-muted mb-0">{{ __('CRM') }}</p>
        </div>
        
        @if($isAdminOrManager)
        <form action="{{ route('crm.activities.dashboard') }}" method="GET" class="d-flex align-items-center gap-2">
            <label class="fw-medium mb-0">{{ __('Vendedor:') }}</label>
            <select name="user_id" class="form-select form-select-sm" style="width: 200px;" onchange="this.form.submit()">
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ $targetUserId == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-calendar-event fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">{{ __('Para Hoje') }}</p>
                            <h4 class="mb-0 fw-bold">{{ $todayAgenda->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar rounded-circle bg-label-danger d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-error-circle fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">{{ __('Atrasadas') }}</p>
                            <h4 class="mb-0 fw-bold">{{ $overdueActivities->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar rounded-circle bg-label-success d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-check-double fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">{{ __('Concluídas (Semana)') }}</p>
                            <h4 class="mb-0 fw-bold">{{ $completedThisWeekCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-task fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">{{ __('Tarefas Pendentes') }}</p>
                            <h4 class="mb-0 fw-bold">{{ $kanbanTasks['pending']->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Upcoming Appointments --}}
        <div class="col-12 col-xl-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bx bx-calendar text-danger me-2"></i>{{ __('Próximos Compromissos') }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 px-4">{{ __('Horário/Data') }}</th>
                                    <th class="border-0 py-3">{{ __('Tipo') }}</th>
                                    <th class="border-0 py-3">{{ __('Assunto/Oportunidade') }}</th>
                                    <th class="border-0 py-3 text-end">{{ __('Ações') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todayAgenda->merge($overdueActivities)->sortBy('due_date')->take(10) as $activity)
                                <tr id="activity-row-{{ $activity->id }}" class="{{ $activity->due_date->isPast() && $activity->status === 'pending' ? 'table-light' : '' }}">
                                    <td class="py-3 px-4">
                                        <div class="fw-semibold">{{ $activity->due_date->format('H:i') }}</div>
                                        <small class="text-muted">{{ $activity->due_date->format('d/m/Y') }}</small>
                                    </td>
                                    <td class="py-3">
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
                                        <span class="badge bg-label-{{ $type['color'] }} rounded-pill p-2">
                                            <i class="bx {{ $type['icon'] }}"></i>
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="fw-semibold">{{ $activity->subject }}</div>
                                        @if($activity->opportunity)
                                        <a href="{{ route('crm.opportunities.show', $activity->opportunity_id) }}" class="small text-muted">
                                            <i class="bx bx-link-external me-1"></i>{{ $activity->opportunity->title }}
                                        </a>
                                        @endif
                                    </td>
                                    <td class="py-3 text-end">
                                        <button type="button" class="btn btn-sm btn-success complete-task-btn" 
                                                data-id="{{ $activity->id }}" title="{{ __('Concluir') }}">
                                            <i class="bx bx-check"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bx bx-calendar-check fs-1 d-block mb-2"></i>
                                        {{ __('A agenda está limpa para hoje.') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Weekly Effort Metrics --}}
        <div class="col-12 col-xl-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bx bx-bar-chart-alt text-danger me-2"></i>{{ __('Esforço na Semana') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div id="effortChart"></div>
                    <ul class="list-unstyled mt-3">
                        <li class="d-flex mb-3 pb-2 border-bottom">
                            <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bx bx-phone"></i>
                            </div>
                            <div class="d-flex w-100 align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-0">{{ __('Ligações') }}</h6>
                                    <small class="text-muted">{{ __('Realizadas') }}</small>
                                </div>
                                <span class="fw-bold">{{ $weeklyMetrics['calls'] }}</span>
                            </div>
                        </li>
                        <li class="d-flex mb-3 pb-2 border-bottom">
                            <div class="avatar rounded-circle bg-label-success d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bx bxl-whatsapp"></i>
                            </div>
                            <div class="d-flex w-100 align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-0">WhatsApp</h6>
                                    <small class="text-muted">{{ __('Mensagens') }}</small>
                                </div>
                                <span class="fw-bold">{{ $weeklyMetrics['whatsapp'] }}</span>
                            </div>
                        </li>
                        <li class="d-flex mb-3 pb-2 border-bottom">
                            <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bx bx-calendar"></i>
                            </div>
                            <div class="d-flex w-100 align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-0">{{ __('Reuniões') }}</h6>
                                    <small class="text-muted">{{ __('Realizadas') }}</small>
                                </div>
                                <span class="fw-bold">{{ $weeklyMetrics['meetings'] }}</span>
                            </div>
                        </li>
                        <li class="d-flex">
                            <div class="avatar rounded-circle bg-label-danger d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bx bx-map"></i>
                            </div>
                            <div class="d-flex w-100 align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-0">{{ __('Visitas') }}</h6>
                                    <small class="text-muted">{{ __('Técnicas/Comerciais') }}</small>
                                </div>
                                <span class="fw-bold">{{ $weeklyMetrics['visits'] }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Mini-Kanban for Tasks --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bx bx-task text-danger me-2"></i>{{ __('Fluxo de Tarefas') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                {{-- Pending --}}
                <div class="col-md-4">
                    <div class="kanban-column">
                        <h6 class="text-uppercase fw-bold mb-3 text-muted">
                            <i class="bx bx-list-ul me-2"></i>{{ __('Pendentes') }} ({{ $kanbanTasks['pending']->count() }})
                        </h6>
                        <div class="kanban-items" data-status="pending" id="kanban-pending">
                            @foreach($kanbanTasks['pending'] as $task)
                            <div class="card kanban-card shadow-sm" data-id="{{ $task->id }}">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-label-primary rounded-pill px-2 py-1">
                                            <i class="bx bx-task me-1"></i>{{ __('Tarefa') }}
                                        </span>
                                        <small class="text-muted">{{ $task->due_date ? $task->due_date->format('d/m') : __('S/ Data') }}</small>
                                    </div>
                                    <p class="mb-2 fw-medium">{{ $task->subject }}</p>
                                    @if($task->opportunity)
                                    <a href="{{ route('crm.opportunities.show', $task->opportunity_id) }}" class="small text-muted text-truncate d-block">
                                        {{ $task->opportunity->title }}
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- In Progress/Overdue --}}
                <div class="col-md-4">
                    <div class="kanban-column" style="background-color: #fff3e0;">
                        <h6 class="text-uppercase fw-bold mb-3" style="color: #e65100;">
                            <i class="bx bx-time-five me-2"></i>{{ __('Em Andamento') }} ({{ $kanbanTasks['overdue']->count() }})
                        </h6>
                        <div class="kanban-items" data-status="pending" id="kanban-inprogress">
                            @foreach($kanbanTasks['overdue'] as $task)
                            <div class="card kanban-card overdue shadow-sm" data-id="{{ $task->id }}">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-label-warning rounded-pill px-2 py-1">
                                            <i class="bx bx-task me-1"></i>{{ __('Tarefa') }}
                                        </span>
                                        <small class="text-danger fw-bold">{{ $task->due_date->format('d/m') }}</small>
                                    </div>
                                    <p class="mb-2 fw-medium">{{ $task->subject }}</p>
                                    @if($task->opportunity)
                                    <a href="{{ route('crm.opportunities.show', $task->opportunity_id) }}" class="small text-muted text-truncate d-block">
                                        {{ $task->opportunity->title }}
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Completed --}}
                <div class="col-md-4">
                    <div class="kanban-column" style="background-color: #e8f5e9;">
                        <h6 class="text-uppercase fw-bold mb-3 text-success">
                            <i class="bx bx-check-circle me-2"></i>{{ __('Concluídas') }} (7 {{ __('dias') }})
                        </h6>
                        <div class="kanban-items" data-status="completed" id="kanban-completed">
                            @foreach($kanbanTasks['completed'] as $task)
                            <div class="card kanban-card border-success shadow-none opacity-75" data-id="{{ $task->id }}" style="border-left-color: #4caf50;">
                                <div class="card-body p-3">
                                    <p class="mb-1 text-decoration-line-through text-muted">{{ $task->subject }}</p>
                                    <small class="text-muted d-block">
                                        <i class="bx bx-check me-1"></i>{{ __('Concluído') }} {{ $task->completed_at->format('d/m') }}
                                    </small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Monthly Calendar --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bx bx-calendar text-danger me-2"></i>{{ __('Calendário de Reuniões e Visitas') }}
            </h5>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
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
    // Kanban Sortable
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
                
                if (newStatus === 'completed') {
                    markAsComplete(activityId, itemEl, evt.from);
                }
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
                    title: '{{ __("Tarefa Concluída!") }}',
                    timer: 1000,
                    showConfirmButton: false,
                    confirmButtonColor: '#DE0802'
                });
                element.classList.add('border-success', 'shadow-none', 'opacity-75');
                element.querySelector('.card-body p').classList.add('text-decoration-line-through');
            } else {
                Swal.fire('{{ __("Erro") }}', res.message || '{{ __("Erro ao atualizar") }}', 'error');
                fromColumn.appendChild(element);
            }
        })
        .catch(err => {
            console.error(err);
            fromColumn.appendChild(element);
        });
    }

    // Effort Chart
    const effortOptions = {
        series: [{
            name: '{{ __("Interações") }}',
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
            categories: ['{{ __("Ligações") }}', 'WhatsApp', '{{ __("Reuniões") }}', '{{ __("Visitas") }}', '{{ __("Tarefas") }}']
        },
        colors: ['#DE0802'],
        fill: {
            opacity: 0.3
        }
    };
    const effortChart = new ApexCharts(document.querySelector("#effortChart"), effortOptions);
    effortChart.render();

    // FullCalendar
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            buttonText: {
                today: '{{ __("Hoje") }}',
                month: '{{ __("Mês") }}',
                week: '{{ __("Semana") }}',
                day: '{{ __("Dia") }}'
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

    // Complete Task Button
    document.addEventListener('click', function(e) {
        if (e.target.closest('.complete-task-btn')) {
            const btn = e.target.closest('.complete-task-btn');
            const id = btn.dataset.id;
            const row = document.getElementById(`activity-row-${id}`);

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

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
                        title: '{{ __("Sucesso!") }}',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false,
                        confirmButtonColor: '#DE0802'
                    }).then(() => {
                        if (row) {
                            row.style.opacity = '0';
                            setTimeout(() => row.remove(), 500);
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire('{{ __("Erro") }}', res.message || '{{ __("Não foi possível concluir") }}', 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bx bx-check"></i>';
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('{{ __("Erro") }}', '{{ __("Falha na comunicação com o servidor.") }}', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="bx bx-check"></i>';
            });
        }
    });
});
</script>
@endsection
