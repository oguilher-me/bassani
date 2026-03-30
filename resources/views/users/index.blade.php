@extends('layouts/contentNavbarLayout')

@section('title', __('Gerenciar Usuários'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Usuários') }}</h4>
        <p class="text-muted mb-0">{{ __('Gerenciamento de usuários do sistema') }}</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> {{ __('Novo Usuário') }}
    </a>
</div>

{{-- Data Table Card --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="users-table">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4">{{ __('Usuário') }}</th>
                        <th class="py-3">{{ __('Email') }}</th>
                        <th class="py-3">{{ __('Perfil') }}</th>
                        <th class="py-3">{{ __('Status') }}</th>
                        <th class="py-3 text-center">{{ __('Ações') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($users as $user)
                        <tr>
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="py-3">{{ $user->email }}</td>
                            <td class="py-3">
                                <span class="badge bg-label-info rounded-pill px-3 py-2">{{ $user->role->name ?? 'N/A' }}</span>
                            </td>
                            <td class="py-3">
                                @if ($user->status)
                                    <span class="badge bg-success rounded-pill px-3 py-2">{{ __('Ativo') }}</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3 py-2">{{ __('Inativo') }}</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-icon btn-sm btn-outline-info" title="{{ __('Ver') }}">
                                        <i class="bx bx-show"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-icon btn-sm btn-outline-primary" title="{{ __('Editar') }}">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-sm btn-outline-danger" title="{{ __('Excluir') }}">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="module">
    $(document).ready(function() {
        $('#users-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            },
            ordering: false,
            info: false
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