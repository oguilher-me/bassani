@extends('layouts/contentNavbarLayout')

@section('title', __('Janelas de Entrega'))

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card"> 
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Janelas de Entrega') }}</h5>
                <a href="{{ route('delivery_windows.create') }}" class="btn btn-success mb-3"><i class="icon-base bx bx-plus icon-sm text-white"></i> {{ __('Adicionar Nova Janela') }}</a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table display" id="delivery-windows-table">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Dia da Semana') }}</th>
                                <th>{{ __('Início') }}</th>
                                <th>{{ __('Fim') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center" style="width: 5%;">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            {{-- DataTables will populate this tbody --}}
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
        $('#delivery-windows-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('delivery_windows.data') }}", // We will create this route
            columns: [
                { data: 'id', name: 'id' },
                { data: 'day_of_week', name: 'day_of_week' },
                { data: 'start_time', name: 'start_time' },
                { data: 'end_time', name: 'end_time' },
                { data: 'status', name: 'status', className: 'text-center' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            language: {
                url: '{{ asset('assets/js/datatables/pt-BR.json') }}'
            }
        });

        // Implement delete confirmation if needed
        // $('.delete-form').on('submit', function(e) {
        //     e.preventDefault();
        //     const form = this;
        //     Swal.fire({
        //         title: 'Tem certeza?',
        //         text: "Você não poderá reverter isso!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Sim, excluir!',
        //         cancelButtonText: 'Cancelar'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             form.submit();
        //         }
        //     });
        // });
    });
</script>
@endsection