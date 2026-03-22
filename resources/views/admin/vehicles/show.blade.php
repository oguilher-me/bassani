@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes do Veículo'))

@section('content')
<div class="row mb-6 gy-6">
    <!-- Vehicle Details -->
    <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
        <div class="card mb-4">
            <div class="card-body">
                <div class="user-avatar-section">
                    <div class="d-flex align-items-center flex-column">
                        <img class="img-fluid rounded mb-3 mt-4" src="{{ asset('assets/img/icons/car_placeholder.webp') }}" height="120" width="120" alt="Vehicle avatar" />
                        <div class="user-info text-center">
                            <h4 class="mb-2">{{ $vehicle->placa }}</h4>
                            <span class="badge bg-label-secondary">{{ $vehicle->modelo }}</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-around flex-wrap mt-4 pt-3">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bx bx-tachometer bx-sm me-1"></i>
                        <div class="d-flex flex-column">
                            <h5 class="mb-0">{{ number_format($vehicle->quilometragem_atual, 0, ',', '.') }} KM</h5>
                            <small>{{ __('Quilometragem Atual') }}</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar bx-sm me-1"></i>
                        <div class="d-flex flex-column">
                            <h5 class="mb-0">{{ \Carbon\Carbon::parse($vehicle->data_aquisicao)->format('d/m/Y') }}</h5>
                            <small>{{ __('Data de Aquisição') }}</small>
                        </div>
                    </div>
                </div>
                <h5 class="pb-3 border-bottom mb-3">{{ __('Detalhes') }}</h5>
                <div class="info-container">
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2">
                            <span class="fw-medium me-1">{{ __('Marca:') }}</span>
                            <span>{{ $vehicle->carBrand->name ?? 'N/A' }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="fw-medium me-1">{{ __('Ano de Fabricação:') }}</span>
                            <span>{{ $vehicle->ano_fabricacao }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="fw-medium me-1">{{ __('Status:') }}</span>
                            @if ($vehicle->status == 'Ativo')
                                <span class="badge bg-label-success">{{ __('Ativo') }}</span>
                            @elseif ($vehicle->status == 'Em manutenção')
                                <span class="badge bg-label-warning">{{ __('Em manutenção') }}</span>
                            @else
                                <span class="badge bg-label-danger">{{ __('Inativo') }}</span>
                            @endif
                        </li>
                        <li class="mb-2">
                            <span class="fw-medium me-1">{{ __('Observações:') }}</span>
                            <span>{{ $vehicle->observacoes ?? 'N/A' }}</span>
                        </li>
                    </ul>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-primary me-3">{{ __('Editar Detalhes') }}</a>
                        {{-- <a href="{{ route('maintenances.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-success">{{ __('Registrar Manutenção') }}</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Vehicle Details -->

    <!-- Vehicle Overview -->
    <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
        <div class="row">
            <!-- Average Fuel Consumption -->
            <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-gas-pump fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">{{ number_format($averageFuelConsumption, 2, ',', '.') }} L/100KM</h5>
                                    <small class="text-muted">{{ __('Média de Consumo') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Last Maintenance -->
            <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-warning"><i class="bx bx-wrench fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">
                                        @if ($lastMaintenance)
                                            {{ \Carbon\Carbon::parse($lastMaintenance->maintenance_date)->format('d/m/Y') }}
                                        @else
                                            {{ __('N/A') }}
                                        @endif
                                    </h5>
                                    <small class="text-muted">{{ __('Última Manutenção') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Upcoming Maintenances -->
            <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-info"><i class="bx bx-bell fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    @if($upcomingMaintenances->isNotEmpty())
                                        <h5 class="card-title mb-0 me-2">{{ $upcomingMaintenances->first()->maintenance_date->format('d/m/Y') }}</h5>
                                    @else
                                        <h5 class="card-title mb_0 me-2">Nenhuma manutenção agendada</h5>
                                    @endif
                                    <small class="text-muted">{{ __('Próximas Manutenções') }} ({{ $upcomingMaintenances->count() }})</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Annual Costs -->
            <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-danger"><i class="bx bx-dollar fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">R$ {{ number_format($annualCosts, 2, ',', '.') }}</h5>
                                    <small class="text-muted">{{ __('Custos Anuais') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Histórico de Uso do Veículo -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Histórico de Uso do Veículo') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="vehicle-usages-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>{{ __('Motorista') }}</th>
                                <th>{{ __('Saída') }}</th>
                                <th>{{ __('Retorno') }}</th>
                                <th>{{ __('Total KM') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center" style="width: 5%;">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($vehicleUsages as $usage)
                                <tr>
                                    <td>{{ $usage->driver->full_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($usage->departure_date)->format('d/m/Y H:i') }} </td>
                                    <td>
                                        @if ($usage->return_date)
                                            {{ \Carbon\Carbon::parse($usage->return_date)->format('d/m/Y H:i') }}
                                        @else
                                            {{ __('Em andamento') }}
                                        @endif
                                    </td>
                                    <td>{{ $usage->return_mileage - $usage->departure_mileage }} KM</td>
                                    <td>
                                        @if ($usage->trip_status == 'Em andamento')
                                            <span class="badge bg-label-warning">{{ __('Em andamento') }}</span>
                                        @elseif ($usage->trip_status == 'Finalizada')
                                            <span class="badge bg-label-success">{{ __('Finalizada') }}</span>
                                        @else
                                            <span class="badge bg-label-danger">{{ __('Cancelada') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('vehicle_usages.show', $usage->id) }}" class="btn btn-icon item-show"><i class="icon-base bx bx-show icon-sm text-primary"></i></a>
                                        <a href="{{ route('vehicle_usages.edit', $usage->id) }}" class="btn btn-icon item-edit"><i class="icon-base bx bx-edit icon-sm text-warning"></i></a>
                                        <form action="{{ route('vehicle_usages.destroy', $usage->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon item-delete"><i class="icon-base bx bx-trash icon-sm text-danger"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Histórico de Abastecimentos -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Histórico de Abastecimentos') }}</h5>
                <a href="{{ route('fuel_ups.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-primary">{{ __('Registrar Abastecimento') }}</a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="fuel-ups-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>{{ __('Data/Hora') }}</th>
                                <th class="text-center">{{ __('QTD. (L)') }}</th>
                                <th class="text-center">{{ __('(R$)') }}</th>
                                <th class="text-center">{{ __('(R$/L)') }}</th>
                                <th class="text-center">{{ __('Tipo') }}</th>
                                <th class="text-center">{{ __('Local/Posto') }}</th>
                                <th class="text-center">{{ __('Forma Pagamento') }}</th>
                                <th class="text-center" style="width: 5%;">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($fuelUps as $fuelUp)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($fuelUp->fuel_up_date)->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">{{ number_format($fuelUp->quantity, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ number_format($fuelUp->total_value, 2, ',', '.') }}</td>
                                    <td class="text-center">{{ number_format($fuelUp->unit_value, 2, ',', '.') }}</td>
                                    <td class="text-center">{{ $fuelUp->fuel_up_type }}</td>
                                    <td class="text-center">{{ $fuelUp->station_name ?? '-' }}</td>
                                    <td class="text-center">{{ $fuelUp->payment_method }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('fuel_ups.show', $fuelUp->id) }}" class="btn btn-icon item-show"><i class="icon-base bx bx-show icon-sm text-primary"></i></a>
                                        <a href="{{ route('fuel_ups.edit', $fuelUp->id) }}" class="btn btn-icon item-edit"><i class="icon-base bx bx-edit icon-sm text-warning"></i></a>
                                        <form action="{{ route('fuel_ups.destroy', $fuelUp->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon item-delete"><i class="icon-base bx bx-trash icon-sm text-danger"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Histórico de Manutenções -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Histórico de Manutenções') }}</h5>
                <a href="{{ route('maintenances.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-primary">{{ __('Registrar Manutenção') }}</a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="maintenances-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>{{ __('Data') }}</th>
                                <th>{{ __('Tipo') }}</th>
                                <th>{{ __('KM') }}</th>
                                <th>{{ __('Custo') }}</th>
                                <th>{{ __('Fornecedor') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center" style="width: 5%;">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($maintenances as $maintenance)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($maintenance->maintenance_date)->format('d/m/Y') }}</td>
                                    <td>{{ $maintenance->type }}</td>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Histórico de Multas') }}</h5>
                <a href="{{ route('vehicle_fines.create') }}" class="btn btn-primary">{{ __('Registrar Multa') }}</a>
            </div>
            <div class="card-body">

                <div class="table-responsive text-nowrap">
                    <table class="table" id="vehicle-fines-table">
                        <thead>
                            <tr>
                                <th>{{ __('Número da Multa') }}</th>
                                <th>{{ __('Data da Infração') }}</th>
                                <th>{{ __('Tipo da Multa') }}</th>
                                <th>{{ __('Valor da Multa') }}</th>
                                <th>{{ __('Status do Pagamento') }}</th>
                                <th class="text-center" style="width: 5%;">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($vehicleFines as $fine)
                                <tr>
                                    <td>{{ $fine->fine_number }}</td>
                                    <td>{{ \Carbon\Carbon::parse($fine->infraction_date)->format('d/m/Y') }}</td>
                                    <td>{{ $fine->fine_type }}</td>
                                    <td>R$ {{ number_format($fine->fine_amount, 2, ',', '.') }}</td>
                                    <td>
                                        @if ($fine->payment_status == 'Paga')
                                            <span class="badge bg-label-success">{{ __('Paga') }}</span>
                                        @elseif ($fine->payment_status == 'Pendente')
                                            <span class="badge bg-label-warning">{{ __('Pendente') }}</span>
                                        @else
                                            <span class="badge bg-label-danger">{{ __('Cancelada') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('vehicle_fines.show', $fine->id) }}" class="btn btn-icon item-show"><i class="icon-base bx bx-show icon-sm text-primary"></i></a>
                                        <a href="{{ route('vehicle_fines.edit', $fine->id) }}" class="btn btn-icon item-edit"><i class="icon-base bx bx-edit icon-sm text-warning"></i></a>
                                        <form action="{{ route('vehicle_fines.destroy', $fine->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon item-delete"><i class="icon-base bx bx-trash icon-sm text-danger"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /Vehicle Overview -->
</div>


@endsection

@section('page-script')
<script type="module">
    $(document).ready(function() {

        $('#vehicle-usages-table, #maintenances-table, #fuel-ups-table, #vehicle-fines-table').DataTable({
            language: {
                url: '{{ asset('assets/js/datatables/pt-BR.json') }}'
            }
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
