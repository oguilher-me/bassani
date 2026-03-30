@extends('layouts/contentNavbarLayout')

@section('title', __('Relatório de Check-ups'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Relatório de Check-ups') }}</h4>
        <p class="text-muted mb-0">{{ __('Histórico de check-ups realizados nos veículos') }}</p>
    </div>
</div>

{{-- Statistics Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bx bx-clipboard fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('Total de Check-ups') }}</p>
                        <h4 class="mb-0 fw-bold">{{ $totalCheckups }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bx bx-check-circle fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('Aprovados') }}</p>
                        <h4 class="mb-0 fw-bold">{{ $passedCheckups }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bx bx-x-circle fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('Reprovados') }}</p>
                        <h4 class="mb-0 fw-bold">{{ $failedCheckups }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form class="row g-3 align-items-end" method="GET" action="{{ route('checkups.index') }}">
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Veículo') }}</label>
                <select name="vehicle_id" class="form-select form-select-sm">
                    <option value="">{{ __('Todos') }}</option>
                    @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}" {{ request('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                            {{ $vehicle->placa }} - {{ $vehicle->modelo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">{{ __('Status') }}</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">{{ __('Todos') }}</option>
                    <option value="passed" {{ request('status') === 'passed' ? 'selected' : '' }}>{{ __('Aprovado') }}</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>{{ __('Reprovado') }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">{{ __('Data Início') }}</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">{{ __('Data Fim') }}</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="bx bx-filter-alt me-1"></i> {{ __('Filtrar') }}
                    </button>
                    <a href="{{ route('checkups.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-refresh"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Checkups Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 py-3 px-4">#</th>
                        <th class="border-0 py-3">{{ __('Veículo') }}</th>
                        <th class="border-0 py-3">{{ __('Motorista') }}</th>
                        <th class="border-0 py-3 text-center">{{ __('Status') }}</th>
                        <th class="border-0 py-3 text-center">{{ __('Data/Hora') }}</th>
                        <th class="border-0 py-3 text-center">{{ __('Ações') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($checkups as $checkup)
                    <tr class="{{ $checkup->status === 'failed' ? 'table-danger' : '' }}">
                        <td class="py-3 px-4">
                            <span class="fw-semibold">{{ $checkup->id }}</span>
                        </td>
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar rounded-circle bg-label-secondary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                    <i class="bx bx-car"></i>
                                </div>
                                <div>
                                    <span class="fw-semibold">{{ $checkup->vehicle->placa ?? 'N/A' }}</span>
                                    <small class="d-block text-muted">{{ $checkup->vehicle->modelo ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <span>{{ $checkup->user->name ?? 'N/A' }}</span>
                        </td>
                        <td class="py-3 text-center">
                            @if($checkup->status === 'passed')
                                <span class="badge bg-label-success rounded-pill px-2 py-1">
                                    <i class="bx bx-check me-1"></i>{{ __('Aprovado') }}
                                </span>
                            @else
                                <span class="badge bg-label-danger rounded-pill px-2 py-1">
                                    <i class="bx bx-x me-1"></i>{{ __('Reprovado') }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3 text-center">
                            <span class="small">{{ $checkup->created_at->format('d/m/Y') }}</span>
                            <small class="d-block text-muted">{{ $checkup->created_at->format('H:i') }}</small>
                        </td>
                        <td class="py-3 text-center">
                            <a href="{{ route('checkups.show', $checkup) }}" class="btn btn-icon btn-sm btn-outline-primary" title="{{ __('Ver Detalhes') }}">
                                <i class="bx bx-show"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            {{ __('Nenhum check-up encontrado.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($checkups->hasPages())
    <div class="card-footer bg-transparent border-0">
        {{ $checkups->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

@section('page-script')
<style>
    /* Pagination - Bassani Theme */
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
