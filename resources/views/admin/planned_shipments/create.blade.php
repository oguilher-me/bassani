@extends('layouts/contentNavbarLayout')

@section('title', __('Cadastro de Cargas Planejadas'))
 
@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl"> 
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Cadastro de Cargas Planejadas') }}</h5>
            </div> 
            <div class="card-body">

                  @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
                <form action="{{ route('planned_shipments.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="shipment_number" class="form-label">{{ __('Número da Carga') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="shipment_number" name="shipment_number" value="{{ old('shipment_number') }}" required>
                            @error('shipment_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="status" name="status" required>
                                <option value="Planned" {{ old('status') == 'Planned' ? 'selected' : '' }}>{{ __('Planejada') }}</option>
                                <option value="In Transit" {{ old('status') == 'In Transit' ? 'selected' : '' }}>{{ __('Em Transporte') }}</option>
                                <option value="Delivered" {{ old('status') == 'Delivered' ? 'selected' : '' }}>{{ __('Entregue') }}</option>
                                <option value="Returned" {{ old('status') == 'Returned' ? 'selected' : '' }}>{{ __('Devolvida') }}</option>
                                <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>{{ __('Cancelada') }}</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="vehicle_id" class="form-label">{{ __('Veículo') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="vehicle_id" name="vehicle_id">
                                <option value="">{{ __('Selecione um veículo') }}</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->modelo }} ({{ $vehicle->placa }})</option>
                                @endforeach
                            </select>
                            @error('vehicle_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="driver_id" class="form-label">{{ __('Motorista') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="driver_id" name="driver_id">
                                <option value="">{{ __('Selecione um motorista') }}</option>
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
                        <div class="mb-3 col-md-3">
                            <label for="planned_departure_date" class="form-label">{{ __('Data de Saída Planejada') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="planned_departure_date" name="planned_departure_date" value="{{ old('planned_departure_date') }}" required>
                            @error('planned_departure_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="mb-3 col-md-3">
                            <label for="total_weight" class="form-label">{{ __('Peso Total (kg)') }}</label>
                            <input type="number" class="form-control" id="total_weight" name="total_weight" value="{{ old('total_weight') }}" step="0.01">
                            @error('total_weight')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="total_volume" class="form-label">{{ __('Volume Total (m³)') }}</label>
                            <input type="number" class="form-control" id="total_volume" name="total_volume" value="{{ old('total_volume') }}" step="0.01">
                            @error('total_volume')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">{{ __('Observações') }}</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    

                    <div class="mb-3">
                        <h5 class="mb-4 mt-4">{{ __('Destinos das Cargas') }}</h5>
                        <div id="destinations-wrapper">
                            <div class="card mb-2 destination-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>{{ __('Destino #1') }}</span>
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-destination">{{ __('Remover') }}</button>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-md-8">
                                            <input type="text" name="destinations_addresses[]" class="form-control" placeholder="Endereço completo">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="destinations_contact_names[]" class="form-control" placeholder="Contato direto">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="destinations_contact_phones[]" class="form-control" placeholder="Telefone">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="datetime-local" name="destinations_window_starts[]" class="form-control" placeholder="Início da janela">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="datetime-local" name="destinations_window_ends[]" class="form-control" placeholder="Fim da janela">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label mt-2">{{ __('Itens para este destino') }}</label>
                                            <select class="form-select select2 dest-items-select" name="destinations_items[0][]" multiple="multiple"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="btn-add-destination" class="btn btn-outline-primary">{{ __('Adicionar destino') }}</button>
                    </div>


                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">{{ __('Salvar') }}</button>
                        <a href="{{ route('planned_shipments.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="module">
  $(function() {

    const tmpl = (idx) => `
    <div class="card mb-2 destination-card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Destino #${idx+1}</span>
        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-destination">Remover</button>
      </div>
      <div class="card-body">
        <div class="row g-2">
          <div class="col-md-8"><input type="text" name="destinations_addresses[]" class="form-control" placeholder="Endereço completo"></div>
          <div class="col-md-4"><input type="text" name="destinations_contact_names[]" class="form-control" placeholder="Contato direto"></div>
          <div class="col-md-4"><input type="text" name="destinations_contact_phones[]" class="form-control" placeholder="Telefone"></div>
          <div class="col-md-4"><input type="datetime-local" name="destinations_window_starts[]" class="form-control" placeholder="Início da janela"></div>
          <div class="col-md-4"><input type="datetime-local" name="destinations_window_ends[]" class="form-control" placeholder="Fim da janela"></div>
          <div class="col-12"><label class="form-label mt-2">Itens para este destino</label><select class="form-select select2 dest-items-select" name="destinations_items[${idx}][]" multiple="multiple"></select></div>
        </div>
      </div>
    </div>`;
    let destIndex = 0;
    const populateItemsOptions = (selectEl) => {
      const optionsHtml = $('#items-options-template').html();
      $(selectEl).html(optionsHtml).trigger('change');
    };
    populateItemsOptions($('.dest-items-select'));
    $('#btn-add-destination').on('click', function(){
      destIndex++;
      const node = $(tmpl(destIndex));
      $('#destinations-wrapper').append(node);
      node.find('.select2').select2();
      populateItemsOptions(node.find('.dest-items-select'));
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
