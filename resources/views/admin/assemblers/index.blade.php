@extends('layouts/contentNavbarLayout')

@section('title', __('Montadores'))

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Montadores') }}</h4>
        <p class="text-muted mb-0">{{ __('Gerenciamento de montadores') }}</p>
    </div>
    <a href="{{ route('assemblers.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> {{ __('Novo Montador') }}
    </a>
</div>

{{-- Data Table Card --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table display" id="assemblers-table">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4">{{ __('ID') }}</th>
                        <th class="py-3">{{ __('Nome') }}</th>
                        <th class="py-3">{{ __('CPF') }}</th>
                        <th class="py-3">{{ __('Telefone') }}</th>
                        <th class="py-3">{{ __('Email') }}</th>
                        <th class="py-3">{{ __('Tipo') }}</th>
                        <th class="py-3">{{ __('Ativo') }}</th>
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
        var table = $('#assemblers-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('assemblers.data') }}',
                data: function (d) {
                    d.name = $('#filter-name').val();
                    d.type = $('#filter-type').val();
                    d.active = $('#filter-active').val();
                }
            },
            columns: [
                { 
                    data: 'id', 
                    name: 'id',
                    render: function(data) {
                        return `<span class="badge bg-light text-dark">#${data}</span>`;
                    }
                },
                { 
                    data: 'name', 
                    name: 'name',
                    render: function(data, type, row) {
                        return `<div class="d-flex align-items-center">
                            <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                <i class="bx bx-user"></i>
                            </div>
                            <span class="fw-semibold">${data}</span>
                        </div>`;
                    }
                },
                { data: 'cpf', name: 'cpf' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { 
                    data: 'type', 
                    name: 'type', 
                    className: 'text-center',
                    render: function(data) {
                        if (data === 'CONTRACTED') {
                            return '<span class="badge bg-success rounded-pill px-3 py-2">{{ __('Contratado') }}</span>';
                        } else if (data === 'OUTSOURCED') {
                            return '<span class="badge bg-info rounded-pill px-3 py-2">{{ __('Terceirizado') }}</span>';
                        }
                        return data;
                    }
                },
                { 
                    data: 'status', 
                    name: 'status',
                    className: 'text-center',
                    render: function(data) {
                        if (data) {
                            return '<span class="badge bg-success rounded-pill px-3 py-2">{{ __('Sim') }}</span>';
                        }
                        return '<span class="badge bg-danger rounded-pill px-3 py-2">{{ __('Não') }}</span>';
                    }
                },
                { 
                    data: 'actions', 
                    name: 'actions', 
                    orderable: false, 
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex justify-content-center gap-1">
                                <a href="/admin/assemblers/${row.id}" class="btn btn-icon btn-sm btn-outline-info" title="Ver">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="/admin/assemblers/${row.id}/edit" class="btn btn-icon btn-sm btn-outline-primary" title="Editar">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="/admin/assemblers/${row.id}" method="POST" class="d-inline delete-form">
                                    <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-icon btn-sm btn-outline-danger" title="Excluir">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        `;
                    }
                },
            ],
            language: {
                url: '{{ asset('assets/js/datatables/pt-BR.json') }}'
            },
            dom: '<"row align-items-center"<"col-sm-6"l><"col-sm-6"f>>rt<"row align-items-center"<"col-sm-6"i><"col-sm-6"p>>',
            pageLength: 10
        });

        $('#filter-name, #filter-type, #filter-active').on('change keyup', function () {
            table.draw();
        });

        $(document).on('submit', '.delete-form', function(e) {
            e.preventDefault();
            const form = $(this);
            const actionUrl = form.attr('action');

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
                    $.ajax({
                        url: actionUrl,
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __('Excluído!') }}',
                                text: response.success,
                                confirmButtonColor: '#DE0802'
                            });
                            table.draw();
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __('Erro!') }}',
                                text: response.responseJSON.error || '{{ __('Ocorreu um erro ao desativar o montador.') }}',
                                confirmButtonColor: '#DE0802'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endsection