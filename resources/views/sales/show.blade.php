@extends('layouts/contentNavbarLayout')



@section('title', __('Detalhes da Venda'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-0">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Detalhes da Venda') }}</h5>
                <a href="{{ route('sales.index') }}" class="btn btn-primary">{{ __('Voltar') }}</a>
            </div>
        </div>
    </div>
</div>

<div class="row mb-6 gy-6">
    <!-- Left Column -->
    <div class="col-xl-8 col-lg-8 col-md-8 order-0 order-md-0">
        <!-- Order details -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Resumo') }}</h5>
                <div class="d-flex gap-2">
                    @if($sale->order_status === \App\Enums\OrderStatusEnum::Delivered)
                        <a href="{{ route('sales.scheduleAssembly.create', $sale->id) }}" class="btn btn-primary">
                            {{ __('Agendar Montagem') }}
                        </a>
                    @endif
                    <a href="javascript:void(0);" class="btn btn-label-primary btn-sm">{{ __('Editar') }}</a>
                </div>
            </div>
            <div class="card-body">
                @php $canSeePrices = Auth::check() && Auth::user()->role_id == 1; @endphp
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Item') }}</th>
                                <th>{{ __('QTD') }}</th>
                                <th>{{ __('IPI') }}</th>
                                <th>{{ __('R$ IPI') }}</th>
                                <th>{{ __('R$ Unit.') }}</th>
                                <th>{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($sale->saleItems ?? collect() as $item)
                            <tr>
                                <td>
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="avatar-wrapper me-3">
                                            <div class="avatar avatar-sm rounded-2 bg-label-secondary">
                                                @if($item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="Product" class="rounded-2">
                                                @else
                                                <i class="bx bx-package bx-sm"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                                            <small class="text-muted">{{ $item->product->description }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ ceil($item->quantity) }}</td>
                                <td>{{ number_format($item->IPI,2, ',', '.') }}%</td>
                                <td>{{ $canSeePrices ? number_format(($item->unit_price * $item->IPI) / 10,2, ',', '.') : '—' }}</td>
                                <td>{{ $canSeePrices ? number_format($item->unit_price, 2, ',', '.') : '—' }}</td>
                                <td>{{ $canSeePrices ? number_format($item->subtotal, 2, ',', '.') : '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">{{ __('Nenhum item de venda encontrado.') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4 offset-md-8" style="padding-right: 33px">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-medium">{{ __('Subtotal:') }}</span>
                            <span class="fw-bold">{{ $canSeePrices ? ('R$ ' . number_format($sale->total_items, 2, ',', '.')) : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-medium">{{ __('Desconto:') }}</span>
                            <span class="fw-bold">{{ $canSeePrices ? ('R$ ' . number_format($sale->total_discounts, 2, ',', '.')) : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-medium">{{ __('Frete:') }}</span>
                            <span class="fw-bold">{{ $canSeePrices ? ('R$ ' . number_format($sale->shipping_cost, 2, ',', '.')) : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-medium">{{ __('IPI:') }}</span>
                            <span class="fw-bold">{{ $canSeePrices ? ('R$ ' . number_format($sale->total_ipi, 2, ',', '.')) : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-medium">{{ __('ICMS:') }}</span>
                            <span class="fw-bold">{{ $canSeePrices ? ('R$ ' . number_format($sale->total_icms, 2, ',', '.')) : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-medium">{{ __('ICMS ST:') }}</span>
                            <span class="fw-bold">{{ $canSeePrices ? ('R$ ' . number_format($sale->total_icms_st, 2, ',', '.')) : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-medium">{{ __('DIFAL:') }}</span>
                            <span class="fw-bold">{{ $canSeePrices ? ('R$ ' . number_format($sale->total_difal, 2, ',', '.')) : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-medium">{{ __('Total:') }}</span>
                            <span class="fw-bold">{{ $canSeePrices ? ('R$ ' . number_format($sale->grand_total, 2, ',', '.')) : '—' }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0 fw-bold">{{ __('Total:') }}</h6>
                            <h6 class="mb-0 fw-bold">{{ $canSeePrices ? ('R$ ' . number_format($sale->grand_total, 2, ',', '.')) : '—' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Order details -->

        <!-- Shipping activity -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Linha do tempo do pedido') }}</h5>
            </div>
            <div class="card-body">
                <ul class="timeline pb-0 mb-0">
                    <li class="timeline-item timeline-item-transparent border-primary">
                        <span class="timeline-point timeline-point-primary"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">{{ __('Pedido realizado (ID do Pedido: #') }}{{ $sale->erp_code }}</h6>
                                <small class="text-muted">{{ $sale->issue_date->format('d/m/Y') }}</small>
                            </div>
                            <p class="mb-2">{{ __('Pedido foi registrado no ERP') }}</p>
                        </div>
                    </li>
                    <li class="timeline-item timeline-item-transparent border-primary">
                        <span class="timeline-point timeline-point-primary"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">{{ __('Pedido Registrado na Plataforma') }}</h6>
                                <small class="text-muted">{{ $sale->created_at->format('d/m/Y') }}</small>
                            </div>
                            <p class="mb-2">{{ __('Venda importada no sistema') }}</p>
                        </div>
                    </li>
                    <li class="timeline-item timeline-item-transparent 
                         @php
                             $expectedDeliveryPointClass = 'timeline-point-primary'; // Default to primary (on track)
                             if ($sale->actual_delivery_date instanceof \Carbon\Carbon) {
                                 if ($sale->actual_delivery_date->lt($sale->expected_delivery_date)) {
                                     $expectedDeliveryPointClass = 'timeline-point-success';
                                 } elseif ($sale->actual_delivery_date->gt($sale->expected_delivery_date)) {
                                     $expectedDeliveryPointClass = 'timeline-point-danger';
                                 }
                             } elseif ($sale->expected_delivery_date->lt(now())) {
                                 $expectedDeliveryPointClass = 'timeline-point-danger'; // Delayed
                             }
                         @endphp
                         border-{{ str_replace('timeline-point-', '', $expectedDeliveryPointClass) }}">
                         <span class="timeline-point {{ $expectedDeliveryPointClass }}"></span>
                         <div class="timeline-event">
                             <div class="timeline-header mb-1">
                                 <h6 class="mb-0">{{ __('Data prevista de entrega') }}</h6>
                                 <small class="text-muted">{{ $sale->expected_delivery_date->format('d/m/Y') }}</small>
                             </div>
                             <p class="mb-2">{{ __('Data estimada inicialmente') }}</p>
                             @if ($sale->expected_delivery_date->lt(now()) && !$sale->actual_delivery_date)
                                 @php
                                     $delayDays = floor($sale->expected_delivery_date->diffInDays(now()));
                                 @endphp
                                 <div class="alert alert-danger mt-2" role="alert">
                                     {{ __('Entrega atrasada em :days dias!', ['days' => $delayDays]) }}
                                 </div>
                             @endif
                         </div>
                     </li>
                    <li class="timeline-item timeline-item-transparent border-primary">
                        @php
                            $deliveryPointClass = 'timeline-point-warning'; // Default
                            if ($sale->order_status == \App\Enums\OrderStatusEnum::Delivered) {
                                $deliveryPointClass = 'timeline-point-success';
                            } elseif ($sale->actual_delivery_date && $sale->expected_delivery_date) {
                                if ($sale->actual_delivery_date->gt($sale->expected_delivery_date)) {
                                    $deliveryPointClass = 'timeline-point-danger';
                                } elseif ($sale->actual_delivery_date->lt($sale->expected_delivery_date)) {
                                    $deliveryPointClass = 'timeline-point-success';
                                }
                            }
                        @endphp
                        <span class="timeline-point {{ $deliveryPointClass }}"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">{{ __('Data Real de entrega') }}</h6>
                                <small class="text-muted">{{ $sale->actual_delivery_date ? $sale->actual_delivery_date->format('d/m/Y') : '-' }}</small>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /Shipping activity -->

         <!-- Assembly Schedules -->
         <div class="card mb-4">
             <div class="card-header">
                 <h5 class="mb-0">{{ __('Agendamentos de Montagem') }}</h5>
             </div>
             <div class="card-body">
                 @forelse ($sale->assemblySchedules as $schedule)
                     <div class="mb-3 border-bottom pb-3">
                         <div class="d-flex justify-content-between align-items-center">
                             <h6>Agendamento #{{ $schedule->id }}</h6>
                             <button type="button" class="btn btn-danger btn-sm delete-schedule-btn" data-id="{{ $schedule->id }}">
                                 <i class="bx bx-trash me-1"></i> {{ __('Excluir') }}
                             </button>
                         </div>
                         <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d/m/Y') }} às {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} </p>
                         <p><strong>Fim Previsto:</strong> {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                         @if($schedule->notes)
                             <p><strong>Observações:</strong> {{ $schedule->notes }}</p>
                         @endif
                         <h6>Montadores:</h6>
                         <ul>
                             @forelse ($schedule->assemblers as $assembler)
                                 <li>{{ $assembler->name }} (Comissão: R$ {{ number_format($assembler->pivot->commission_value, 2, ',', '.') }})</li>
                             @empty
                                 <li>Nenhum montador atribuído.</li>
                             @endforelse
                         </ul>
                     </div>
                 @empty
                     <p>Nenhum agendamento de montagem para esta venda.</p>
                 @endforelse
             </div>
         </div>
         <!-- /Assembly Schedules -->
     </div>
     <!-- /Left Column -->

    <!-- Right Column -->
    <div class="col-xl-4 col-lg-4 col-md-4 order-1 order-md-1">
        <!-- Customer details -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Detalhes do Cliente') }}</h5>
                <a href="javascript:void(0);" class="btn btn-label-primary btn-sm">{{ __('Editar') }}</a>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="d-flex flex-column">
                        <h6 class="mb-1">{{ $sale->customer->full_name ?? $sale->customer->company_name }}</h6>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                    @if ($sale->customer->customer_type === 'individual')
                        <small class="text-muted">{{ __('CPF: ') }}{{ $sale->customer->cpf ? sprintf('%s.%s.%s-%s', substr($sale->customer->cpf, 0, 3), substr($sale->customer->cpf, 3, 3), substr($sale->customer->cpf, 6, 3), substr($sale->customer->cpf, 9, 2)) : '-' }}</small>
                    @elseif ($sale->customer->customer_type === 'company')
                        <div>
                            <div class="row"><small class="text-muted">{{ __('CNPJ: ') }}{{ $sale->customer->cnpj ? sprintf('%s.%s.%s/%s-%s', substr($sale->customer->cnpj, 0, 2), substr($sale->customer->cnpj, 2, 3), substr($sale->customer->cnpj, 5, 3), substr($sale->customer->cnpj, 8, 4), substr($sale->customer->cnpj, 12, 2)) : '-' }}</small></div>
                            <div class="row mt-2"><small class="text-muted">{{ __('IE: ') }}{{ $sale->customer->ie ?? '-' }}</small></div>
                        </div>
                    @endif
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="bx bx-cart me-2"></i>
                    <span class="fw-medium">{{ __('Pedidos:') }}</span>
                    <span class="ms-auto">12</span> {{-- Placeholder for number of orders --}}
                </div>
                <hr>
                <h6 class="mb-3">{{ __('Contato para Entrega') }}</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-medium">{{ __('Nome:') }}</span>
                    <span>{{ $sale->contact_name ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-medium">{{ __('Email:') }}</span>
                    <span>{{ $sale->contact_email ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="fw-medium">{{ __('Telefone:') }}</span>
                    <span><a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $sale->contact_phone) }}?text=Ol%C3%A1%2C%20aqui%20%C3%A9%20da%20Bassani%20M%C3%B3veis" target="_blank">{{ $sale->contact_phone ?? '-' }}</a></span>
                </div>
            </div>
        </div>
        <!-- /Customer details -->

        <!-- Shipping address -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Endereço de Envio') }}</h5>
                <a href="javascript:void(0);" class="btn btn-label-primary btn-sm">{{ __('Editar') }}</a>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    {{ $sale->customer->shippingAddress->address_line_1 ?? 'N/A' }}<br>
                    {{ $sale->customer->shippingAddress->city ?? '' }}, {{ $sale->customer->shippingAddress->state ?? '' }} {{ $sale->customer->shippingAddress->zip_code ?? '' }}<br>
                    {{ $sale->customer->shippingAddress->country ?? '' }}
                </p>
            </div>
        </div>
        <!-- /Shipping address -->

        <!-- Billing address -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Endereço de Cobrança') }}</h5>
                <a href="javascript:void(0);" class="btn btn-label-primary btn-sm">{{ __('Editar') }}</a>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    {{ $sale->customer->address_street ?? 'N/A' }}, {{ $sale->customer->address_number ?? '' }}<br>
                    {{ $sale->customer->address_neighborhood ?? '' }}, {{ $sale->customer->address_city ?? '' }} - {{ $sale->customer->address_state ?? '' }}<br>
                    {{ $sale->customer->address_zip_code ?? '' }}
                </p>
            </div>
        </div>
        <!-- /Billing address -->

        <!-- Mastercard -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Mastercard') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <span class="fw-medium">{{ __('Número do Cartão:') }}</span>
                    <span>******{{ substr($sale->payment->card_number ?? 'N/A', -4) }}</span> {{-- Assuming card_number exists --}}
                </div>
            </div>
        </div>
        <!-- /Mastercard -->
    </div>
    <!-- /Right Column -->
</div>
@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-schedule-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const scheduleId = this.dataset.id;
                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Você não poderá reverter isso!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/assembly-schedules/${scheduleId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => {
                            if (response.redirected) {
                                window.location.href = response.url;
                                return;
                            }
                            if (!response.ok) {
                                return response.json().then(error => Promise.reject(error));
                            }
                            return response.json();
                        })
                        .then(data => {
                            Swal.fire({ icon: 'success', title: 'Sucesso!', text: data.message, showConfirmButton: true }).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(error => {
                            console.error('Erro ao excluir agendamento:', error);
                            Swal.fire({ icon: 'error', title: 'Erro!', text: error.message || 'Ocorreu um erro ao excluir o agendamento.' });
                        });
                    }
                });
            });
        });
    });
</script>
@endsection
