@extends('driver.layout')
@section('title', 'Minhas Entregas')

@section('content')
<div class="app-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <div class="greeting">Olá, {{ Auth::user()->name ?? 'Motorista' }}</div>
        </div>
        <div class="avatar">
            {{ strtoupper(substr(Auth::user()->name ?? 'M', 0, 1)) }}
        </div>
    </div>
</div>

<div class="section-title">Entregas de Hoje</div>

@if($dailyDestinations->isEmpty())
<div class="empty-state">
    <i class="bx bx-package"></i>
    <p>Nenhuma entrega para hoje</p>
</div>
@else
@foreach($dailyDestinations as $d)
<div class="delivery-card">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
            <div class="delivery-client">{{ $d->customer->company_name ?? $d->customer->full_name ?? 'Cliente' }}</div>
            <div class="delivery-address">{{ $d->address }}, {{ $d->neighborhood }}</div>
            <div class="delivery-address">{{ $d->city }} - {{ $d->state }}</div>
        </div>
        <span class="delivery-status {{ $d->status === 'completed' ? 'status-completed' : ($d->status === 'in_progress' ? 'status-in-progress' : 'status-pending') }}">
            {{ $d->status === 'completed' ? 'Concluída' : ($d->status === 'in_progress' ? 'Em Andamento' : 'Pendente') }}
        </span>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-2">
        <small class="text-muted">{{ optional($d->window_start)->format('H:i') }} - {{ optional($d->window_end)->format('H:i') }}</small>
        @if($d->status !== 'completed')
        <a href="{{ route('driver.destinations.show', $d->id) }}" class="btn-action">
            <i class="bx bx-navigation"></i> Ver Detalhes
        </a>
        @endif
    </div>
</div>
@endforeach
@endif

<div class="section-title">Próximas Entregas</div>

@if($weeklyDestinations->isEmpty())
<div class="empty-state">
    <i class="bx bx-calendar"></i>
    <p>Nenhuma entrega agendada</p>
</div>
@else
@foreach($weeklyDestinations as $d)
<div class="delivery-card">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
            <div class="delivery-client">{{ $d->customer->company_name ?? $d->customer->full_name ?? 'Cliente' }}</div>
            <div class="delivery-address">{{ $d->address }}</div>
        </div>
        <span class="delivery-status status-pending">Agendada</span>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-2">
        <small class="text-muted">{{ optional($d->window_start)->format('d/m H:i') }}</small>
    </div>
</div>
@endforeach
@endif
@endsection
