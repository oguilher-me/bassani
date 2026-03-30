@extends('layouts/contentNavbarLayout')

@section('title', 'Espelho de Ponto')

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Espelho de Ponto') }}</h4>
        <p class="text-muted mb-0">{{ __('Controle de horários dos colaboradores') }}</p>
    </div>
</div>

{{-- Statistics Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-user fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">{{ __('Funcionários') }}</p>
                        <h4 class="mb-0 fw-bold">{{ $totalEmployees }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-success d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-time fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">{{ __('Horas Totais') }}</p>
                        <h4 class="mb-0 fw-bold">{{ number_format($totalHours, 1, ',', '.') }}h</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-calendar fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">{{ __('Período') }}</p>
                        <h4 class="mb-0 fw-bold small">{{ \Carbon\Carbon::parse($startDate)->format('d/m') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('timeclock.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Funcionário') }}</label>
                <select name="user_id" class="form-select form-select-sm">
                    <option value="">{{ __('Todos') }}</option>
                    @foreach($assemblers as $assembler)
                        <option value="{{ $assembler->id }}" {{ $userId == $assembler->id ? 'selected' : '' }}>
                            {{ $assembler->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Data Início') }}</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Data Fim') }}</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="bx bx-filter me-1"></i> {{ __('Filtrar') }}
                    </button>
                    <a href="{{ route('timeclock.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-refresh"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Clock Mirror Data --}}
@if($clockMirror->count() > 0)
    @foreach($clockMirror as $userData)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-user text-danger me-2"></i>{{ $userData['user']->name }}
                </h6>
                <span class="badge bg-label-primary rounded-pill px-3 py-2">
                    {{ number_format($userData['total_hours'], 1, ',', '.') }}h {{ __('no período') }}
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 py-3 px-4">{{ __('Data') }}</th>
                            <th class="border-0 py-3 text-center">{{ __('Entrada') }}</th>
                            <th class="border-0 py-3 text-center">{{ __('Pausa') }}</th>
                            <th class="border-0 py-3 text-center">{{ __('Retorno') }}</th>
                            <th class="border-0 py-3 text-center">{{ __('Saída') }}</th>
                            <th class="border-0 py-3 text-center">{{ __('Horas Trabalhadas') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userData['days'] as $dayData)
                        <tr>
                            <td class="py-3 px-4">
                                <div class="fw-semibold">{{ $dayData['date_formatted'] }}</div>
                                <small class="text-muted">{{ $dayData['day_name'] }}</small>
                            </td>
                            <td class="py-3 text-center">
                                @php
                                    $startClock = $dayData['clocks']->firstWhere('type', 'start');
                                @endphp
                                @if($startClock)
                                    <span class="badge bg-label-success rounded-pill px-2 py-1">
                                        {{ $startClock->clock_in_at->format('H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted">--:--</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                @php
                                    $pauseClock = $dayData['clocks']->firstWhere('type', 'pause');
                                @endphp
                                @if($pauseClock)
                                    <span class="badge bg-label-warning rounded-pill px-2 py-1">
                                        {{ $pauseClock->clock_in_at->format('H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted">--:--</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                @php
                                    $resumeClock = $dayData['clocks']->firstWhere('type', 'resume');
                                @endphp
                                @if($resumeClock)
                                    <span class="badge bg-label-info rounded-pill px-2 py-1">
                                        {{ $resumeClock->clock_in_at->format('H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted">--:--</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                @php
                                    $endClock = $dayData['clocks']->firstWhere('type', 'end');
                                @endphp
                                @if($endClock)
                                    <span class="badge bg-label-danger rounded-pill px-2 py-1">
                                        {{ $endClock->clock_in_at->format('H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted">--:--</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                <span class="fw-bold" style="color: #DE0802;">
                                    {{ $dayData['worked_hours']['worked_formatted'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="5" class="text-end py-3 px-4 fw-bold">{{ __('Total no Período:') }}</td>
                            <td class="py-3 text-center">
                                <span class="fw-bold fs-5" style="color: #DE0802;">
                                    {{ number_format($userData['total_hours'], 1, ',', '.') }}h
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bx bx-time-five fs-1 d-block mb-2 text-muted"></i>
            <p class="text-muted mb-0">{{ __('Nenhum registro encontrado para o período selecionado.') }}</p>
        </div>
    </div>
@endif
@endsection

@section('page-script')
<style>
    .page-item.active .page-link {
        background-color: #DE0802;
        border-color: #DE0802;
    }
    .page-link {
        color: #1F2A44;
    }
    .page-link:hover {
        color: #DE0802;
    }
</style>
@endsection
