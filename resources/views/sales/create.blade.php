@extends('layouts/contentNavbarLayout')

@section('title', __('Cadastro de Venda'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Cadastro de Venda') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('sales.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="customer_id" class="form-label">{{ __('Cliente') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="customer_id" name="customer_id" required>
                                <option value="">{{ __('Selecione o Cliente') }}</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->full_name ?? $customer->company_name }}</option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="sale_date" class="form-label">{{ __('Data da Venda') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="sale_date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" required>
                            @error('sale_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="order_status" class="form-label">{{ __('Status do Pedido') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="order_status" name="order_status" required>
                                @foreach($orderStatuses as $status)
                                    <option value="{{ $status->value }}" {{ old('order_status') == $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
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
                                    <option value="{{ $status->value }}" {{ old('payment_status') == $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
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
                                    <option value="{{ $method->value }}" {{ old('payment_method') == $method->value ? 'selected' : '' }}>{{ $method->label() }}</option>
                                @endforeach
                            </select>
                            @error('payment_method')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Placeholder for Sale Items --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>{{ __('Itens da Venda') }}</h5>
                            <div id="sale-items-container">
                                {{-- Sale items will be added here dynamically --}}
                                <p>{{ __('Funcionalidade de adicionar itens será implementada aqui.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="mb-3 col-md-12">
                            <label for="notes" class="form-label">{{ __('Observações') }}</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">{{ __('Salvar') }}</button>
                        <a href="{{ route('sales.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')

@endsection