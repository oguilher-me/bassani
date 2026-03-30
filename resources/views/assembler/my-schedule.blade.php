@extends('assembler.layout')

@section('title', 'Minha Agenda - Bassani')

@section('content')
{{-- Header --}}
<div class="app-header">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ url('/assembler/home') }}" class="text-white">
                <i class="bx bx-arrow-back fs-4"></i>
            </a>
            <div>
                <div class="greeting">Minha Agenda</div>
                <div class="user-name small opacity-75">{{ $assembler->name }}</div>
            </div>
        </div>
        <div class="avatar">
            {{ strtoupper(substr($assembler->name, 0, 1)) }}
        </div>
    </div>
</div>

{{-- Tab Navigation --}}
<div class="px-3 py-3">
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-dark rounded-pill px-4 flex-grow-1" id="tabToday" onclick="showTab('today')">
            <i class="bx bx-calendar-day me-1"></i> Hoje
        </button>
        <button class="btn btn-sm btn-outline-secondary rounded-pill px-4 flex-grow-1" id="tabWeek" onclick="showTab('week')">
            <i class="bx bx-calendar-week me-1"></i> Semana
        </button>
    </div>
</div>

{{-- Today's Schedule --}}
<div id="todayContent">
    @if ($dailySchedules->isEmpty())
        <div class="empty-state">
            <i class="bx bx-calendar-check"></i>
            <p>Nenhum agendamento para hoje!</p>
            <small class="text-muted">Aproveite o dia livre</small>
        </div>
    @else
        @foreach ($dailySchedules as $schedule)
        <div class="schedule-card" onclick="toggleActions('{{ $schedule->id }}')">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="schedule-time">
                    <i class="bx bx-time-five me-1"></i>
                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                </div>
                @php
                    $status = $schedule->pivot->confirmation_status ?? 'pending';
                    $statusClass = match($status) {
                        'pending' => 'status-pending',
                        'confirmed' => 'status-confirmed',
                        'started' => 'status-started',
                        'completed', 'completed_with_pendencies' => 'status-completed',
                        'cancelled' => 'status-cancelled',
                        default => 'status-pending'
                    };
                    $statusLabel = match($status) {
                        'pending' => 'Pendente',
                        'confirmed' => 'Confirmado',
                        'started' => 'Em Andamento',
                        'completed' => 'Concluído',
                        'completed_with_pendencies' => 'Concluído c/ Pend.',
                        'cancelled' => 'Cancelado',
                        default => 'Pendente'
                    };
                @endphp
                <span class="schedule-status {{ $statusClass }}">{{ $statusLabel }}</span>
            </div>
            <div class="schedule-client">
                {{ $schedule->sale->customer->full_name ?? $schedule->sale->customer->company_name ?? 'Cliente' }}
            </div>
            <div class="schedule-address">
                <i class="bx bx-map-pin me-1"></i>
                {{ $schedule->sale->customer->address_street ?? '' }}, {{ $schedule->sale->customer->address_number ?? '' }}
            </div>
            
            {{-- Action Buttons --}}
            <div id="actions-{{ $schedule->id }}" class="mt-3 pt-3 border-top" style="display: none;">
                @if ($status == 'pending')
                    <div class="d-grid gap-2">
                        <form action="{{ route('assembler.my-schedule.confirm') }}" method="POST">
                            @csrf
                            <input type="hidden" name="assembly_schedule_id" value="{{ $schedule->id }}">
                            <input type="hidden" name="action" value="confirm">
                            <button type="submit" class="btn btn-success w-100" style="border-radius: 12px; padding: 12px;">
                                <i class="bx bx-check me-2"></i>{{ __('Confirmar Presença') }}
                            </button>
                        </form>
                        <form action="{{ route('assembler.my-schedule.confirm') }}" method="POST">
                            @csrf
                            <input type="hidden" name="assembly_schedule_id" value="{{ $schedule->id }}">
                            <input type="hidden" name="action" value="cancel">
                            <button type="submit" class="btn btn-outline-danger w-100" style="border-radius: 12px; padding: 12px;">
                                <i class="bx bx-x me-2"></i>{{ __('Cancelar') }}
                            </button>
                        </form>
                    </div>
                @elseif ($status == 'confirmed')
                    <div class="d-grid">
                        <a href="{{ route('assembler.my-schedule.start.form', $schedule->id) }}" class="btn w-100" style="background: linear-gradient(135deg, #DE0802 0%, #B3211A 100%); color: white; border-radius: 12px; padding: 12px;">
                            <i class="bx bx-play-circle me-2"></i>{{ __('Iniciar Montagem') }}
                        </a>
                    </div>
                @elseif ($status == 'started')
                    <div class="d-grid gap-2">
                        <a href="{{ route('assembly-schedules.showDetails', $schedule->id) }}" class="btn btn-outline-primary w-100" style="border-radius: 12px; padding: 12px;">
                            <i class="bx bx-show me-2"></i>{{ __('Ver Detalhes') }}
                        </a>
                        <a href="{{ route('assembler.my-schedule.finish.form', $schedule->id) }}" class="btn btn-success w-100" style="border-radius: 12px; padding: 12px;">
                            <i class="bx bx-check-double me-2"></i>{{ __('Concluir Montagem') }}
                        </a>
                    </div>
                @elseif ($status == 'completed' || $status == 'completed_with_pendencies')
                    <div class="d-grid">
                        <a href="{{ route('assembly-schedules.showDetails', $schedule->id) }}" class="btn btn-outline-success w-100" style="border-radius: 12px; padding: 12px;">
                            <i class="bx bx-check-circle me-2"></i>{{ __('Ver Detalhes') }}
                        </a>
                    </div>
                @endif

                {{-- Notes --}}
                <div class="mt-3">
                    <form action="{{ route('assembler.my-schedule.notes') }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <input type="hidden" name="assembly_schedule_id" value="{{ $schedule->id }}">
                        <input type="text" name="notes" class="form-control form-control-sm" 
                               value="{{ $schedule->pivot->assembler_notes }}" 
                               placeholder="{{ __('Adicionar observação...') }}"
                               style="border-radius: 10px;">
                        <button type="submit" class="btn btn-sm btn-outline-primary" style="border-radius: 10px;">
                            <i class="bx bx-save"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>

{{-- Weekly Schedule --}}
<div id="weekContent" style="display: none;">
    @if ($weeklySchedules->isEmpty())
        <div class="empty-state">
            <i class="bx bx-calendar-check"></i>
            <p>Nenhuma montagem agendada!</p>
            <small class="text-muted">Sem compromissos para os próximos 7 dias</small>
        </div>
    @else
        @foreach ($weeklySchedules as $schedule)
        <div class="schedule-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="schedule-time">
                    <i class="bx bx-calendar me-1"></i>
                    {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d/m (D)') }}
                    <i class="bx bx-time-five ms-2 me-1"></i>
                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                </div>
                @php
                    $status = $schedule->pivot->confirmation_status ?? 'pending';
                    $statusClass = match($status) {
                        'pending' => 'status-pending',
                        'confirmed' => 'status-confirmed',
                        'started' => 'status-started',
                        'completed', 'completed_with_pendencies' => 'status-completed',
                        'cancelled' => 'status-cancelled',
                        default => 'status-pending'
                    };
                    $statusLabel = match($status) {
                        'pending' => 'Pendente',
                        'confirmed' => 'Confirmado',
                        'started' => 'Em Andamento',
                        'completed' => 'Concluído',
                        'completed_with_pendencies' => 'Concluído c/ Pend.',
                        'cancelled' => 'Cancelado',
                        default => 'Pendente'
                    };
                @endphp
                <span class="schedule-status {{ $statusClass }}">{{ $statusLabel }}</span>
            </div>
            <div class="schedule-client">
                {{ $schedule->sale->customer->full_name ?? $schedule->sale->customer->company_name ?? 'Cliente' }}
            </div>
            <div class="schedule-address">
                <i class="bx bx-map-pin me-1"></i>
                {{ $schedule->sale->customer->address_street ?? '' }}, {{ $schedule->sale->customer->address_number ?? '' }}
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection

@section('scripts')
<script>
function showTab(tab) {
    const todayContent = document.getElementById('todayContent');
    const weekContent = document.getElementById('weekContent');
    const tabToday = document.getElementById('tabToday');
    const tabWeek = document.getElementById('tabWeek');
    
    if (tab === 'today') {
        todayContent.style.display = 'block';
        weekContent.style.display = 'none';
        tabToday.className = 'btn btn-sm btn-dark rounded-pill px-4 flex-grow-1';
        tabWeek.className = 'btn btn-sm btn-outline-secondary rounded-pill px-4 flex-grow-1';
    } else {
        todayContent.style.display = 'none';
        weekContent.style.display = 'block';
        tabToday.className = 'btn btn-sm btn-outline-secondary rounded-pill px-4 flex-grow-1';
        tabWeek.className = 'btn btn-sm btn-dark rounded-pill px-4 flex-grow-1';
    }
    if (navigator.vibrate) navigator.vibrate(10);
}

function toggleActions(id) {
    const actions = document.getElementById('actions-' + id);
    if (actions) {
        const isVisible = actions.style.display === 'block';
        // Hide all actions first
        document.querySelectorAll('[id^="actions-"]').forEach(el => el.style.display = 'none');
        // Show clicked if it was hidden
        if (!isVisible) {
            actions.style.display = 'block';
            if (navigator.vibrate) navigator.vibrate(10);
        }
    }
}
</script>
@endsection
