@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            <!-- Vendas Totais -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center gap-2">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-dollar fs-4"></i></span>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <small class="text-muted">Vendas</small>
                                    <h5 class="mb-0">R${{ number_format($totalSalesAmount, 2, ',', '.') }}</h5>
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                <span class="badge bg-label-success rounded-pill">+28.42%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Entregas Atrasadas -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center gap-2">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-time-five fs-4"></i></span>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <small class="text-muted">Entregas Atrasadas</small>
                                    <h5 class="mb-0">{{ $overdueMaintenances->count() }}</h5>
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                <span class="badge bg-label-warning rounded-pill">+X%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Gastos com Veículos -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center gap-2">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-car fs-4"></i></span>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <small class="text-muted">Gastos Veículos</small>
                                    <h5 class="mb-0">R${{ number_format($totalVehicleExpenses, 2, ',', '.') }}</h5>
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                <span class="badge bg-label-danger rounded-pill">-Y%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Lucro -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center gap-2">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-success"><i class="bx bx-wallet fs-4"></i></span>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <small class="text-muted">Lucro</small>
                                    <h5 class="mb-0">R${{ number_format($totalProfit, 2, ',', '.') }}</h5>
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                <span class="badge bg-label-success rounded-pill">+Z%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Receita Mensal -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Receita Mensal</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="monthlySalesDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="monthlySalesDropdown">
                                <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de Receita Mensal
        new Chart(document.getElementById('monthlySalesChart'), {
            type: 'line',
            data: {
                labels: {{ json_encode(array_keys($monthlySales)) }},
                datasets: [{
                    label: 'Receita (R$)',
                    data: {{ json_encode(array_values($monthlySales)) }},
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            }
        });
    </script>
@endsection
