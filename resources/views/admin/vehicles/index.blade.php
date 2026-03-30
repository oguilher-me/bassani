@extends('layouts/contentNavbarLayout')

@section('title', __('Veículos'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Veículos') }}</h4>
        <p class="text-muted mb-0">{{ __('Gerenciamento da frota de veículos') }}</p>
    </div>
    <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> {{ __('Novo Veículo') }}
    </a>
</div>

{{-- Data Table Card --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table display" id="vehicles-table">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4">{{ __('Placa') }}</th>
                        <th class="py-3">{{ __('Modelo') }}</th>
                        <th class="py-3">{{ __('Marca') }}</th>
                        <th class="py-3 text-center">{{ __('KM Atual') }}</th>
                        <th class="py-3 text-center">{{ __('Cap. Cúbica') }}</th>
                        <th class="py-3 text-center">{{ __('Status') }}</th>
                        <th class="py-3 text-center">{{ __('Aquisição') }}</th>
                        <th class="py-3 text-center">{{ __('Ações') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($vehicles as $vehicle)
                        <tr>
                            <td class="py-3 px-4">
                                <span class="fw-semibold">{{ $vehicle->placa }}</span>
                            </td>
                            <td class="py-3">{{ $vehicle->modelo }}</td>
                            <td class="py-3">{{ $vehicle->carBrand->name ?? 'N/A' }}</td>
                            <td class="py-3 text-center">
                                <span class="badge bg-label-info">{{ number_format($vehicle->quilometragem_atual, 0, ',', '.') }} KM</span>
                            </td>
                            <td class="py-3 text-center">
                                @if($vehicle->cubic_capacity !== null)
                                    <span class="badge bg-label-secondary">{{ number_format($vehicle->cubic_capacity, 2, ',', '.') }} m³</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                @if($vehicle->status == 'Ativo')
                                    <span class="badge bg-success rounded-pill">{{ __('Ativo') }}</span>
                                @elseif($vehicle->status == 'Em manutenção')
                                    <span class="badge bg-warning rounded-pill">{{ __('Em manutenção') }}</span>
                                @else
                                    <span class="badge bg-danger rounded-pill">{{ __('Inativo') }}</span>
                                @endif
                            </td>
                            <td class="py-3 text-center text-muted small">
                                {{ $vehicle->data_aquisicao }}
                            </td>
                            <td class="py-3 text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('vehicles.show', $vehicle->id) }}" class="btn btn-icon btn-sm btn-outline-primary" title="{{ __('Ver') }}">
                                        <i class="bx bx-show"></i>
                                    </a>
                                    <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-icon btn-sm btn-outline-warning" title="{{ __('Editar') }}">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <a href="{{ route('vehicle_usages.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-icon btn-sm btn-outline-info" title="{{ __('Uso') }}">
                                        <i class="bx bx-car"></i>
                                    </a>
                                    <a href="{{ route('fuel_ups.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-icon btn-sm btn-outline-success" title="{{ __('Abastecimento') }}">
                                        <i class="bx bx-gas-pump"></i>
                                    </a>
                                    <a href="{{ route('fleet_report.vehicle_detailed_report', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-icon btn-sm btn-outline-secondary" title="{{ __('Relatório') }}">
                                        <i class="bx bx-chart"></i>
                                    </a>
                                    <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" class="d-inline delete-form">
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
    </div>
</div>
@endsection

@section('page-script')
<style>
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #DE0802 !important;
        border-color: #DE0802 !important;
        color: #fff !important;
        border-radius: 4px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f5f5f5 !important;
        border-color: #DE0802 !important;
        color: #DE0802 !important;
        border-radius: 4px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #ccc !important;
    }
</style>
<script type="module">
    $(document).ready(function() {
        $('#vehicles-table').DataTable({
            language: {
                url: '{{ asset('assets/js/datatables/pt-BR.json') }}'
            },
            order: [[0, 'asc']],
            pageLength: 10
        });

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: '{{ __('Tem certeza?') }}',
                text: "{{ __('Você não poderá reverter isso!') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DE0802',
                cancelButtonColor: '#1F2A44',
                confirmButtonText: '{{ __('Sim, excluir!') }}',
                cancelButtonText: '{{ __('Cancelar') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
