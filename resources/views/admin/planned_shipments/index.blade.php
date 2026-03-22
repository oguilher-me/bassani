@extends('layouts/contentNavbarLayout')

@section('title', __('Cargas Planejadas'))

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card"> 
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Cargas Planejadas') }}</h5>
                <a href="{{ route('planned_shipments.create') }}" class="btn btn-success mb-3"><i class="icon-base bx bx-plus icon-sm text-white"></i> {{ __('Adicionar Nova Carga') }}</a>
            </div>
            <div class="card-body">
               

                <div class="table-responsive text-nowrap">
                    <table class="table display" id="planned-shipments-table">
                        <thead>
                            <tr>
                                <th>{{ __('Número da Carga') }}</th>
                                <th>{{ __('Veículo') }}</th>
                                <th>{{ __('Nome do Motorista') }}</th>
                                <th>{{ __('Saída Planejada') }}</th>
                                <th>{{ __('Entrega Planejada') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center" style="width: 5%;">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            {{-- DataTables will populate this tbody --}}
                        </tbody>
                    </table>
                </div>
                {{-- Removed existing pagination --}}
            </div>
        </div>
    </div>

</div>
@endsection

@section('page-script')
<script type="module">
    $(document).ready(function() {
        $.fn.dataTable.ext.errMode = 'throw';
        $('#planned-shipments-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('planned_shipments.data') }}", // We will create this route
            columns: [
                { data: 'shipment_number', name: 'shipment_number' },
                {
                    data: null,
                    name: 'vehicle.modelo',
                    render: function(data, type, row) {
                        if (row.vehicle) {
                            return row.vehicle.modelo + ' (' + row.vehicle.placa + ')';
                        }
                        return 'N/A';
                    }
                },
                { data: 'driver.full_name', name: 'driver.full_name', defaultContent: 'N/A' },
                {
                    data: 'planned_departure_date',
                    name: 'planned_departure_date',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            if (data) {
                                const date = new Date(data);
                                return date.toLocaleDateString('pt-BR');
                            }
                        }
                        return data;
                    }
                },
                {
                    data: 'planned_delivery_date',
                    name: 'planned_delivery_date',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            if (data) {
                                const date = new Date(data);
                                return date.toLocaleDateString('pt-BR');
                            }
                        }
                        return data;
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    className: 'text-center',
                    render: function(data, type, row) {
                        let badgeClass = '';
                        let translatedStatus = '';
                        switch (data) {
                            case 'Planned':
                                badgeClass = 'bg-label-info';
                                translatedStatus = 'Planejado';
                                break;
                            case 'In Transit':
                                badgeClass = 'bg-label-warning';
                                translatedStatus = 'Em Trânsito';
                                break;
                            case 'Delivered':
                                badgeClass = 'bg-label-success';
                                translatedStatus = 'Entregue';
                                break;
                            case 'Cancelled':
                                badgeClass = 'bg-label-danger';
                                translatedStatus = 'Cancelado';
                                break;
                            case 'Returned':
                                badgeClass = 'bg-label-primary';
                                translatedStatus = 'Devolvido';
                                break;
                            default:
                                badgeClass = 'bg-label-secondary';
                                translatedStatus = data; // Fallback to original data if no translation
                                break;
                        }
                        return `<span class="badge ${badgeClass}">${translatedStatus}</span>`;
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                url: '{{ asset('assets/js/datatables/pt-BR.json') }}'
            }
        });

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, inativar!',
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