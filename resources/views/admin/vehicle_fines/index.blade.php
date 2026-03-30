@extends('layouts/contentNavbarLayout')

@section('title', __('Multas de Veículos'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Multas de Veículos') }}</h4>
        <p class="text-muted mb-0">{{ __('Gerenciamento de multas e infrações da frota') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-car me-1"></i> {{ __('Veículos') }}
        </a>
        <a href="{{ route('vehicle_fines.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> {{ __('Nova Multa') }}
        </a>
    </div>
</div>

{{-- Stats Cards --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-danger d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-receipt fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block mb-1 small text-muted">{{ __('Total de Multas') }}</span>
                        <span class="fw-bold fs-4">{{ $totalFines ?? 0 }}</span>
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
                        <i class="bx bx-time-five fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block mb-1 small text-muted">{{ __('Pendentes') }}</span>
                        <span class="fw-bold fs-4">{{ $pendingFines ?? 0 }}</span>
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
                        <span class="d-block mb-1 small text-muted">{{ __('Pagas') }}</span>
                        <span class="fw-bold fs-4">{{ $paidFines ?? 0 }}</span>
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
                        <i class="bx bx-money fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block mb-1 small text-muted">{{ __('Valor Total') }}</span>
                        <span class="fw-bold fs-4">R$ {{ number_format($totalAmount ?? 0, 2, ',', '.') }}</span>
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
                    <i class="bx bx-receipt text-danger me-2"></i>{{ __('Registro de Multas') }}
                </h6>
                <span class="badge bg-label-primary">{{ __('Server-Side') }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="vehicle-fines-table">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 px-4">{{ __('Data Infração') }}</th>
                                <th class="border-0 py-3">{{ __('Veículo') }}</th>
                                <th class="border-0 py-3">{{ __('Motorista') }}</th>
                                <th class="border-0 py-3">{{ __('Valor') }}</th>
                                <th class="border-0 py-3">{{ __('Status') }}</th>
                                <th class="border-0 py-3 text-center">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- DataTables will populate --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="module">
    $(document).ready(function() {
        $.fn.dataTable.ext.errMode = 'throw';
        $('#vehicle-fines-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vehicle_fines.data') }}",
            columns: [
                { data: 'infraction_date', name: 'infraction_date' },
                { data: 'vehicle_info', name: 'vehicle_info' },
                { data: 'driver_name', name: 'driver.full_name' },
                { data: 'fine_amount', name: 'fine_amount' },
                { data: 'payment_status', name: 'payment_status', className: 'text-center' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            language: {
                url: '{{ asset('assets/js/datatables/pt-BR.json') }}'
            },
            order: [[0, 'desc']]
        });

        $(document).on('submit', '.delete-form', function(e) {
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