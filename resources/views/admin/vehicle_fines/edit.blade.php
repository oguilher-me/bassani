@extends('layouts/contentNavbarLayout')

@section('title', __('Editar Multa de Veículo'))

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
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Editar Multa de Veículo') }}</h4>
        <p class="text-muted mb-0">{{ $vehicleFine->fine_number }} - {{ $vehicleFine->vehicle->modelo ?? '-' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('vehicle_fines.show', $vehicleFine->id) }}" class="btn btn-outline-secondary">
            <i class="bx bx-show me-1"></i> {{ __('Ver Detalhes') }}
        </a>
        <a href="{{ route('vehicle_fines.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('vehicle_fines.update', $vehicleFine->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    {{-- Veículo e Motorista --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-car me-2 text-danger"></i>{{ __('Veículo e Motorista') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="vehicle_id" class="form-label">{{ __('Veículo') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                                <option value="">{{ __('Selecione o Veículo') }}</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ (old('vehicle_id', $vehicleFine->vehicle_id) == $vehicle->id) ? 'selected' : '' }}>{{ $vehicle->modelo }} ({{ $vehicle->placa }})</option>
                                @endforeach
                            </select>
                            @error('vehicle_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="driver_id" class="form-label">{{ __('Motorista') }}</label>
                            <select class="form-select" id="driver_id" name="driver_id">
                                <option value="">{{ __('Selecione o Motorista') }}</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ (old('driver_id', $vehicleFine->driver_id) == $driver->id) ? 'selected' : '' }}>{{ $driver->full_name }}</option>
                                @endforeach
                            </select>
                            @error('driver_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    {{-- Dados da Multa --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-receipt me-2 text-danger"></i>{{ __('Dados da Multa') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="fine_number" class="form-label">{{ __('Número da Multa') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="fine_number" name="fine_number" value="{{ old('fine_number', $vehicleFine->fine_number) }}" required>
                            @error('fine_number')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="fine_type" class="form-label">{{ __('Tipo de Multa') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="fine_type" name="fine_type" value="{{ old('fine_type', $vehicleFine->fine_type) }}" required>
                            @error('fine_type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="points" class="form-label">{{ __('Pontos') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="points" name="points" value="{{ old('points', $vehicleFine->points) }}" min="0" required>
                            @error('points')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label for="description" class="form-label">{{ __('Descrição') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="2" required>{{ old('description', $vehicleFine->description) }}</textarea>
                            @error('description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    {{-- Datas --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-calendar me-2 text-danger"></i>{{ __('Datas') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="infraction_date" class="form-label">{{ __('Data da Infração') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpickr-date" id="infraction_date" name="infraction_date" value="{{ old('infraction_date', $vehicleFine->infraction_date ? $vehicleFine->infraction_date->format('Y-m-d') : '') }}" required>
                            @error('infraction_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="notification_date" class="form-label">{{ __('Data da Notificação') }}</label>
                            <input type="text" class="form-control flatpickr-date" id="notification_date" name="notification_date" value="{{ old('notification_date', $vehicleFine->notification_date ? $vehicleFine->notification_date->format('Y-m-d') : '') }}">
                            @error('notification_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="due_date" class="form-label">{{ __('Data de Vencimento') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpickr-date" id="due_date" name="due_date" value="{{ old('due_date', $vehicleFine->due_date ? $vehicleFine->due_date->format('Y-m-d') : '') }}" required>
                            @error('due_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    {{-- Local da Infração --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-map me-2 text-danger"></i>{{ __('Local da Infração') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="location" class="form-label">{{ __('Local') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $vehicleFine->location) }}" required>
                            @error('location')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="authority" class="form-label">{{ __('Autoridade') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="authority" name="authority" value="{{ old('authority', $vehicleFine->authority) }}" required>
                            @error('authority')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    {{-- Valores e Pagamento --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-money me-2 text-danger"></i>{{ __('Valores e Pagamento') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="fine_amount" class="form-label">{{ __('Valor da Multa') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" step="0.01" class="form-control money-mask" id="fine_amount" name="fine_amount" value="{{ old('fine_amount', $vehicleFine->fine_amount) }}" required>
                            </div>
                            @error('fine_amount')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="paid_amount" class="form-label">{{ __('Valor Pago') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" step="0.01" class="form-control money-mask" id="paid_amount" name="paid_amount" value="{{ old('paid_amount', $vehicleFine->paid_amount) }}">
                            </div>
                            @error('paid_amount')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="payment_date" class="form-label">{{ __('Data do Pagamento') }}</label>
                            <input type="text" class="form-control flatpickr-date" id="payment_date" name="payment_date" value="{{ old('payment_date', $vehicleFine->payment_date ? $vehicleFine->payment_date->format('Y-m-d') : '') }}">
                            @error('payment_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="payment_status" class="form-label">{{ __('Status do Pagamento') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="payment_status" name="payment_status" required>
                                <option value="">{{ __('Selecione') }}</option>
                                @foreach(\App\Enums\PaymentStatus::cases() as $status)
                                    <option value="{{ $status->value }}" {{ (old('payment_status', $vehicleFine->payment_status->value) == $status->value) ? 'selected' : '' }}>{{ $status->getLabel() }}</option>
                                @endforeach
                            </select>
                            @error('payment_status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="responsible_for_payment" class="form-label">{{ __('Responsável pelo Pagamento') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="responsible_for_payment" name="responsible_for_payment" required>
                                <option value="">{{ __('Selecione o Responsável') }}</option>
                                @foreach(\App\Enums\ResponsibleForPayment::cases() as $responsible)
                                    <option value="{{ $responsible->value }}" {{ (old('responsible_for_payment', $vehicleFine->responsible_for_payment->value) == $responsible->value) ? 'selected' : '' }}>{{ $responsible->getLabel() }}</option>
                                @endforeach
                            </select>
                            @error('responsible_for_payment')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="document_reference" class="form-label">{{ __('Documento de Referência') }}</label>
                            <input type="file" class="form-control" id="document_reference" name="document_reference">
                            @if($vehicleFine->document_reference)
                                <small class="text-muted">{{ __('Documento atual:') }} <a href="{{ Storage::url($vehicleFine->document_reference) }}" target="_blank">{{ __('Visualizar') }}</a></small>
                            @endif
                            @error('document_reference')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label for="comments" class="form-label">{{ __('Comentários') }}</label>
                            <textarea class="form-control" id="comments" name="comments" rows="3">{{ old('comments', $vehicleFine->comments) }}</textarea>
                            @error('comments')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('vehicle_fines.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-x me-1"></i> {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-check me-1"></i> {{ __('Atualizar') }}
                        </button>
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
                value = value.replace('R$', '');
                value = value.replace(/\./g, '');
                value = value.replace(',', '.');
                input.value = value.trim();
            });
        });
    });
</script>
@endsection