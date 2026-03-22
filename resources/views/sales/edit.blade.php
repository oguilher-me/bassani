@extends('layouts/contentNavbarLayout')

@section('title', __('Editar Venda'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Editar Venda') }}</h5>
            </div>
            <div class="card-body">
                @php $canSeePrices = Auth::check() && Auth::user()->role_id == 1; @endphp
                <form action="{{ route('sales.update', $sale->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="customer_id" class="form-label">{{ __('Cliente') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="customer_id" name="customer_id" required>
                                <option value="">{{ __('Selecione o Cliente') }}</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ (old('customer_id', $sale->customer_id) == $customer->id) ? 'selected' : '' }}>{{ $customer->full_name ?? $customer->company_name }}</option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="issue_date" class="form-label">{{ __('Data da Venda') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="issue_date" name="issue_date" value="{{ old('issue_date', $sale->issue_date->format('Y-m-d')) }}" required>
                            @error('issue_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="order_status" class="form-label">{{ __('Status do Pedido') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="order_status" name="order_status" required>
                                @foreach($orderStatuses as $status)
                                    <option value="{{ $status->value }}" {{ (old('order_status', $sale->order_status->value) == $status->value) ? 'selected' : '' }}>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                            @error('order_status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="payment_status" class="form-label">{{ __('Status do Pagamento') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="payment_status" name="payment_status" required>
                                @foreach($paymentStatuses as $status)
                                    <option value="{{ $status->value }}" {{ (old('payment_status', $sale->payment_status->value) == $status->value) ? 'selected' : '' }}>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                            @error('payment_status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="payment_method" class="form-label">{{ __('Método de Pagamento') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="payment_method" name="payment_method" required>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->value }}" {{ (old('payment_method', $sale->payment_method->value) == $method->value) ? 'selected' : '' }}>{{ $method->label() }}</option>
                                @endforeach
                            </select>
                            @error('payment_method')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>{{ __('Itens da Venda') }}</h5>
                            <div id="sale-items-container">
                                @foreach($sale->saleItems as $index => $item)
                                    <div class="row sale-item mb-3" data-item-id="{{ $item->id }}">
                                        <div class="mb-3 col-md-4">
                                            <label for="sale_items_{{ $index }}_product_id" class="form-label">{{ __('Produto') }} <span class="text-danger">*</span></label>
                                            <select class="form-select select2" id="sale_items_{{ $index }}_product_id" name="sale_items[{{ $index }}][product_id]" required>
                                                <option value="">{{ __('Selecione o Produto') }}</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" {{ (old("sale_items.{$index}.product_id", $item->product_id) == $product->id) ? 'selected' : '' }}>{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-2">
                                            <label for="sale_items_{{ $index }}_quantity" class="form-label">{{ __('Quantidade') }} <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="sale_items_{{ $index }}_quantity" name="sale_items[{{ $index }}][quantity]" value="{{ old("sale_items.{$index}.quantity", $item->quantity) }}" min="1" required>
                                        </div>
                                        @if($canSeePrices)
                                        <div class="mb-3 col-md-2">
                                            <label for="sale_items_{{ $index }}_unit_price" class="form-label">{{ __('Preço Unitário') }} <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="sale_items_{{ $index }}_unit_price" name="sale_items[{{ $index }}][unit_price]" value="{{ old("sale_items.{$index}.unit_price", $item->unit_price) }}" step="0.01" min="0" required>
                                        </div>
                                        <div class="mb-3 col-md-2">
                                            <label for="sale_items_{{ $index }}_item_discount" class="form-label">{{ __('Desconto') }}</label>
                                            <input type="number" class="form-control" id="sale_items_{{ $index }}_item_discount" name="sale_items[{{ $index }}][item_discount]" value="{{ old("sale_items.{$index}.item_discount", $item->item_discount) }}" step="0.01" min="0">
                                        </div>
                                        @endif
                                        <div class="mb-3 col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger remove-sale-item">{{ __('Remover') }}</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-primary" id="add-sale-item">{{ __('Adicionar Item') }}</button>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="mb-3 col-md-12">
                            <label for="notes" class="form-label">{{ __('Observações') }}</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $sale->notes) }}</textarea>
                            @error('notes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="expected_delivery_date" class="form-label">{{ __('Entrega Prevista') }}</label>
                            <input type="date" class="form-control" id="expected_delivery_date" name="expected_delivery_date" value="{{ old('expected_delivery_date', $sale->expected_delivery_date ? $sale->expected_delivery_date->format('Y-m-d') : '') }}">
                            @error('expected_delivery_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6" id="actual_delivery_date_field">
                            <label for="actual_delivery_date" class="form-label">{{ __('Data Real de Entrega') }}</label>
                            <input type="date" class="form-control" id="actual_delivery_date" name="actual_delivery_date" value="{{ old('actual_delivery_date', $sale->actual_delivery_date ? $sale->actual_delivery_date->format('Y-m-d') : '') }}">
                            @error('actual_delivery_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">{{ __('Atualizar') }}</button>
                        <a href="{{ route('sales.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    const products = @json($products);
    document.addEventListener('DOMContentLoaded', function () {
        const orderStatusSelect = document.getElementById('order_status');
        const actualDeliveryDateField = document.getElementById('actual_delivery_date_field');

        function toggleActualDeliveryDateField() {
            if (orderStatusSelect.value === '{{ \App\Enums\OrderStatusEnum::Delivered->value }}') {
                actualDeliveryDateField.style.display = 'block';
            } else {
                actualDeliveryDateField.style.display = 'none';
            }
        }

        toggleActualDeliveryDateField();
        orderStatusSelect.addEventListener('change', toggleActualDeliveryDateField);
        $(orderStatusSelect).on('select2:select', toggleActualDeliveryDateField);

        let itemIndex = {{ count($sale->saleItems) }};

        function initializeSelect2(element) {
            $(element).select2({
                placeholder: "{{ __('Selecione o Produto') }}",
                allowClear: true,
                dropdownParent: $(element).parent()
            });
        }

        $('.sale-item select[name$="[product_id]"]').each(function() {
            initializeSelect2(this);
        });

        $('#add-sale-item').on('click', function () {
            const newItemHtml = `
                <div class="row sale-item mb-3">
                    <div class="mb-3 col-md-4">
                        <label for="sale_items_${itemIndex}_product_id" class="form-label">{{ __('Produto') }} <span class="text-danger">*</span></label>
                        <select class="form-select" id="sale_items_${itemIndex}_product_id" name="sale_items[${itemIndex}][product_id]" required>
                            <option value="">{{ __('Selecione o Produto') }}</option>
                            ${products.map(product => `
                                <option value="${product.id}">${product.name}</option>
                            `).join('')}
                        </select>
                    </div>
                    <div class="mb-3 col-md-2">
                        <label for="sale_items_${itemIndex}_description" class="form-label">{{ __('Descrição') }}</label>
                        <input type="text" class="form-control" id="sale_items_${itemIndex}_description" name="sale_items[${itemIndex}][description]">
                    </div>
                    <div class="mb-3 col-md-2">
                        <label for="sale_items_${itemIndex}_ipi" class="form-label">{{ __('IPI') }} <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="sale_items_${itemIndex}_ipi" name="sale_items[${itemIndex}][ipi]" value="0.00" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3 col-md-2">
                        <label for="sale_items_${itemIndex}_quantity" class="form-label">{{ __('Quantidade') }} <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="sale_items_${itemIndex}_quantity" name="sale_items[${itemIndex}][quantity]" value="1" min="1" required>
                    </div>
                    <div class="mb-3 col-md-2">
                        <label for="sale_items_${itemIndex}_unit_price" class="form-label">{{ __('Preço Unitário') }} <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="sale_items_${itemIndex}_unit_price" name="sale_items[${itemIndex}][unit_price]" value="0.00" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3 col-md-2">
                        <label for="sale_items_${itemIndex}_item_discount" class="form-label">{{ __('Desconto') }}</label>
                        <input type="number" class="form-control" id="sale_items_${itemIndex}_item_discount" name="sale_items[${itemIndex}][item_discount]" value="0.00" step="0.01" min="0">
                    </div>
                    <div class="mb-3 col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-sale-item">{{ __('Remover') }}</button>
                    </div>
                </div>
            `;
            $('#sale-items-container').append(newItemHtml);
            initializeSelect2($(`#sale_items_${itemIndex}_product_id`));
            itemIndex++;
        });

        $(document).on('click', '.remove-sale-item', function () {
            $(this).closest('.sale-item').remove();
        });
    });
</script>
@endsection
