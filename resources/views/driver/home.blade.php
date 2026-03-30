@extends('driver.layout')

@section('title', 'Início - Bassani Motorista')

@section('content')
<div class="app-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <div class="greeting">Olá, {{ explode(' ', Auth::user()->name)[0] }}!</div>
            <div class="user-name small opacity-75">{{ now()->format('d/m/Y') }}</div>
        </div>
        <div class="avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
    </div>
</div>

<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon" style="background: #e3f2fd; color: #1976d2;">
            <i class="bx bx-map"></i>
        </div>
        <div class="stat-value">{{ $dailyDestinations->count() }}</div>
        <div class="stat-label">Entregas Hoje</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #fff3e0; color: #f57c00;">
            <i class="bx bx-calendar-week"></i>
        </div>
        <div class="stat-value">{{ $weeklyDestinations->count() }}</div>
        <div class="stat-label">Esta Semana</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #fce4ec; color: #c2185b;">
            <i class="bx bx-receipt"></i>
        </div>
        <div class="stat-value">{{ $pendingExpenses }}</div>
        <div class="stat-label">Despesas Pend.</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #e8f5e9; color: #388e3c;">
            <i class="bx bx-check-circle"></i>
        </div>
        <div class="stat-value">R$ {{ number_format($approvedExpenses ?? 0, 0, ',', '.') }}</div>
        <div class="stat-label">Total Aprovado</div>
    </div>
</div>

<div class="section-title">Entregas de Hoje</div>

@if($dailyDestinations->count() > 0)
    @foreach($dailyDestinations as $destination)
    <div class="delivery-card">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="delivery-time">
                <i class="bx bx-time me-1"></i>
                {{ \Carbon\Carbon::parse($destination->window_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($destination->window_end)->format('H:i') }}
            </div>
            <span class="delivery-status status-{{ match($destination->confirmation_status) {
                'confirmed' => 'completed',
                'in_progress' => 'in-progress',
                default => 'pending'
            } }}">
                {{ match($destination->confirmation_status) {
                    'confirmed' => 'Confirmado',
                    'in_progress' => 'Em Andamento',
                    'pending' => 'Pendente'
                } }}
            </span>
        </div>
        <div class="delivery-client">{{ $destination->contact_name ?? 'Cliente' }}</div>
        <div class="delivery-address mb-2">
            <i class="bx bx-map me-1"></i>
            {{ $destination->address }}
        </div>
        <div class="d-flex gap-2">
            @if($destination->confirmation_status === 'pending')
                <a href="{{ url('/driver/destinations/' . $destination->id . '/start') }}" class="btn-action start">
                    <i class="bx bx-play"></i> Iniciar
                </a>
            @elseif($destination->confirmation_status === 'in_progress')
                <a href="{{ url('/driver/destinations/' . $destination->id) }}" class="btn-action">
                    <i class="bx bx-show"></i> Ver
                </a>
                <a href="{{ url('/driver/destinations/' . $destination->id . '/finish') }}" class="btn-action finish">
                    <i class="bx bx-check"></i> Finalizar
                </a>
            @else
                <a href="{{ url('/driver/destinations/' . $destination->id) }}" class="btn-action">
                    <i class="bx bx-show"></i> Ver Detalhes
                </a>
            @endif
        </div>
    </div>
    @endforeach
@else
    <div class="empty-state">
        <i class="bx bx-package"></i>
        <p>Nenhuma entrega agendada para hoje.</p>
    </div>
@endif

<div class="section-title">Próximas Entregas</div>

@if($weeklyDestinations->count() > 0)
    @foreach($weeklyDestinations as $destination)
    <div class="delivery-card" style="border-left-color: #6c757d;">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="delivery-time" style="color: #6c757d;">
                <i class="bx bx-calendar me-1"></i>
                {{ \Carbon\Carbon::parse($destination->window_start)->format('d/m') }}
                às {{ \Carbon\Carbon::parse($destination->window_start)->format('H:i') }}
            </div>
        </div>
        <div class="delivery-client">{{ $destination->contact_name ?? 'Cliente' }}</div>
        <div class="delivery-address">
            <i class="bx bx-map me-1"></i>
            {{ $destination->address }}
        </div>
    </div>
    @endforeach
@else
    <div class="empty-state">
        <i class="bx bx-calendar"></i>
        <p>Nenhuma entrega prevista para esta semana.</p>
    </div>
@endif

<div style="height: 20px;"></div>
@endsection
