@extends('layouts/contentNavbarLayout')

@section('title', __('roles_permissions.manage_permissions') . ' - ' . __('roles_permissions.roles') . ' & ' . __('roles_permissions.permissions'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('roles_permissions.manage_permissions') }}</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('permissions.create') }}" class="btn btn-primary mb-3">{{ __('roles_permissions.add_new_permission') }}</a>
                <div class="table-responsive text-nowrap">
                    <table class="table" id="permissions-table">
                        <thead>
                            <tr>
                                <th>{{ __('roles_permissions.name') }}</th>
                                <th>{{ __('roles_permissions.description') }}</th>
                                <th style="width:5%">{{ __('roles_permissions.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->description }}</td>
                                    <td>
                                        <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-sm btn-warning">{{ __('roles_permissions.edit') }}</a>
                                        <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">{{ __('roles_permissions.delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary mt-3">{{ __('roles_permissions.back_to_roles_permissions') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="module">
    $(document).ready(function() {
        $('#permissions-table').DataTable();

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            var form = this;
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