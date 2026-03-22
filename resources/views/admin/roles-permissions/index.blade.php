@extends('layouts/contentNavbarLayout')

@section('title', __('roles_permissions.manage_roles_permissions'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card"> 
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('roles_permissions.roles') }}</h5>
                <a href="{{ route('roles.create') }}" class="btn btn-success mb-3"><i class="icon-base bx bx-plus icon-sm text-white"></i> {{ __('roles_permissions.add_new_role') }}</a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="roles-table">
                        <thead>
                            <tr>
                                <th>{{ __('roles_permissions.name') }}</th>
                                <th>{{ __('roles_permissions.description') }}</th>
                                <th style="width: 5%;">{{ __('roles_permissions.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->description }}</td>
                                    <td>
                                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-icon item-edit"><i class="icon-base bx bx-edit icon-sm text-warning"></i></a>
                                        <a href="{{ route('roles.show', $role->id) }}" class="btn btn-icon item-show"><i class="icon-base bx bx-show icon-sm text-primary"></i></a>
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline delete-form">
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
        $('#roles-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            }
        });
        $('#permissions-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            }
        });

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: '{{ __('roles_permissions.are_you_sure') }}',
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