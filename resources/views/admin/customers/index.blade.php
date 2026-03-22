@extends('layouts/contentNavbarLayout')

@section('title', __('Clientes'))

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card"> 
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Clientes') }}</h5>
                <a href="{{ route('customers.create') }}" class="btn btn-success mb-3"><i class="icon-base bx bx-plus icon-sm text-white"></i> {{ __('Adicionar Novo Cliente') }}</a>
            </div>
            <div class="card-body">
               

                <div class="table-responsive text-nowrap">
                    <table class="table display" id="customers-table">
                        <thead>
                            <tr>
                                <th>{{ __('Nome/Razão Social') }}</th>
                                <th>{{ __('CPF/CNPJ') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Telefone') }}</th>
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
        $('#customers-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('customers.data') }}", // We will create this route
            columns: [
                { data: 'fullName', name: 'fullName' },
                { data: 'document', name: 'document' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'status', name: 'status', className: 'text-center' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
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