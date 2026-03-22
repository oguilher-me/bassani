@extends('layouts/contentNavbarLayout')

@section('title', __('Dashboard de Frota'))

@section('content')
<div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Bem-vindo ao Dashboard de Frota! 🎉') }}</h5>
                        <p class="mb-4">{{ __('Aqui você pode acompanhar os principais indicadores da sua frota.') }}</p>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Cards de Resumo --}}
<div class="row">
    <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img
                            src="{{ asset('assets/img/icons/unicons/car-solid.png') }}"
                            alt="chart success"
                            class="rounded"
                        />
                    </div>
                </div>
                <span class="d-block mb-1">Total de Veículos</span>
                <h3 class="card-title text-nowrap mb-2">{{ $totalVehicles }}</h3>
                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.14%</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img
                            src="{{ asset('assets/img/icons/unicons/wrench-solid.png') }}"
                            alt="chart success"
                            class="rounded"
                        />
                    </div>
                </div>
                <span class="d-block mb-1">Total de Manutenções</span>
                <h3 class="card-title text-nowrap mb-2">{{ $totalMaintenances }}</h3>
                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.14%</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img
                            src="{{ asset('assets/img/icons/unicons/gas-pump-solid.png') }}"
                            alt="chart success"
                            class="rounded"
                        />
                    </div>
                </div>
                <span class="d-block mb-1">Total de Abastecimentos</span>
                <h3 class="card-title text-nowrap mb-2">{{ $totalFuelUps }}</h3>
                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.14%</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img
                            src="{{ asset('assets/img/icons/unicons/users-solid.png') }}"
                            alt="chart success"
                            class="rounded"
                        />
                    </div>
                </div>
                <span class="d-block mb-1">Total de Motoristas</span>
                <h3 class="card-title text-nowrap mb-2">{{ $totalDrivers }}</h3>
                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.14%</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img
                            src="{{ asset('assets/img/icons/unicons/money-bill-wave-solid.png') }}"
                            alt="chart success"
                            class="rounded"
                        />
                    </div>
                </div>
                <span class="d-block mb-1">Total de Vendas</span>
                <h3 class="card-title text-nowrap mb-2">R$ {{ number_format($totalSales, 2, ',', '.') }}</h3>
                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.14%</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img
                            src="{{ asset('assets/img/icons/unicons/road-solid.png') }}"
                            alt="chart success"
                            class="rounded"
                        />
                    </div>
                </div>
                <span class="d-block mb-1">Total de Usos de Veículos</span>
                <h3 class="card-title text-nowrap mb-2">{{ $totalVehicleUsages }}</h3>
                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.14%</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img
                            src="{{ asset('assets/img/icons/unicons/receipt-solid.png') }}"
                            alt="chart success"
                            class="rounded"
                        />
                    </div>
                </div>
                <span class="d-block mb-1">Total de Multas</span>
                <h3 class="card-title text-nowrap mb-2">{{ $totalFines }}</h3>
                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.14%</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img
                            src="{{ asset('assets/img/icons/unicons/exclamation-triangle-solid.png') }}"
                            alt="chart success"
                            class="rounded"
                        />
                    </div>
                </div>
                <span class="d-block mb-1">Multas Pendentes</span>
                <h3 class="card-title text-nowrap mb-2">{{ $totalPendingFines }}</h3>
                <small class="text-danger fw-semibold"><i class="bx bx-down-arrow-alt"></i> -14.82%</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img
                            src="{{ asset('assets/img/icons/unicons/money-check-alt-solid.png') }}"
                            alt="chart success"
                            class="rounded"
                        />
                    </div>
                </div>
                <span class="d-block mb-1">Valor Total Multas Pendentes</span>
                <h3 class="card-title text-nowrap mb-2">R$ {{ number_format($totalPendingFinesAmount, 2, ',', '.') }}</h3>
                <small class="text-danger fw-semibold"><i class="bx bx-down-arrow-alt"></i> -14.82%</small>
            </div>
        </div>
    </div>
</div>

