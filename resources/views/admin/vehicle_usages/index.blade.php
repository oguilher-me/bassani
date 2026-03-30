@extends('layouts/contentNavbarLayout')

@section('title', __('Controle de Uso de Veículos'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Controle de Uso de Veículos') }}</h4>
        <p class="text-muted mb-0">{{ __('Gerenciamento de saídas e retornos da frota') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-car me-1"></i> {{ __('Veículos') }}
        </a>
        <a href="{{ route('vehicle_usages.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> {{ __('Registrar Uso') }}
        </a>
    </div>
</div>

{{-- Stats Cards --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-list-ul fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block mb-1 small text-muted">{{ __('Total de Registros') }}</span>
                        <span class="fw-bold fs-4">{{ $vehicleUsages->total() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-loader fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block mb-1 small text-muted">{{ __('Em Andamento') }}</span>
                        <span class="fw-bold fs-4">{{ $vehicleUsages->where('trip_status', 'Em andamento')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-success d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-check-circle fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block mb-1 small text-muted">{{ __('Finalizadas') }}</span>
                        <span class="fw-bold fs-4">{{ $vehicleUsages->where('trip_status', 'Finalizada')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-calendar fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block mb-1 small text-muted">{{ __('Este Mês') }}</span>
                        <span class="fw-bold fs-4">{{ $vehicleUsages->where('departure_date', '>=', now()->startOfMonth())->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-history text-danger me-2"></i>{{ __('Histórico de Uso') }}
                </h6>
                <span class="badge bg-label-primary">{{ $vehicleUsages->total() }} {{ __('registro(s)') }}</span>
            </div>
            <div class="card-body p-0">
                @if($vehicleUsages->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="vehicle-usages-table">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 px-4">{{ __('Veículo') }}</th>
                                    <th class="border-0 py-3">{{ __('Motorista') }}</th>
                                    <th class="border-0 py-3">{{ __('Saída') }}</th>
                                    <th class="border-0 py-3">{{ __('Retorno') }}</th>
                                    <th class="border-0 py-3">{{ __('Rota') }}</th>
                                    <th class="border-0 py-3">{{ __('Status') }}</th>
                                    <th class="border-0 py-3 text-center">{{ __('Ações') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vehicleUsages as $vehicleUsage)
                                    <tr>
                                        <td class="py-3 px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                                    <i class="bx bx-car"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-semibold d-block">{{ $vehicleUsage->vehicle->modelo ?? '-' }}</span>
                                                    <small class="text-muted">{{ $vehicleUsage->vehicle->placa ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="fw-semibold">{{ $vehicleUsage->driver->full_name ?? '-' }}</span>
                                        </td>
                                        <td class="py-3">
                                            <span class="d-block">{{ \Carbon\Carbon::parse($vehicleUsage->departure_date)->format('d/m/Y') }}</span>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($vehicleUsage->departure_date)->format('H:i') }} · {{ number_format($vehicleUsage->departure_mileage, 0, ',', '.') }} km</small>
                                        </td>
                                        <td class="py-3">
                                            @if($vehicleUsage->return_date)
                                                <span class="d-block">{{ \Carbon\Carbon::parse($vehicleUsage->return_date)->format('d/m/Y') }}</span>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($vehicleUsage->return_date)->format('H:i') }} · {{ number_format($vehicleUsage->return_mileage ?? 0, 0, ',', '.') }} km</small>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            <span class="text-muted small">{{ $vehicleUsage->route_destination ?? '—' }}</span>
                                        </td>
                                        <td class="py-3">
                                            @if($vehicleUsage->trip_status == 'Em andamento')
                                                <span class="badge bg-warning rounded-pill px-3 py-2">{{ __('Em andamento') }}</span>
                                            @else
                                                <span class="badge bg-success rounded-pill px-3 py-2">{{ __('Finalizada') }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('vehicle_usages.show', $vehicleUsage->id) }}" class="btn btn-icon btn-sm btn-outline-info" title="{{ __('Ver') }}">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="{{ route('vehicle_usages.edit', $vehicleUsage->id) }}" class="btn btn-icon btn-sm btn-outline-primary" title="{{ __('Editar') }}">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <form action="{{ route('vehicle_usages.destroy', $vehicleUsage->id) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-sm btn-outline-danger" title="{{ __('Excluir') }}">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-transparent border-0 py-3">
                        {{ $vehicleUsages->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bx bx-history fs-1 text-muted opacity-50"></i>
                        <p class="text-muted mt-2">{{ __('Nenhum registro de uso encontrado') }}</p>
                        <a href="{{ route('vehicle_usages.create') }}" class="btn btn-primary mt-2">
                            <i class="bx bx-plus me-1"></i> {{ __('Registrar Primeiro Uso') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="module">
    $(document).ready(function() {
        $('#vehicle-usages-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            },
            ordering: false,
            paging: false,
            info: false
        });

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: '{{ __('Tem certeza?') }}',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DE0802',
                cancelButtonColor: '#1F2A44',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection