@extends('layouts/contentNavbarLayout')

@section('title', __('Cargas Planejadas'))

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Cargas Planejadas') }}</h4>
        <p class="text-muted mb-0">{{ __('Gerenciamento de cargas e entregas') }}</p>
    </div>
    <a href="{{ route('planned_shipments.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> {{ __('Nova Carga') }}
    </a>
</div>

{{-- Data Table Card --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table display" id="planned-shipments-table">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4">{{ __('Número') }}</th>
                        <th class="py-3">{{ __('Veículo') }}</th>
                        <th class="py-3">{{ __('Motorista') }}</th>
                        <th class="py-3">{{ __('Saída') }}</th>
                        <th class="py-3">{{ __('Entrega') }}</th>
                        <th class="py-3">{{ __('Status') }}</th>
                        <th class="py-3 text-center">{{ __('Ações') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    {{-- DataTables will populate this tbody --}}
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
        $('#planned-shipments-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('planned_shipments.data') }}",
            columns: [
                { 
                    data: 'shipment_number', 
                    name: 'shipment_number',
                    render: function(data) {
                        return `<span class="fw-semibold">${data}</span>`;
                    }
                },
                {
                    data: null,
                    name: 'vehicle.modelo',
                    render: function(data, type, row) {
                        if (row.vehicle) {
                            return `<div class="d-flex align-items-center">
                                <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    <i class="bx bx-car"></i>
                                </div>
                                <div>
                                    <span class="fw-semibold d-block">${row.vehicle.modelo}</span>
                                    <small class="text-muted">${row.vehicle.placa}</small>
                                </div>
                            </div>`;
                        }
                        return '<span class="text-muted">N/A</span>';
                    }
                },
                { 
                    data: 'driver.full_name', 
                    name: 'driver.full_name', 
                    defaultContent: '<span class="text-muted">N/A</span>' 
                },
                {
                    data: 'planned_departure_date',
                    name: 'planned_departure_date',
                    render: function(data) {
                        if (data) {
                            const date = new Date(data);
                            return `<span class="badge bg-label-info">${date.toLocaleDateString('pt-BR')}</span>`;
                        }
                        return '-';
                    }
                },
                {
                    data: 'planned_delivery_date',
                    name: 'planned_delivery_date',
                    render: function(data) {
                        if (data) {
                            const date = new Date(data);
                            return `<span class="badge bg-label-success">${date.toLocaleDateString('pt-BR')}</span>`;
                        }
                        return '-';
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    className: 'text-center',
                    render: function(data) {
                        let badgeClass = '';
                        let translatedStatus = '';
                        switch (data) {
                            case 'Planned':
                                badgeClass = 'bg-info';
                                translatedStatus = 'Planejado';
                                break;
                            case 'In Transit':
                                badgeClass = 'bg-warning';
                                translatedStatus = 'Em Trânsito';
                                break;
                            case 'Delivered':
                                badgeClass = 'bg-success';
                                translatedStatus = 'Entregue';
                                break;
                            case 'Cancelled':
                                badgeClass = 'bg-danger';
                                translatedStatus = 'Cancelado';
                                break;
                            case 'Returned':
                                badgeClass = 'bg-primary';
                                translatedStatus = 'Devolvido';
                                break;
                            default:
                                badgeClass = 'bg-secondary';
                                translatedStatus = data;
                        }
                        return `<span class="badge ${badgeClass} rounded-pill px-3 py-2">${translatedStatus}</span>`;
                    }
                },
                { 
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        const showUrl = `/admin/planned_shipments/${row.shipment_id}`;
                        const editUrl = `/admin/planned_shipments/${row.shipment_id}/edit`;
                        const destroyUrl = `/admin/planned_shipments/${row.shipment_id}`;
                        const csrfToken = $('meta[name="csrf-token"]').attr('content');
                        return `
                            <div class="d-flex justify-content-center gap-1">
                                <a href="${showUrl}" class="btn btn-icon btn-sm btn-outline-info" title="Ver">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="${editUrl}" class="btn btn-icon btn-sm btn-outline-primary" title="Editar">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="${destroyUrl}" method="POST" class="d-inline delete-form">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-icon btn-sm btn-outline-danger" title="Excluir">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        `;
                    }
                }
            ],
            language: {
                url: '{{ asset('assets/js/datatables/pt-BR.json') }}'
            },
            order: [[0, 'desc']],
            dom: '<"row align-items-center"<"col-sm-6"l><"col-sm-6"f>>rt<"row align-items-center"<"col-sm-6"i><"col-sm-6"p>>',
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