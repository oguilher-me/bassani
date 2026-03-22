@extends('layouts/contentNavbarLayout')

@section('title', __('Vendas'))

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Vendas') }}</h5>
                <div class="d-flex align-items-center">
                    <div>
                        <div style="margin-top: -10px;" class="btn-group me-2" role="group" aria-label="Visualização de Vendas">
                            <button type="button" class="btn btn-outline-primary active" id="listViewBtn"><i class="bx bx-list-ul"></i> Lista</button>
                            <button type="button" class="btn btn-outline-primary" id="kanbanViewBtn"><i class="bx bx-columns"></i> Kanban</button>
                        </div>
                        <a href="{{ route('sales.create') }}" class="btn btn-success mb-3"><i class="icon-base bx bx-plus icon-sm text-white"></i> {{ __('Adicionar Nova Venda') }}</a>
                        <a href="{{ route('sales.export.excel') }}" class="btn btn-primary mb-3 ms-2"><i class="icon-base bx bxs-file-excel icon-sm text-white"></i> {{ __('Exportar Excel') }}</a>
                        <a href="{{ route('sales.export.pdf') }}" class="btn btn-danger mb-3 ms-2"><i class="icon-base bx bxs-file-pdf icon-sm text-white"></i> {{ __('Exportar PDF') }}</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="listViewContent">
                    <div class="table-responsive text-nowrap">
                        <table class="table display" id="sales-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">{{ __('Pedido') }}</th>
                                    <th>{{ __('Cliente') }}</th>
                                    <th class="text-center">{{ __('Data da Venda') }}</th>
                                    <th class="text-center">{{ __('Entrega Prevista') }}</th>
                                    <th class="text-center">{{ __('Total') }}</th>
                                    <th class="text-center">{{ __('Status') }}</th>
                                    <th class="text-center" style="width: 5%;">{{ __('Ações') }}</th>
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
                                 <div class="card mb-3" style="box-shadow: none">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">{{ $status->label() }}</h5>
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
                {{-- Removed existing pagination --}}
            </div>
        </div>
    </div>

</div>
@endsection

