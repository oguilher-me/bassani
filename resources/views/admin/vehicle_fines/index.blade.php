@extends('layouts/contentNavbarLayout')

@section('title', __('Multas de Veículos'))

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card"> 
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Multas de Veículos') }}</h5>
                <a href="{{ route('vehicle_fines.create') }}" class="btn btn-success mb-3"><i class="icon-base bx bx-plus icon-sm text-white"></i> {{ __('Adicionar Nova Multa') }}</a>
            </div>
            <div class="card-body">
               

                <div class="table-responsive text-nowrap">
                    <table class="table display" id="vehicle-fines-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">{{ __('Data da Infração') }}</th>
                                <th>{{ __('Veículo') }}</th>
                                <th>{{ __('Motorista') }}</th>
                                <th>{{ __('Valor') }}</th>
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
        $('#vehicle-fines-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vehicle_fines.data') }}", // We will create this route
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
            }
        });

        $(document).on('submit', '.delete-form', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Tem certeza?',
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