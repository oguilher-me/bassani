@extends('assembler.layout')

@section('title', 'Bassani - Montador')

@section('content')
{{-- Header --}}
<div class="app-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <div class="greeting">Bem-vindo,</div>
            <div class="user-name">{{ Auth::user()->name }}</div>
        </div>
        <div class="avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
    </div>
</div>

{{-- Quick Stats --}}
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon bg-label-warning text-warning">
            <i class="bx bx-calendar-check"></i>
        </div>
        <div class="stat-value">{{ $todayCount ?? 0 }}</div>
        <div class="stat-label">Hoje</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-label-info text-info">
            <i class="bx bx-calendar-week"></i>
        </div>
        <div class="stat-value">{{ $weekCount ?? 0 }}</div>
        <div class="stat-label">Esta semana</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-label-success text-success">
            <i class="bx bx-check-circle"></i>
        </div>
        <div class="stat-value">{{ $completedCount ?? 0 }}</div>
        <div class="stat-label">Concluídas</div>
    </div>
    
</div>

{{-- Quick Actions --}}
<div class="quick-actions">
    <a href="{{ url('/my-schedule') }}" class="action-card">
        <div class="action-icon bg-label-primary text-primary">
            <i class="bx bx-calendar"></i>
        </div>
        <div class="action-title">Minha Agenda</div>
    </a>
    <a href="{{ url('/meu-ponto') }}" class="action-card">
        <div class="action-icon bg-label-success text-success">
            <i class="bx bx-time-five"></i>
        </div>
        <div class="action-title">Meu Ponto</div>
    </a>
    <a href="{{ url('/assembler/expenses/create') }}" class="action-card">
        <div class="action-icon bg-label-danger" style="color: #DE0802;">
            <i class="bx bx-plus"></i>
        </div>
        <div class="action-title">Lançar Despesa</div>
    </a>
    <a href="{{ url('/assembler/expenses') }}" class="action-card">
        <div class="action-icon bg-label-info text-info">
            <i class="bx bx-receipt"></i>
        </div>
        <div class="action-title">Minhas Despesas</div>
    </a>
</div>

{{-- Today's Schedule --}}
<div class="section-title">
    <i class="bx bx-calendar-event me-1 text-danger"></i> Agendamentos de Hoje
</div>

@if(isset($todaySchedules) && $todaySchedules->count() > 0)
    @foreach($todaySchedules as $schedule)
    <a href="{{ url('/my-schedule') }}" class="text-decoration-none">
        <div class="schedule-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="schedule-time">
                    <i class="bx bx-time-five me-1"></i>
                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                </div>
                @php
                    $statusClass = match($schedule->pivot->confirmation_status ?? 'pending') {
                        'pending' => 'status-pending',
                        'confirmed' => 'status-confirmed',
                        'started' => 'status-started',
                        'completed', 'completed_with_pendencies' => 'status-completed',
                        'cancelled' => 'status-cancelled',
                        default => 'status-pending'
                    };
                    $statusLabel = match($schedule->pivot->confirmation_status ?? 'pending') {
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
                {{ $schedule->sale->customer->full_name ?? $schedule->sale->customer->company_name ?? 'Cliente não informado' }}
            </div>
            <div class="schedule-address">
                <i class="bx bx-map-pin me-1"></i>
                {{ $schedule->sale->customer->address_street ?? 'Endereço não informado' }}, {{ $schedule->sale->customer->address_number ?? '' }}
            </div>
        </div>
    </a>
    @endforeach
@else
    <div class="empty-state">
        <i class="bx bx-calendar-check"></i>
        <p>Nenhum agendamento para hoje!</p>
    </div>
@endif

{{-- Recent Expenses --}}
@if(isset($recentExpenses) && $recentExpenses->count() > 0)
<div class="section-title mt-3">
    <i class="bx bx-receipt me-1 text-danger"></i> Despesas Recentes
</div>

@foreach($recentExpenses as $expense)
<div class="expense-card">
    <div class="expense-icon {{ $expense->status === 'aprovado' ? 'bg-label-success text-success' : ($expense->status === 'rejeitado' ? 'bg-label-danger text-danger' : 'bg-label-warning text-warning') }}">
        <i class="bx {{ match($expense->category) {
            'Alimentação' => 'bx-restaurant',
            'Hospedagem' => 'bx-hotel',
            'Combustível' => 'bx-gas-pump',
            'Pedágio' => 'bx-transfer',
            'Estacionamento' => 'bx-car',
            'Material Extra' => 'bx-package',
            default => 'bx-receipt'
        } }}"></i>
    </div>
    <div class="expense-details">
        <div class="expense-category">{{ $expense->category }}</div>
        <div class="expense-date">{{ $expense->date->format('d/m/Y') }} · Montagem #{{ $expense->assembly_schedule_id }}</div>
    </div>
    <div class="text-end">
        <div class="expense-amount">R$ {{ number_format($expense->amount, 2, ',', '.') }}</div>
        <span class="expense-status {{ $expense->status === 'aprovado' ? 'bg-label-success text-success' : ($expense->status === 'rejeitado' ? 'bg-label-danger text-danger' : 'bg-label-warning text-warning') }}">
            {{ $expense->status_label }}
        </span>
    </div>
</div>
@endforeach
@endif
@endsection