@section('page-script')
<script type="module">
    $(document).ready(function() {
        const canSeePrices = {{ (Auth::check() && Auth::user()->role_id == 1) ? 'true' : 'false' }};
        $('#sales-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('sales.data') }}", // We will create this route
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

        // Lógica para alternar entre visualização de lista e Kanban
        $('#listViewBtn').on('click', function() {
            $('#listViewContent').show();
            $('#kanbanViewContent').hide();
            $('#listViewBtn').addClass('active');
            $('#kanbanViewBtn').removeClass('active');
            localStorage.setItem('salesViewPreference', 'list'); // Salva a preferência
        });

        $('#kanbanViewBtn').on('click', function() {
            $('#listViewContent').hide();
            $('#kanbanViewContent').show();
            $('#kanbanViewBtn').addClass('active');
            $('#listViewBtn').removeClass('active');
            loadKanbanData(); // Função para carregar os dados do Kanban
            localStorage.setItem('salesViewPreference', 'kanban'); // Salva a preferência
        });

        // Carregar a preferência do usuário ao carregar a página
        const savedViewPreference = localStorage.getItem('salesViewPreference');
        if (savedViewPreference === 'kanban') {
            $('#kanbanViewBtn').click();
        } else {
            $('#listViewBtn').click();
        }

        function loadKanbanData() {
            console.log("Carregando dados do Kanban...");
            const statusColors = {
                'In Production': 'bg-label-info',
                'Open': 'bg-label-secondary',
                'Delivered': 'bg-label-primary',
                'In Transit': 'bg-label-warning',
                'In Assembly': 'bg-label-success',
                'Cancelled': 'bg-label-danger'
            };
            const orderStatusesValues = @json(collect($orderStatuses)->map(fn($status) => str_replace(' ', '-', $status->value)));
            $.ajax({
                url: "{{ route('sales.kanbanData') }}",
                method: "GET",
                success: function(data) {
                    // Limpar colunas existentes
                    $('.kanban-items').empty();

                    // Agrupar vendas por status
                    const salesByStatus = {};
                    data.forEach(sale => {
                        const statusKey = sale.order_status.replace(/ /g, '-'); // Substituir espaços por hífens
                        if (!salesByStatus[statusKey]) {
                            salesByStatus[statusKey] = [];
                        }
                        salesByStatus[statusKey].push(sale);
                    });

                    // Renderizar os cards nas colunas corretas
                    orderStatusesValues.forEach(statusValue => {
                        const column = $(`#kanban-column-${statusValue}`);
                        if (column.length) {
                            if (salesByStatus[statusValue] && salesByStatus[statusValue].length > 0) {
                                salesByStatus[statusValue].forEach(sale => {
                                    const card = `
                                        <div class="card kanban-item mb-3" data-sale-id="${sale.id}" style="${(() => {
                                            const today = new Date();
                                            today.setHours(0, 0, 0, 0);
                                            const deliveryDate = new Date(sale.expected_delivery_date);
                                            deliveryDate.setHours(0, 0, 0, 0);
                                            return (deliveryDate < today && sale.actual_delivery_date === null) ? 'background-color: #FFE0DB;' : '';
                                        })()}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge ${statusColors[sale.order_status] || 'bg-label-primary'}">${sale.erp_code || sale.id}</span>
                                                    <i class="bx bx-dots-vertical-rounded cursor-pointer dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="/sales/${sale.id}">
                                                                <i class="bx bx-show me-2"></i> Ver Detalhes
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="/sales/${sale.id}/edit">
                                                                <i class="bx bx-edit me-2"></i> Editar
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form action="/sales/${sale.id}" method="POST" class="delete-form">
                                                                <input type="hidden" name="_token" value="${$('meta[name=\"csrf-token\"]').attr('content')}">
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="bx bx-trash me-2"></i> Excluir
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <h6 class="card-title">${sale.customer.full_name || sale.customer.company_name}</h6>
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <div class="d-flex align-items-center">
                                                        ${(() => {
                                                            const today = new Date();
                                                            today.setHours(0, 0, 0, 0);
                                                            const deliveryDate = new Date(sale.expected_delivery_date);
                                                            deliveryDate.setHours(0, 0, 0, 0);
                                                            const badgeClass = (deliveryDate < today && sale.actual_delivery_date === null) ? 'bg-label-danger' : 'bg-label-warning';
                                                            const formattedDate = deliveryDate.toLocaleDateString('pt-BR', {
                                                                 day: '2-digit',
                                                                 month: '2-digit',
                                                                 year: 'numeric'
                                                             });
                                                             return `<span class="badge ${badgeClass}">${formattedDate}</span>`;
                                                         })()}
                                                    </div>
                                                    <div class="avatar-group">
                                                        ${sale.assigned_users && sale.assigned_users.length > 0 ?
                                                            sale.assigned_users.map(user => `
                                                                <div class="avatar avatar-sm pull-up" data-bs-toggle="tooltip" data-bs-placement="top" title="${user.name}">
                                                                    <img src="${user.avatar_url || 'https://via.placeholder.com/150'}" alt="Avatar" class="rounded-circle">
                                                                </div>
                                                            `).join('')
                                                            : ''
                                                        }
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    column.append(card);
                                });
                            } else {
                                column.append('<p>Nenhum pedido neste status.</p>');
                            }
                        }
                    });

                    // Inicializar SortableJS para cada coluna Kanban
                    orderStatusesValues.forEach(statusValue => {
                        const kanbanColumn = document.getElementById(`kanban-column-${statusValue}`);
                        if (kanbanColumn) {
                            new Sortable(kanbanColumn, {
                                group: 'kanban-sales',
                                animation: 150,
                                ghostClass: 'kanban-item-ghost',
                                onEnd: function (evt) {
                                    const saleId = $(evt.item).data('sale-id');
                                    const newStatus = evt.to.id.replace('kanban-column-', '').replace(/-/g, ' ');
                                    
                                    $.ajax({
                                        url: `/sales/${saleId}/update-status`,
                                        method: 'PATCH',
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            status: newStatus
                                        },
                                        success: function(response) {
                                            console.log('Status atualizado com sucesso:', response);
                                            // Opcional: recarregar dados do kanban ou atualizar apenas o card movido
                                        },
                                        error: function(error) {
                                            console.error('Erro ao atualizar status:', error);
                                            // Opcional: reverter a movimentação do card em caso de erro
                                        }
                                    });
                                },
                            });
                        }
                    });
                },
                error: function(error) {
                    console.error("Erro ao carregar dados do Kanban:", error);
                }
            });
        }
    });
</script>
@endsection
