@extends('layouts/contentNavbarLayout')

@section('title', __('Listagem de Manutenções'))

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('Manutenções / ') }}</span> {{ __('Listagem') }}</h4>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('Filtros de Manutenção') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('maintenances.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="filter_vehicle" class="form-label">{{ __('Veículo') }}</label>
                        <select class="form-select" id="filter_vehicle" name="vehicle_id">
                            <option value="">{{ __('Todos os Veículos') }}</option>
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ (request('vehicle_id') == $vehicle->id) ? 'selected' : '' }}>
                                    {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="filter_type" class="form-label">{{ __('Tipo de Manutenção') }}</label>
                        <select class="form-select" id="filter_type" name="type">
                            <option value="">{{ __('Todos os Tipos') }}</option>
                            <option value="Preventiva" {{ (request('type') == 'Preventiva') ? 'selected' : '' }}>{{ __('Preventiva') }}</option>
                            <option value="Corretiva" {{ (request('type') == 'Corretiva') ? 'selected' : '' }}>{{ __('Corretiva') }}</option>
                            <option value="Revisão" {{ (request('type') == 'Revisão') ? 'selected' : '' }}>{{ __('Revisão') }}</option>
                            <option value="Troca de Óleo" {{ (request('type') == 'Troca de Óleo') ? 'selected' : '' }}>{{ __('Troca de Óleo') }}</option>
                            <option value="Pneus" {{ (request('type') == 'Pneus') ? 'selected' : '' }}>{{ __('Pneus') }}</option>
                            <option value="Elétrica" {{ (request('type') == 'Elétrica') ? 'selected' : '' }}>{{ __('Elétrica') }}</option>
                            <option value="Funilaria" {{ (request('type') == 'Funilaria') ? 'selected' : '' }}>{{ __('Funilaria') }}</option>
                            <option value="Outros" {{ (request('type') == 'Outros') ? 'selected' : '' }}>{{ __('Outros') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="filter_status" class="form-label">{{ __('Status') }}</label>
                        <select class="form-select" id="filter_status" name="status">
                            <option value="">{{ __('Todos os Status') }}</option>
                            <option value="Agendada" {{ (request('status') == 'Agendada') ? 'selected' : '' }}>{{ __('Agendada') }}</option>
                            <option value="Em execução" {{ (request('status') == 'Em execução') ? 'selected' : '' }}>{{ __('Em execução') }}</option>
                            <option value="Concluída" {{ (request('status') == 'Concluída') ? 'selected' : '' }}>{{ __('Concluída') }}</option>
                            <option value="Cancelada" {{ (request('status') == 'Cancelada') ? 'selected' : '' }}>{{ __('Cancelada') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="filter_supplier" class="form-label">{{ __('Fornecedor') }}</label>
                        <input type="text" class="form-control" id="filter_supplier" name="supplier" value="{{ request('supplier') }}" placeholder="{{ __('Nome do Fornecedor') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="filter_start_date" class="form-label">{{ __('Data Inicial') }}</label>
                        <input type="date" class="form-control" id="filter_start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="filter_end_date" class="form-label">{{ __('Data Final') }}</label>
                        <input type="date" class="form-control" id="filter_end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">{{ __('Aplicar Filtros') }}</button>
                        <a href="{{ route('maintenances.index') }}" class="btn btn-outline-secondary">{{ __('Limpar Filtros') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('Manutenções Registradas') }}</h5>
            <a href="{{ route('maintenances.create') }}" class="btn btn-primary">{{ __('Registrar Nova Manutenção') }}</a>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table" id="maintenances-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>{{ __('Veículo') }}</th>
                            <th>{{ __('Tipo') }}</th>
                            <th>{{ __('Data') }}</th>
                            <th>{{ __('KM') }}</th>
                            <th>{{ __('Custo') }}</th>
                            <th>{{ __('Fornecedor') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-center" style="width: 5%;">{{ __('Ações') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($maintenances as $maintenance)
                            <tr>
                                <td>{{ $maintenance->vehicle->modelo }} ({{ $maintenance->vehicle->placa }})</td>
                                <td>{{ $maintenance->type }}</td>
                                <td>{{ \Carbon\Carbon::parse($maintenance->maintenance_date)->format('d/m/Y') }}</td>
                                <td>{{ number_format($maintenance->mileage, 0, ',', '.') }}</td>
                                <td>R$ {{ number_format($maintenance->cost, 2, ',', '.') }}</td>
                                <td>{{ $maintenance->supplier }}</td>
                                <td>
                                    @if ($maintenance->status == 'Concluída')
                                        <span class="badge bg-label-success">{{ __('Concluída') }}</span>
                                    @elseif ($maintenance->status == 'Em execução')
                                        <span class="badge bg-label-warning">{{ __('Em execução') }}</span>
                                    @elseif ($maintenance->status == 'Agendada')
                                        <span class="badge bg-label-info">{{ __('Agendada') }}</span>
                                    @else
                                        <span class="badge bg-label-danger">{{ __('Cancelada') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('maintenances.show', $maintenance->id) }}" class="btn btn-icon item-show"><i class="icon-base bx bx-show icon-sm text-primary"></i></a>
                                    <a href="{{ route('maintenances.edit', $maintenance->id) }}" class="btn btn-icon item-edit"><i class="icon-base bx bx-edit icon-sm text-warning"></i></a>
                                    <form action="{{ route('maintenances.destroy', $maintenance->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon item-delete"><i class="icon-base bx bx-trash icon-sm text-danger"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">{{ __('Nenhuma manutenção encontrada.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $maintenances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="module">
    $(document).ready(function() {
        $('#maintenances-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            },
            paging: false, // Desabilita a paginação do DataTables, pois o Laravel já faz isso
            info: false, // Desabilita a informação de "Mostrando X de Y registros"
            searching: false // Desabilita a busca do DataTables, pois os filtros já fazem isso
        });

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: '{{ __('Tem certeza?') }}',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
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