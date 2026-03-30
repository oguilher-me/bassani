@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes da Venda'))

@section('content')
@php $canSeePrices = Auth::check() && Auth::user()->role_id == 1; @endphp

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Detalhes da Venda') }} <span class="text-danger">#{{ $sale->erp_code }}</span></h4>
        <p class="text-muted mb-0">{{ __('Informações completas do pedido') }}</p>
    </div>
    <div class="d-flex gap-2">
        @if($sale->order_status === \App\Enums\OrderStatusEnum::Delivered)
            <a href="{{ route('sales.scheduleAssembly.create', $sale->id) }}" class="btn btn-sm btn-outline-primary">
                <i class="bx bx-calendar me-1"></i> {{ __('Agendar Montagem') }}
            </a>
        @endif
        <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-sm btn-outline-primary">
            <i class="bx bx-edit me-1"></i> {{ __('Editar') }}
        </a>
        <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column -->
    <div class="col-xl-8 col-lg-8">
        <!-- Order Summary -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-cart text-danger me-2"></i>{{ __('Resumo do Pedido') }}
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 px-4">{{ __('Item') }}</th>
                                <th class="border-0 py-3 text-center">{{ __('QTD') }}</th>
                                <th class="border-0 py-3 text-center">{{ __('IPI') }}</th>
                                <th class="border-0 py-3 text-end">{{ __('R$ IPI') }}</th>
                                <th class="border-0 py-3 text-end">{{ __('R$ Unit.') }}</th>
                                <th class="border-0 py-3 text-end">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($sale->saleItems ?? collect() as $item)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar rounded-circle bg-label-secondary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            @if($item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="Product" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i class="bx bx-package"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                                            <small class="text-muted">{{ $item->product->description }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 text-center">{{ ceil($item->quantity) }}</td>
                                <td class="py-3 text-center">{{ number_format($item->IPI, 2, ',', '.') }}%</td>
                                <td class="py-3 text-end">{{ $canSeePrices ? number_format(($item->unit_price * $item->IPI) / 10, 2, ',', '.') : '—' }}</td>
                                <td class="py-3 text-end">{{ $canSeePrices ? number_format($item->unit_price, 2, ',', '.') : '—' }}</td>
                                <td class="py-3 text-end fw-semibold">{{ $canSeePrices ? number_format($item->subtotal, 2, ',', '.') : '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">{{ __('Nenhum item de venda encontrado.') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Totals --}}
                <div class="row p-4">
                    <div class="col-md-5 offset-md-7">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">{{ __('Subtotal:') }}</span>
                            <span class="fw-semibold">{{ $canSeePrices ? ('R$ ' . number_format($sale->total_items, 2, ',', '.')) : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">{{ __('Desconto:') }}</span>
                            <span class="fw-semibold text-danger">- {{ $canSeePrices ? ('R$ ' . number_format($sale->total_discounts, 2, ',', '.')) : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">{{ __('Frete:') }}</span>
                            <span class="fw-semibold">{{ $canSeePrices ? ('R$ ' . number_format($sale->shipping_cost, 2, ',', '.')) : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">{{ __('IPI:') }}</span>
                            <span class="fw-semibold">{{ $canSeePrices ? ('R$ ' . number_format($sale->total_ipi, 2, ',', '.')) : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">{{ __('ICMS:') }}</span>
                            <span class="fw-semibold">{{ $canSeePrices ? ('R$ ' . number_format($sale->total_icms, 2, ',', '.')) : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">{{ __('ICMS ST:') }}</span>
                            <span class="fw-semibold">{{ $canSeePrices ? ('R$ ' . number_format($sale->total_icms_st, 2, ',', '.')) : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">{{ __('DIFAL:') }}</span>
                            <span class="fw-semibold">{{ $canSeePrices ? ('R$ ' . number_format($sale->total_difal, 2, ',', '.')) : '—' }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0 fw-bold">{{ __('Total Geral:') }}</h6>
                            <h5 class="mb-0 fw-bold" style="color: #DE0802;">
                                {{ $canSeePrices ? ('R$ ' . number_format($sale->grand_total, 2, ',', '.')) : '—' }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-time text-danger me-2"></i>{{ __('Linha do tempo do pedido') }}
                </h6>
            </div>
            <div class="card-body">
                <ul class="timeline pb-0 mb-0">
                    <li class="timeline-item timeline-item-transparent border-primary">
                        <span class="timeline-point timeline-point-primary"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">{{ __('Pedido realizado (ID: #') }}{{ $sale->erp_code }}</h6>
                                <small class="text-muted">{{ $sale->issue_date->format('d/m/Y') }}</small>
                            </div>
                            <p class="mb-0 text-muted small">{{ __('Pedido foi registrado no ERP') }}</p>
                        </div>
                    </li>
                    <li class="timeline-item timeline-item-transparent border-primary">
                        <span class="timeline-point timeline-point-primary"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">{{ __('Pedido Registrado na Plataforma') }}</h6>
                                <small class="text-muted">{{ $sale->created_at->format('d/m/Y') }}</small>
                            </div>
                            <p class="mb-0 text-muted small">{{ __('Venda importada no sistema') }}</p>
                        </div>
                    </li>
                    <li class="timeline-item timeline-item-transparent 
                         @php
                             $expectedDeliveryPointClass = 'timeline-point-primary';
                             if ($sale->actual_delivery_date instanceof \Carbon\Carbon) {
                                 if ($sale->actual_delivery_date->lt($sale->expected_delivery_date)) {
                                     $expectedDeliveryPointClass = 'timeline-point-success';
                                 } elseif ($sale->actual_delivery_date->gt($sale->expected_delivery_date)) {
                                     $expectedDeliveryPointClass = 'timeline-point-danger';
                                 }
                             } elseif ($sale->expected_delivery_date->lt(now())) {
                                 $expectedDeliveryPointClass = 'timeline-point-danger';
                             }
                         @endphp
                         border-{{ str_replace('timeline-point-', '', $expectedDeliveryPointClass) }}">
                         <span class="timeline-point {{ $expectedDeliveryPointClass }}"></span>
                         <div class="timeline-event">
                             <div class="timeline-header mb-1">
                                 <h6 class="mb-0">{{ __('Data prevista de entrega') }}</h6>
                                 <small class="text-muted">{{ $sale->expected_delivery_date->format('d/m/Y') }}</small>
                             </div>
                             <p class="mb-0 text-muted small">{{ __('Data estimada inicialmente') }}</p>
                             @if ($sale->expected_delivery_date->lt(now()) && !$sale->actual_delivery_date)
                                 @php
                                     $delayDays = floor($sale->expected_delivery_date->diffInDays(now()));
                                 @endphp
                                 <div class="alert alert-danger mt-2 py-2 px-3 small" role="alert">
                                     <i class="bx bx-error-circle me-1"></i>{{ __('Entrega atrasada em :days dias!', ['days' => $delayDays]) }}
                                 </div>
                             @endif
                         </div>
                     </li>
                    <li class="timeline-item timeline-item-transparent border-primary">
                        @php
                            $deliveryPointClass = 'timeline-point-warning';
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

        <!-- Assembly Schedules -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-calendar text-danger me-2"></i>{{ __('Agendamentos de Montagem') }}
                </h6>
            </div>
            <div class="card-body">
                @forelse ($sale->assemblySchedules as $schedule)
                    <div class="border rounded p-3 mb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 fw-semibold">{{ __('Agendamento') }} #{{ $schedule->id }}</h6>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-schedule-btn" data-id="{{ $schedule->id }}">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                        <div class="row small text-muted">
                            <div class="col-md-6">
                                <i class="bx bx-calendar me-1"></i>{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d/m/Y') }}
                                <i class="bx bx-time-five ms-2 me-1"></i>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                            </div>
                            <div class="col-md-6">
                                <i class="bx bx-time me-1"></i>{{ __('Fim:') }} {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                            </div>
                        </div>
                        @if($schedule->notes)
                            <p class="mb-2 mt-2 small"><strong>{{ __('Obs:') }}</strong> {{ $schedule->notes }}</p>
                        @endif
                        <div class="mt-2">
                            <small class="text-muted fw-semibold d-block mb-1">{{ __('Montadores:') }}</small>
                            <div class="d-flex flex-wrap gap-1">
                                @forelse ($schedule->assemblers as $assembler)
                                    <span class="badge bg-label-secondary rounded-pill px-2 py-1">
                                        {{ $assembler->name }}
                                    </span>
                                @empty
                                    <small class="text-muted">{{ __('Nenhum montador atribuído.') }}</small>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bx bx-calendar-x fs-1 text-muted"></i>
                        <p class="text-muted mt-2 mb-0">{{ __('Nenhum agendamento de montagem para esta venda.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-xl-4 col-lg-4">
        <!-- Customer Details -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-user text-danger me-2"></i>{{ __('Detalhes do Cliente') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        {{ strtoupper(substr($sale->customer->full_name ?? $sale->customer->company_name, 0, 1)) }}
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $sale->customer->full_name ?? $sale->customer->company_name }}</h6>
                        <small class="text-muted">{{ $sale->customer->customer_type === 'individual' ? __('Pessoa Física') : __('Pessoa Jurídica') }}</small>
                    </div>
                </div>
                
                <div class="mb-3">
                    @if ($sale->customer->customer_type === 'individual')
                        <div class="d-flex align-items-center mb-2">
                            <i class="bx bx-id-card text-muted me-2"></i>
                            <small>{{ __('CPF:') }} {{ $sale->customer->cpf ? sprintf('%s.%s.%s-%s', substr($sale->customer->cpf, 0, 3), substr($sale->customer->cpf, 3, 3), substr($sale->customer->cpf, 6, 3), substr($sale->customer->cpf, 9, 2)) : '-' }}</small>
                        </div>
                    @elseif ($sale->customer->customer_type === 'company')
                        <div class="d-flex align-items-center mb-2">
                            <i class="bx bx-buildings text-muted me-2"></i>
                            <small>{{ __('CNPJ:') }} {{ $sale->customer->cnpj ? sprintf('%s.%s.%s/%s-%s', substr($sale->customer->cnpj, 0, 2), substr($sale->customer->cnpj, 2, 3), substr($sale->customer->cnpj, 5, 3), substr($sale->customer->cnpj, 8, 4), substr($sale->customer->cnpj, 12, 2)) : '-' }}</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bx bx-certification text-muted me-2"></i>
                            <small>{{ __('IE:') }} {{ $sale->customer->ie ?? '-' }}</small>
                        </div>
                    @endif
                </div>

                <hr>

                <h6 class="fw-semibold mb-3 small text-uppercase text-muted">{{ __('Contato para Entrega') }}</h6>
                <div class="d-flex align-items-center mb-2">
                    <i class="bx bx-user text-muted me-2"></i>
                    <small>{{ $sale->contact_name ?? '-' }}</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bx bx-envelope text-muted me-2"></i>
                    <small>{{ $sale->contact_email ?? '-' }}</small>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bx bx-phone text-muted me-2"></i>
                    <small>
                        <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $sale->contact_phone) }}?text=Ol%C3%A1%2C%20aqui%20%C3%A9%20da%20Bassani%20M%C3%B3veis" target="_blank" class="text-decoration-none">
                            {{ $sale->contact_phone ?? '-' }}
                        </a>
                    </small>
                </div>
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-map text-danger me-2"></i>{{ __('Endereço de Envio') }}
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-0 small">
                    {{ $sale->customer->shippingAddress->address_line_1 ?? 'N/A' }}<br>
                    {{ $sale->customer->shippingAddress->city ?? '' }}, {{ $sale->customer->shippingAddress->state ?? '' }}<br>
                    CEP: {{ $sale->customer->shippingAddress->zip_code ?? '' }}<br>
                    {{ $sale->customer->shippingAddress->country ?? '' }}
                </p>
            </div>
        </div>

        <!-- Billing Address -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-receipt text-danger me-2"></i>{{ __('Endereço de Cobrança') }}
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-0 small">
                    {{ $sale->customer->address_street ?? 'N/A' }}, {{ $sale->customer->address_number ?? '' }}<br>
                    {{ $sale->customer->address_neighborhood ?? '' }}<br>
                    {{ $sale->customer->address_city ?? '' }} - {{ $sale->customer->address_state ?? '' }}<br>
                    CEP: {{ $sale->customer->address_zip_code ?? '' }}
                </p>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-credit-card text-danger me-2"></i>{{ __('Pagamento') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <i class="bx bx-credit-card-front text-muted me-2"></i>
                    <small>{{ __('Cartão:') }} {{ $sale->payment && $sale->payment->card_number ? '******' . substr($sale->payment->card_number, -4) : 'N/A' }}</small>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bx bx-check-circle text-muted me-2"></i>
                    <small>{{ __('Status:') }} 
                        <span class="badge bg-label-success rounded-pill px-2 py-1 ms-1">
                            {{ $sale->payment_status->label() ?? 'N/A' }}
                        </span>
                    </small>
                </div>
            </div>
        </div>
    </div>
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
                    confirmButtonColor: '#DE0802',
                    cancelButtonColor: '#1F2A44',
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
                            Swal.fire({ 
                                icon: 'success', 
                                title: 'Sucesso!', 
                                text: data.message, 
                                confirmButtonColor: '#DE0802',
                                showConfirmButton: true 
                            }).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(error => {
                            console.error('Erro ao excluir agendamento:', error);
                            Swal.fire({ 
                                icon: 'error', 
                                title: 'Erro!', 
                                text: error.message || 'Ocorreu um erro ao excluir o agendamento.',
                                confirmButtonColor: '#DE0802'
                            });
                        });
                    }
                });
            });
        });
    });
</script>
@endsection
