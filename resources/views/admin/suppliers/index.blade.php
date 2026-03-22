@extends('layouts/contentNavbarLayout')

@section('title', __('Fornecedores'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card"> 
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Fornecedores') }}</h5>
                <a href="{{ route('suppliers.create') }}" class="btn btn-success mb-3"><i class="icon-base bx bx-plus icon-sm text-white"></i> {{ __('Adicionar Novo Fornecedor') }}</a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="suppliers-table">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Razão Social / Nome Fantasia') }}</th>
                                <th>{{ __('CNPJ / CPF') }}</th>
                                <th>{{ __('Tipo') }}</th>
                                <th>{{ __('Contato') }}</th>
                                <th>{{ __('Situação') }}</th>
                                <th class="text-center" style="width: 5%;">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($suppliers as $supplier)
                                <tr>
                                    <td>{{ $supplier->id }}</td>
                                    <td>{{ $supplier->company_name }}</td>
                                    <td>{{ $supplier->document_number }}</td>
                                    <td>{{ $supplier->supplier_type }}</td>
                                    <td>{{ $supplier->contact_person }} <br>
                                        <small class="emp_post text-truncate">{{ $supplier->phone }}</small>
                                    </td>
                                    <td>
                                        @if($supplier->status == 'Ativo')
                                            <span class="badge bg-success">{{ $supplier->status }}</span>
                                        @elseif($supplier->status == 'Inativo')
                                            <span class="badge bg-danger">{{ $supplier->status }}</span>
                                        @else
                                            <span class="badge bg-warning">{{ $supplier->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-icon item-show"><i class="icon-base bx bx-show icon-sm text-primary"></i></a>
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-icon item-edit"><i class="icon-base bx bx-edit icon-sm text-warning"></i></a>
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon item-delete"><i class="icon-base bx bx-trash icon-sm text-danger"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
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
        $('#suppliers-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            }
        });

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: '{{ __('Tem certeza?') }}',
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