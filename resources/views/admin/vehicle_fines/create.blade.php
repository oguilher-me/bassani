@extends('layouts/contentNavbarLayout')

@section('title', __('Cadastro de Multa de Veículo'))

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/jquery-mask/jquery.mask.min.js') }}"></script>
@endsection

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Cadastro de Multa de Veículo') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('vehicle_fines.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="vehicle_id" class="form-label">{{ __('Veículo') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="vehicle_id" name="vehicle_id" required>
                                <option value="">{{ __('Selecione o Veículo') }}</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->placa }} - {{ $vehicle->modelo }}</option>
                                @endforeach
                            </select>
                            @error('vehicle_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="driver_id" class="form-label">{{ __('Motorista') }}</label>
                            <select class="form-select select2" id="driver_id" name="driver_id">
                                <option value="">{{ __('Selecione o Motorista') }}</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->full_name }}</option>
                                @endforeach
                            </select>
                            @error('driver_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="fine_number" class="form-label">{{ __('Número da Multa') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="fine_number" name="fine_number" value="{{ old('fine_number') }}" required>
                            @error('fine_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="infraction_date" class="form-label">{{ __('Data da Infração') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpickr-date" id="infraction_date" name="infraction_date" value="{{ old('infraction_date') }}" required>
                            @error('infraction_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="notification_date" class="form-label">{{ __('Data da Notificação') }}</label>
                            <input type="text" class="form-control flatpickr-date" id="notification_date" name="notification_date" value="{{ old('notification_date') }}">
                            @error('notification_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="due_date" class="form-label">{{ __('Data de Vencimento') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpickr-date" id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                            @error('due_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="payment_date" class="form-label">{{ __('Data do Pagamento') }}</label>
                            <input type="text" class="form-control flatpickr-date" id="payment_date" name="payment_date" value="{{ old('payment_date') }}">
                            @error('payment_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="fine_type" class="form-label">{{ __('Tipo da Multa') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="fine_type" name="fine_type" value="{{ old('fine_type') }}" required>
                            @error('fine_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('Descrição') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="location" class="form-label">{{ __('Local da Infração') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" required>
                            @error('location')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="authority" class="form-label">{{ __('Autoridade') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="authority" name="authority" value="{{ old('authority') }}" required>
                            @error('authority')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="points" class="form-label">{{ __('Pontos') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="points" name="points" value="{{ old('points') }}" min="0" required>
                            @error('points')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="fine_amount" class="form-label">{{ __('Valor da Multa') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control money-mask" id="fine_amount" name="fine_amount" value="{{ old('fine_amount') }}" required>
                            @error('fine_amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="paid_amount" class="form-label">{{ __('Valor Pago') }}</label>
                            <input type="text" class="form-control money-mask" id="paid_amount" name="paid_amount" value="{{ old('paid_amount') }}">
                            @error('paid_amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="payment_status" class="form-label">{{ __('Status do Pagamento') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="payment_status" name="payment_status" required>
                                <option value="">{{ __('Selecione o Status') }}</option>
                                @foreach($paymentStatuses as $status)
                                    <option value="{{ $status->value }}" {{ old('payment_status') == $status->value ? 'selected' : '' }}>{{ $status->getLabel() }}</option>
                                @endforeach
                            </select>
                            @error('payment_status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="responsible_for_payment" class="form-label">{{ __('Responsável pelo Pagamento') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="responsible_for_payment" name="responsible_for_payment" required>
                                <option value="">{{ __('Selecione o Responsável') }}</option>
                                @foreach($responsibleForPayments as $responsible)
                                    <option value="{{ $responsible->value }}" {{ old('responsible_for_payment') == $responsible->value ? 'selected' : '' }}>{{ $responsible->getLabel() }}</option>
                                @endforeach
                            </select>
                            @error('responsible_for_payment')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="document_reference" class="form-label">{{ __('Referência do Documento') }}</label>
                            <input type="file" class="form-control" id="document_reference" name="document_reference">
                            @error('document_reference')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="comments" class="form-label">{{ __('Comentários') }}</label>
                            <textarea class="form-control" id="comments" name="comments" rows="3">{{ old('comments') }}</textarea>
                            @error('comments')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">{{ __('Salvar') }}</button>
                        <a href="{{ route('vehicle_fines.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        form.addEventListener('submit', function () {
            document.querySelectorAll('.money-mask').forEach(function (input) {
                let value = input.value;
                value = value.replace('R$', ''); // Remove R$
                value = value.replace(/\./g, ''); // Remove dots
                value = value.replace(',', '.'); // Replace comma with dot
                input.value = value.trim();
            });
        });
    });
</script>
@endsection