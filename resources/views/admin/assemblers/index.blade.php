@extends('layouts/contentNavbarLayout')

@section('title', __('Montadores'))

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Montadores') }}</h5>
                <a href="{{ route('assemblers.create') }}" class="btn btn-success mb-3"><i class="icon-base bx bx-plus icon-sm text-white"></i> {{ __('Adicionar Novo Montador') }}</a>
            </div>
            <div class="card-body">
                
                <div class="table-responsive text-nowrap">
                    <table class="table display" id="assemblers-table">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Nome') }}</th>
                                <th>{{ __('CPF') }}</th>
                                <th>{{ __('Telefone') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Tipo') }}</th>
                                <th>{{ __('Ativo') }}</th>
                                <th style="width: 5%;">{{ __('Ações') }}</th>
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
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'cpf', name: 'cpf' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'type', name: 'type', render: function(data, type, row) {
                    if (data === 'CONTRACTED') {
                        return '<span class="badge bg-label-success">{{ __('Contratado') }}</span>';
                    } else if (data === 'OUTSOURCED') {
                        return '<span class="badge bg-label-info">{{ __('Terceirizado') }}</span>';
                    }
                    return data;
                }},
                { data: 'status', name: 'status', render: function(data, type, row) {
                    if (data) {
                        return '<span class="badge bg-label-success">{{ __('Sim') }}</span>';
                    }
                    return '<span class="badge bg-label-danger">{{ __('Não') }}</span>';
                }},
                { data: 'actions', name: 'actions', orderable: false, searchable: false, render: function(data, type, row) {
                    var actions = '';
                    actions += '<a href="/admin/assemblers/' + row.id + '" class="btn btn-sm btn-icon item-edit"><i class="bx bx-show"></i></a>';
                    actions += '<a href="/admin/assemblers/' + row.id + '/edit" class="btn btn-sm btn-icon item-edit"><i class="bx bx-edit"></i></a>';
                    actions += '<form action="/admin/assemblers/' + row.id + '" method="POST" style="display:inline;" class="delete-form">';
                    actions += '@csrf';
                    actions += '@method("DELETE")';
                    actions += '<button type="submit" class="btn btn-sm btn-icon item-delete"><i class="bx bx-trash"></i></button>';
                    actions += '</form>';
                    return actions;
                }},
            ],
            language: {
                url: '{{ asset('assets/js/datatables/pt-BR.json') }}'
            }
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
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __('Sim, excluir!') }}',
                cancelButtonText: '{{ __('Cancelar') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: actionUrl,
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            Swal.fire(
                                '{{ __('Excluído!') }}',
                                response.success,
                                'success'
                            );
                            table.draw();
                        },
                        error: function(response) {
                            Swal.fire(
                                '{{ __('Erro!') }}',
                                response.responseJSON.error || '{{ __('Ocorreu um erro ao desativar o montador.') }}',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>
@endsection