{{-- Gráficos e Visualizações --}}
<div class="row">
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 me-2">{{ __('Distribuição da Frota por Status') }}</h5>
            </div>
            <div class="card-body">
                <canvas id="fleetStatusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 me-2">{{ __('Custo Mensal de Manutenção') }}</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyMaintenanceCostChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 me-2">{{ __('Distribuição de Tipos de Multas') }}</h5>
            </div>
            <div class="card-body">
                <canvas id="fineTypeDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 me-2">{{ __('Vendas Mensais') }}</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 me-2">{{ __('Consumo Médio de Combustível') }}</h5>
            </div>
            <div class="card-body">
                <canvas id="averageFuelConsumptionChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 me-2">{{ __('Top 5 Veículos com Maior Custo de Manutenção') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('Placa') }}</th>
                                <th>{{ __('Modelo') }}</th>
                                <th>{{ __('Custo Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($top5MaintenanceCostVehicles as $vehicle)
                                <tr>
                                    <td>{{ $vehicle->placa }}</td>
                                    <td>{{ $vehicle->modelo }}</td>
                                    <td>R$ {{ number_format($vehicle->maintenances_sum_cost ?? 0, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">{{ __('Nenhum veículo encontrado.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 me-2">{{ __('Últimas Multas') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('Veículo') }}</th>
                                <th>{{ __('Motorista') }}</th>
                                <th>{{ __('Data da Infração') }}</th>
                                <th>{{ __('Valor') }}</th>
                                <th>{{ __('Status do Pagamento') }}</th>
                                <th>{{ __('Responsável') }}</th>
                                <th>{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestFines as $fine)
                                <tr>
                                    <td>{{ $fine->vehicle->modelo ?? 'N/A' }} ({{ $fine->vehicle->placa ?? 'N/A' }})</td>
                                    <td>{{ $fine->driver->full_name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($fine->infraction_date)->format('d/m/Y') }}</td>
                                    <td>R$ {{ number_format($fine->fine_amount, 2, ',', '.') }}</td>
                                    <td>{{ $fine->payment_status->getLabel() }}</td>
                                    <td>{{ $fine->responsible_for_payment->getLabel() }}</td>
                                    <td>
                                        <a href="{{ route('admin.vehicle_fines.show', $fine->id) }}" class="btn btn-sm btn-icon btn-outline-info" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Visualizar') }}">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('admin.vehicle_fines.edit', $fine->id) }}" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Editar') }}">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.vehicle_fines.destroy', $fine->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Excluir') }}" onclick="return confirm('{{ __('Tem certeza que deseja excluir esta multa?') }}')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">{{ __('Nenhuma multa encontrada.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Alertas e Notificações --}}
<div class="col-lg-6 mb-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Alertas e Notificações</h6>
        </div>
        <div class="card-body">
            @if($upcomingLicensing->isNotEmpty() || $upcomingInsurance->isNotEmpty() || $upcomingPreventiveMaintenance->isNotEmpty())
                @if($upcomingLicensing->isNotEmpty())
                    <h5 class="h5 mb-2 text-gray-800">Licenciamento Próximo do Vencimento:</h5>
                    <ul class="list-group mb-3">
                        @foreach($upcomingLicensing as $vehicle)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $vehicle->model }} ({{ $vehicle->plate }})
                                <span class="badge badge-warning badge-pill">Vence em: {{ \Carbon\Carbon::parse($vehicle->licensing_due_date)->format('d/m/Y') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if($upcomingInsurance->isNotEmpty())
                    <h5 class="h5 mb-2 text-gray-800">Seguro Próximo do Vencimento:</h5>
                    <ul class="list-group mb-3">
                        @foreach($upcomingInsurance as $vehicle)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $vehicle->model }} ({{ $vehicle->plate }})
                                <span class="badge badge-warning badge-pill">Vence em: {{ \Carbon\Carbon::parse($vehicle->insurance_due_date)->format('d/m/Y') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if($upcomingPreventiveMaintenance->isNotEmpty())
                    <h5 class="h5 mb-2 text-gray-800">Manutenção Preventiva Próxima/Vencida:</h5>
                    <ul class="list-group mb-3">
                        @foreach($upcomingPreventiveMaintenance as $vehicle)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $vehicle->model }} ({{ $vehicle->plate }})
                                <span class="badge badge-danger badge-pill">
                                    @if($vehicle->quilometragem_atual >= $vehicle->next_preventive_maintenance_mileage)
                                        Vencida ({{ number_format($vehicle->quilometragem_atual, 0, ',', '.') }} KM / {{ number_format($vehicle->next_preventive_maintenance_mileage, 0, ',', '.') }} KM)
                                    @else
                                        Próxima ({{ number_format($vehicle->quilometragem_atual, 0, ',', '.') }} KM / {{ number_format($vehicle->next_preventive_maintenance_mileage, 0, ',', '.') }} KM)
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            @else
                <p class="text-success">Nenhum alerta de licenciamento, seguro ou manutenção preventiva próximo do vencimento.</p>
            @endif
            {{-- TODO: Adicionar alertas para manutenção preventiva próxima e anomalias --}}
        </div>
    </div>
</div>

{{-- Tabela Dinâmica de Veículos --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">{{ __('Tabela Dinâmica de Veículos') }}</h5>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('Placa') }}</th>
                                <th>{{ __('Modelo') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Última Manutenção') }}</th>
                                <th>{{ __('Próxima Manutenção') }}</th>
                                <th>{{ __('Quilometragem') }}</th>
                                <th>{{ __('Condutor Responsável') }}</th>
                                <th>{{ __('Total Manutenções') }}</th>
                                <th>{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($vehicles as $vehicle)
                                <tr>
                                    <td>{{ $vehicle['plate'] }}</td>
                                    <td>{{ $vehicle['model'] }}</td>
                                    <td><span class="badge bg-label-primary me-1">{{ $vehicle['status'] }}</span></td>
                                    <td>{{ $vehicle['last_maintenance'] }}</td>
                                    <td>{{ $vehicle['next_maintenance'] }}</td>
                                    <td>{{ $vehicle['mileage'] }} KM</td>
                                    <td>{{ $vehicle['responsible_driver'] }}</td>
                                    <td>{{ $vehicle['total_maintenances'] }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('vehicles.show', $vehicle['id']) }}"><i class="bx bx-show me-1"></i> {{ __('Visualizar Veículo') }}</a>
                                                <a class="dropdown-item" href="{{ route('maintenances.index', ['vehicle_id' => $vehicle['id']]) }}"><i class="bx bx-wrench me-1"></i> {{ __('Histórico de Manutenções') }}</a>
                                                <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> {{ __('Editar Veículo') }}</a>
                                                <a class="dropdown-item" href="{{ route('maintenances.create', ['vehicle_id' => $vehicle['id']]) }}"><i class="bx bx-plus me-1"></i> {{ __('Registrar Manutenção') }}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">{{ __('Nenhum veículo encontrado.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fleetStatusCtx = document.getElementById('fleetStatusChart').getContext('2d');
        const fleetStatusChart = new Chart(fleetStatusCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode(array_keys($vehicleStatusDistribution)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($vehicleStatusDistribution)) !!},
                    backgroundColor: [
                        '#66BB6A', // Ativo (verde)
                        '#FFA726', // Em manutenção (laranja)
                        '#EF5350', // Inativo (vermelho)
                        '#42A5F5', // Outros (azul)
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false,
                        text: 'Distribuição da Frota por Status'
                    }
                }
            },
        });
    });

    const monthlyMaintenanceCostCtx = document.getElementById('monthlyMaintenanceCostChart').getContext('2d');
    const monthlyMaintenanceCostChart = new Chart(monthlyMaintenanceCostCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($monthlyMaintenanceCosts)) !!},
            datasets: [{
                label: 'Custo de Manutenção (R$)',
                data: {!! json_encode(array_values($monthlyMaintenanceCosts)) !!},
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false,
                    text: 'Custo Mensal de Manutenção'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const averageFuelConsumptionCtx = document.getElementById('averageFuelConsumptionChart').getContext('2d');
    const averageFuelConsumptionChart = new Chart(averageFuelConsumptionCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($averageFuelConsumption)) !!},
            datasets: [{
                label: 'Litros Médios',
                data: {!! json_encode(array_values($averageFuelConsumption)) !!},
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false,
                    text: 'Consumo Médio de Combustível ao Longo do Tempo'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const salesStatusDistributionCtx = document.getElementById('salesStatusDistributionChart').getContext('2d');
    const salesStatusDistributionChart = new Chart(salesStatusDistributionCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_keys($salesStatusDistribution)) !!},
            datasets: [{
                data: {!! json_encode(array_values($salesStatusDistribution)) !!},
                backgroundColor: [
                    '#4CAF50', // Concluída (verde)
                    '#FFC107', // Pendente (amarelo)
                    '#F44336', // Cancelada (vermelho)
                    '#2196F3', // Em Processamento (azul)
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false,
                    text: 'Distribuição de Vendas por Status'
                }
            }
        },
    });

    const monthlySalesChartCtx = document.getElementById('monthlySalesChart').getContext('2d');
    const monthlySalesChart = new Chart(monthlySalesChartCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($monthlySales)) !!},
            datasets: [{
                label: 'Vendas Mensais (R$)',
                data: {!! json_encode(array_values($monthlySales)) !!},
                backgroundColor: 'rgba(153, 102, 255, 0.6)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false,
                    text: 'Vendas Mensais'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const fineTypeDistributionCtx = document.getElementById('fineTypeDistributionChart').getContext('2d');
    const fineTypeDistributionChart = new Chart(fineTypeDistributionCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_keys($fineTypeDistribution)) !!},
            datasets: [{
                data: {!! json_encode(array_values($fineTypeDistribution)) !!},
                backgroundColor: [
                    '#FF6384', // Vermelho
                    '#36A2EB', // Azul
                    '#FFCE56', // Amarelo
                    '#4BC0C0', // Verde Água
                    '#9966FF', // Roxo
                    '#FF9F40', // Laranja
                    '#E7E9ED', // Cinza
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false,
                    text: 'Distribuição de Tipos de Multas'
                }
            }
        },
    });
});
</script>
@endsection