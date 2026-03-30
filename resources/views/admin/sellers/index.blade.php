@extends('layouts/contentNavbarLayout')

@section('title', __('Vendedores'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Vendedores') }}</h4>
        <p class="text-muted mb-0">{{ __('Gestão de vendedores e consultores') }}</p>
    </div>
    <a href="{{ route('crm.sellers.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> {{ __('Novo Vendedor') }}
    </a>
</div>

{{-- Data Table Card --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4">{{ __('Vendedor') }}</th>
                        <th class="py-3">{{ __('CPF') }}</th>
                        <th class="py-3">{{ __('Comissão') }}</th>
                        <th class="py-3">{{ __('Vendas (Mês)') }}</th>
                        <th class="py-3">{{ __('Status') }}</th>
                        <th class="py-3 text-center">{{ __('Ações') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($sellers as $seller)
                    <tr>
                        <td class="py-3 px-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                    @if($seller->photo)
                                        <img src="{{ Storage::url($seller->photo) }}" alt="{{ $seller->name }}" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
                                    @else
                                        <span class="fs-6">{{ strtoupper(substr($seller->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('crm.sellers.show', $seller->id) }}" class="fw-semibold text-dark d-block">{{ $seller->name }}</a>
                                    <small class="text-muted">{{ $seller->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">{{ $seller->cpf }}</td>
                        <td class="py-3">
                            <span class="badge bg-label-success">{{ number_format($seller->commission_percentage, 2) }}%</span>
                        </td>
                        <td class="py-3">
                            <span class="fw-semibold text-success">R$ {{ number_format($seller->monthly_sales, 2, ',', '.') }}</span>
                        </td>
                        <td class="py-3">
                            @if($seller->status === 'active')
                                <span class="badge bg-success rounded-pill px-3 py-2">{{ __('Ativo') }}</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-2">{{ __('Inativo') }}</span>
                            @endif
                        </td>
                        <td class="py-3 text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('crm.sellers.show', $seller->id) }}" class="btn btn-icon btn-sm btn-outline-info" title="{{ __('Ver') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('crm.sellers.edit', $seller->id) }}" class="btn btn-icon btn-sm btn-outline-primary" title="{{ __('Editar') }}">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('crm.sellers.destroy', $seller->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-sm btn-outline-danger" title="{{ __('Desativar') }}">
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const f = this;
                Swal.fire({
                    title: '{{ __('Tem certeza?') }}',
                    text: "{{ __('Deseja realmente desativar este vendedor?') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DE0802',
                    cancelButtonColor: '#1F2A44',
                    confirmButtonText: '{{ __('Sim, desativar!') }}',
                    cancelButtonText: '{{ __('Cancelar') }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        f.submit();
                    }
                });
            });
        });
    });
</script>
@endsection