@extends('layouts/contentNavbarLayout')

@section('title', __('Fornecedores'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Fornecedores') }}</h4>
        <p class="text-muted mb-0">{{ __('Gerenciamento de fornecedores e parceiros') }}</p>
    </div>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> {{ __('Novo Fornecedor') }}
    </a>
</div>

{{-- Stats Cards --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-building fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block mb-1 small text-muted">{{ __('Total de Fornecedores') }}</span>
                        <span class="fw-bold fs-4">{{ $suppliers->total() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-success d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-check-circle fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block mb-1 small text-muted">{{ __('Ativos') }}</span>
                        <span class="fw-bold fs-4">{{ $totalActive }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-label-danger d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-block fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block mb-1 small text-muted">{{ __('Inativos') }}</span>
                        <span class="fw-bold fs-4">{{ $totalInactive }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-store text-danger me-2"></i>{{ __('Lista de Fornecedores') }}
                </h6>
                <span class="badge bg-label-primary">{{ $suppliers->total() }} {{ __('registro(s)') }}</span>
            </div>
            <div class="card-body p-0">
                @if($suppliers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="suppliers-table">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 px-4">{{ __('Fornecedor') }}</th>
                                    <th class="border-0 py-3">{{ __('Documento') }}</th>
                                    <th class="border-0 py-3">{{ __('Tipo') }}</th>
                                    <th class="border-0 py-3">{{ __('Contato') }}</th>
                                    <th class="border-0 py-3">{{ __('Situação') }}</th>
                                    <th class="border-0 py-3 text-center">{{ __('Ações') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $supplier)
                                    <tr>
                                        <td class="py-3 px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar rounded-circle bg-label-{{ $supplier->supplier_type == 'Pessoa Jurídica' ? 'info' : 'warning' }} d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                                    <i class="bx {{ $supplier->supplier_type == 'Pessoa Jurídica' ? 'bx-building' : 'bx-user' }}"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-semibold d-block">{{ $supplier->company_name }}</span>
                                                    <small class="text-muted">{{ $supplier->contact_person ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="fw-semibold">{{ $supplier->document_number }}</span>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-label-secondary">{{ $supplier->supplier_type }}</span>
                                        </td>
                                        <td class="py-3">
                                            <span class="d-block">{{ $supplier->phone }}</span>
                                            <small class="text-muted">{{ $supplier->email }}</small>
                                        </td>
                                        <td class="py-3">
                                            @if($supplier->status == 'Ativo')
                                                <span class="badge bg-success rounded-pill px-3 py-2">{{ __('Ativo') }}</span>
                                            @elseif($supplier->status == 'Inativo')
                                                <span class="badge bg-danger rounded-pill px-3 py-2">{{ __('Inativo') }}</span>
                                            @else
                                                <span class="badge bg-warning rounded-pill px-3 py-2">{{ __('Suspenso') }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-icon btn-sm btn-outline-info" title="{{ __('Ver') }}">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-icon btn-sm btn-outline-primary" title="{{ __('Editar') }}">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline delete-form">
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
                    <div class="card-footer bg-transparent border-0 py-3">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center mb-0">
                                {{-- Previous Page Link --}}
                                @if ($suppliers->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="bx bx-chevron-left"></i></span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $suppliers->previousPageUrl() }}"><i class="bx bx-chevron-left"></i></a>
                                    </li>
                                @endif

                                {{-- Page Numbers --}}
                                @foreach ($suppliers->getUrlRange(1, $suppliers->lastPage()) as $page => $url)
                                    @if ($page == $suppliers->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link" style="background-color: #DE0802; border-color: #DE0802;">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($suppliers->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $suppliers->nextPageUrl() }}"><i class="bx bx-chevron-right"></i></a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="bx bx-chevron-right"></i></span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                {{ __('Mostrando') }} {{ $suppliers->firstItem() }} {{ __('a') }} {{ $suppliers->lastItem() }} {{ __('de') }} {{ $suppliers->total() }} {{ __('registro(s)') }}
                            </small>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bx bx-store fs-1 text-muted opacity-50"></i>
                        <p class="text-muted mt-2">{{ __('Nenhum fornecedor cadastrado') }}</p>
                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary mt-2">
                            <i class="bx bx-plus me-1"></i> {{ __('Cadastrar Primeiro Fornecedor') }}
                        </a>
                    </div>
                @endif
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
            },
            ordering: false,
            paging: false,
            info: false
        });

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: '{{ __('Tem certeza?') }}',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DE0802',
                cancelButtonColor: '#1F2A44',
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