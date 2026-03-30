@extends('layouts/contentNavbarLayout')

@section('title', __('Vendas'))

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Vendas') }}</h4>
        <p class="text-muted mb-0">{{ __('Gerenciamento de vendas e pedidos') }}</p>
    </div>
    <div class="d-flex gap-2">
        <div class="btn-group me-2" role="group" aria-label="Visualização de Vendas">
            <button type="button" class="btn btn-outline-primary btn-sm active" id="listViewBtn"><i class="bx bx-list-ul me-1"></i> {{ __('Lista') }}</button>
            <button type="button" class="btn btn-outline-primary btn-sm" id="kanbanViewBtn"><i class="bx bx-columns me-1"></i> {{ __('Kanban') }}</button>
        </div>
        <a href="{{ route('sales.export.excel') }}" class="btn btn-sm btn-outline-success"><i class="bx bx-file me-1"></i> {{ __('Excel') }}</a>
        <a href="{{ route('sales.export.pdf') }}" class="btn btn-sm btn-outline-danger"><i class="bx bx-file me-1"></i> {{ __('PDF') }}</a>
        <a href="{{ route('sales.create') }}" class="btn btn-sm btn-primary"><i class="bx bx-plus me-1"></i> {{ __('Nova Venda') }}</a>
        <!-- Upload Button -->
        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#importSaleModal">
            <i class="bx bx-import me-1"></i> {{ __('Importar Venda') }}
        </a>
    </div>
</div>

<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div id="listViewContent">
                    <div class="table-responsive text-nowrap">
                        <table class="table display" id="sales-table">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 px-4">{{ __('Pedido') }}</th>
                                    <th class="border-0 py-3">{{ __('Cliente') }}</th>
                                    <th class="border-0 py-3 text-center">{{ __('Data da Venda') }}</th>
                                    <th class="border-0 py-3 text-center">{{ __('Entrega Prevista') }}</th>
                                    <th class="border-0 py-3 text-center">{{ __('Total') }}</th>
                                    <th class="border-0 py-3 text-center">{{ __('Status') }}</th>
                                    <th class="border-0 py-3 text-center" style="width: 5%;">{{ __('Ações') }}</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                {{-- DataTables will populate this tbody --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="kanbanViewContent" style="display: none;">
                    <div class="kanban-container">
                        @foreach($orderStatuses as $status)
                            <div class="kanban-column">
                                 <div class="card mb-3 border-0 shadow-sm">
                                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
                                        <h6 class="mb-0 fw-semibold">{{ $status->label() }}</h6>
                                        <i class="bx bx-dots-vertical-rounded cursor-pointer"></i>
                                    </div>
                                    <div class="card-body" style="padding: 3px">
                                        <div class="kanban-items" id="kanban-column-{{ str_replace(' ', '-', $status->value) }}">
                                            {{-- Kanban cards will be loaded here by JavaScript --}}
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        @endforeach
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('vendor-script')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
@endsection

@section('page-script')
<style>
/* DataTables Pagination - Bassani Theme */
.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: #DE0802 !important;
    border-color: #DE0802 !important;
    color: #fff !important;
    border-radius: 4px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f8f9fa !important;
    border-color: #DE0802 !important;
    color: #DE0802 !important;
    border-radius: 4px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    color: #6c757d !important;
}
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #D2D4DA;
    border-radius: 4px;
    padding: 4px 8px;
}
</style>
<script type="module">
    $(document).ready(function() {
        const canSeePrices = {{ (Auth::check() && Auth::user()->role_id == 1) ? 'true' : 'false' }};
        $('#sales-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('sales.data') }}",
            columns: [
                { data: 'sale_number', name: 'sale_number' },
                { data: 'customer_name', name: 'customer_name' },
                { data: 'sale_date', name: 'sale_date', className: 'text-center' },
                { data: 'expected_delivery_date', name: 'expected_delivery_date', className: 'text-center' },
                { data: 'grand_total', name: 'grand_total', className: 'text-end', render: function(data, type, row) {
                    if (!canSeePrices) { return '—'; }
                    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(data);
                } },
                { data: 'status', name: 'status', render: function(data, type, row) { return data; }, className: 'text-center' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: {
                url: '{{ asset('assets/js/datatables/pt-BR.json') }}'
            },
            dom: "<'row align-items-center'<'col-sm-6'l><'col-sm-6'f>>rt<'row align-items-center'<'col-sm-6'i><'col-sm-6'p>>"
        });

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DE0802',
                cancelButtonColor: '#1F2A44',
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

<!-- Import Sale Modal -->
<div class="modal fade" id="importSaleModal" tabindex="-1" aria-labelledby="importSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importSaleModalLabel">{{ __('Importar Venda de Pedido') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sales.import') }}" method="POST" enctype="multipart/form-data" id="importSaleForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="saleFile" class="form-label">{{ __('Selecione o arquivo XLS') }}</label>
                        <input class="form-control" type="file" id="saleFile" name="sale_file" accept=".xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/html" required>
                        <div class="form-text">{{ __('O arquivo deve estar no formato XLS (HTML com charset utf-8-sig ou utf-16)') }}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Importar') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
