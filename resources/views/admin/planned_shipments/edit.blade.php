@extends('layouts/contentNavbarLayout')

@section('title', __('Editar Carga Planejada'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Editar Carga Planejada') }}</h4>
        <p class="text-muted mb-0">{{ $plannedShipment->shipment_number }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('planned_shipments.show', $plannedShipment) }}" class="btn btn-outline-secondary">
            <i class="bx bx-show me-1"></i> {{ __('Ver Detalhes') }}
        </a>
        <a href="{{ route('planned_shipments.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('planned_shipments.update', $plannedShipment) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Dados da Carga --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-package me-2 text-danger"></i>{{ __('Dados da Carga') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="shipment_number" class="form-label">{{ __('Número da Carga') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="shipment_number" name="shipment_number" value="{{ old('shipment_number', $plannedShipment->shipment_number) }}" required>
                            @error('shipment_number')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Planned" {{ (old('status', $plannedShipment->status) == 'Planned') ? 'selected' : '' }}>{{ __('Planejada') }}</option>
                                <option value="In Transit" {{ (old('status', $plannedShipment->status) == 'In Transit') ? 'selected' : '' }}>{{ __('Em Transporte') }}</option>
                                <option value="Delivered" {{ (old('status', $plannedShipment->status) == 'Delivered') ? 'selected' : '' }}>{{ __('Entregue') }}</option>
                                <option value="Returned" {{ (old('status', $plannedShipment->status) == 'Returned') ? 'selected' : '' }}>{{ __('Devolvida') }}</option>
                                <option value="Cancelled" {{ (old('status', $plannedShipment->status) == 'Cancelled') ? 'selected' : '' }}>{{ __('Cancelada') }}</option>
                            </select>
                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="planned_departure_date" class="form-label">{{ __('Data de Saída Planejada') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="planned_departure_date" name="planned_departure_date" value="{{ old('planned_departure_date', $plannedShipment->planned_departure_date ? $plannedShipment->planned_departure_date->format('Y-m-d') : '') }}" required>
                            @error('planned_departure_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Veículo e Motorista --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-car me-2 text-danger"></i>{{ __('Veículo e Motorista') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="vehicle_id" class="form-label">{{ __('Veículo') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                                <option value="">{{ __('Selecione um veículo') }}</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ (old('vehicle_id', $plannedShipment->vehicle_id) == $vehicle->id) ? 'selected' : '' }}>{{ $vehicle->modelo }} ({{ $vehicle->placa }})</option>
                                @endforeach
                            </select>
                            @error('vehicle_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="driver_id" class="form-label">{{ __('Motorista') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="driver_id" name="driver_id" required>
                                <option value="">{{ __('Selecione um motorista') }}</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ (old('driver_id', $plannedShipment->driver_id) == $driver->id) ? 'selected' : '' }}>{{ $driver->full_name }}</option>
                                @endforeach
                            </select>
                            @error('driver_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Peso e Volume --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-box me-2 text-danger"></i>{{ __('Peso e Volume') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="total_weight" class="form-label">{{ __('Peso Total (kg)') }}</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="total_weight" name="total_weight" step="0.01" value="{{ old('total_weight', $plannedShipment->total_weight) }}">
                                <span class="input-group-text">kg</span>
                            </div>
                            @error('total_weight')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="total_volume" class="form-label">{{ __('Volume Total (m³)') }}</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="total_volume" name="total_volume" step="0.01" value="{{ old('total_volume', $plannedShipment->total_volume) }}">
                                <span class="input-group-text">m³</span>
                            </div>
                            @error('total_volume')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="remarks" class="form-label">{{ __('Observações') }}</label>
                            <input type="text" class="form-control" id="remarks" name="remarks" value="{{ old('remarks', $plannedShipment->remarks) }}">
                            @error('remarks')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Destinos --}}
                    <h6 class="text-uppercase text-muted mb-3">
                        <i class="bx bx-map me-2 text-danger"></i>{{ __('Destinos da Carga') }}
                    </h6>
                    <div id="destinations-wrapper" class="mb-3">
                        @foreach($plannedShipment->destinations as $idx => $dest)
                        <div class="card border-0 shadow-sm mb-3 destination-card">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                <span class="fw-semibold"><i class="bx bx-map-pin me-1 text-danger"></i> {{ __('Destino #') }}{{ $idx+1 }}</span>
                                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-destination">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label class="form-label">{{ __('Endereço Completo') }}</label>
                                        <input type="text" name="destinations_addresses[]" value="{{ $dest->address }}" class="form-control" placeholder="Endereço completo">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('Contato Direto') }}</label>
                                        <input type="text" name="destinations_contact_names[]" value="{{ $dest->contact_name }}" class="form-control" placeholder="Nome do contato">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('Telefone') }}</label>
                                        <input type="text" name="destinations_contact_phones[]" value="{{ $dest->contact_phone }}" class="form-control" placeholder="(00) 00000-0000">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('Início da Janela') }}</label>
                                        <input type="datetime-local" name="destinations_window_starts[]" value="{{ $dest->window_start ? $dest->window_start->format('Y-m-d\TH:i') : '' }}" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('Fim da Janela') }}</label>
                                        <input type="datetime-local" name="destinations_window_ends[]" value="{{ $dest->window_end ? $dest->window_end->format('Y-m-d\TH:i') : '' }}" class="form-control">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label mt-2">{{ __('Itens para este destino') }}</label>
                                        <select class="form-select select2 dest-items-select" name="destinations_items[{{ $idx }}][]" multiple="multiple">
                                            @foreach($orders as $order)
                                                @if($order->saleItems && $order->saleItems->count())
                                                    <optgroup label="Pedido #{{ $order->id }} - {{ $order->customer ? ($order->customer->customer_type=='PF' ? $order->customer->full_name : $order->customer->company_name) : '' }}">
                                                        @foreach($order->saleItems as $it)
                                                            <option value="{{ $it->id }}" {{ $dest->items->contains('id', $it->id) ? 'selected' : '' }}>
                                                                #{{ $order->id }} • {{ $it->product ? $it->product->name : $it->description }} • Qtd: {{ $it->quantity }} • R$ {{ number_format((float)($it->subtotal ?? ($it->quantity * $it->unit_price)),2,',','.') }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" id="btn-add-destination" class="btn btn-outline-primary mb-4">
                        <i class="bx bx-plus me-1"></i> {{ __('Adicionar destino') }}
                    </button>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('planned_shipments.index') }}" class="btn btn-outline-secondary">
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
    $(document).ready(function() {
        $('.select2').select2();
        
        const tmpl = (idx) => `
        <div class="card border-0 shadow-sm mb-3 destination-card">
          <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
            <span class="fw-semibold"><i class="bx bx-map-pin me-1 text-danger"></i> Destino #${idx+1}</span>
            <button type="button" class="btn btn-outline-danger btn-sm btn-remove-destination">
                <i class="bx bx-trash"></i>
            </button>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-8">
                <label class="form-label">Endereço Completo</label>
                <input type="text" name="destinations_addresses[]" class="form-control" placeholder="Endereço completo">
              </div>
              <div class="col-md-4">
                <label class="form-label">Contato Direto</label>
                <input type="text" name="destinations_contact_names[]" class="form-control" placeholder="Nome do contato">
              </div>
              <div class="col-md-4">
                <label class="form-label">Telefone</label>
                <input type="text" name="destinations_contact_phones[]" class="form-control" placeholder="(00) 00000-0000">
              </div>
              <div class="col-md-4">
                <label class="form-label">Início da Janela</label>
                <input type="datetime-local" name="destinations_window_starts[]" class="form-control">
              </div>
              <div class="col-md-4">
                <label class="form-label">Fim da Janela</label>
                <input type="datetime-local" name="destinations_window_ends[]" class="form-control">
              </div>
              <div class="col-12">
                <label class="form-label mt-2">Itens para este destino</label>
                <select class="form-select select2 dest-items-select" name="destinations_items[${idx}][]" multiple="multiple"></select>
              </div>
            </div>
          </div>
        </div>`;
        
        let destIndex = {{ max(0, $plannedShipment->destinations->count()-1) }};
        const optionsHtml = $('#items-options-template').html();
        
        $('#btn-add-destination').on('click', function(){
          destIndex++;
          const node = $(tmpl(destIndex));
          $('#destinations-wrapper').append(node);
          node.find('.select2').select2();
          node.find('.dest-items-select').html(optionsHtml).trigger('change');
        });
        
        $(document).on('click', '.btn-remove-destination', function(){
          $(this).closest('.destination-card').remove();
        });
    });
</script>

<select id="items-options-template" class="d-none">
  @foreach($orders as $order)
    @if($order->saleItems && $order->saleItems->count())
      <optgroup label="Pedido #{{ $order->id }} - {{ $order->customer ? ($order->customer->customer_type=='PF' ? $order->customer->full_name : $order->customer->company_name) : '' }}">
        @foreach($order->saleItems as $it)
          <option value="{{ $it->id }}">#{{ $order->id }} • {{ $it->product ? $it->product->name : $it->description }} • Qtd: {{ $it->quantity }} • R$ {{ number_format((float)($it->subtotal ?? ($it->quantity * $it->unit_price)),2,',','.') }}</option>
        @endforeach
      </optgroup>
    @endif
  @endforeach
</select>
@endsection