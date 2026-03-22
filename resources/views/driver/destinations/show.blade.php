@extends('layouts/contentNavbarLayout')
@section('title', __('Detalhes da Entrega'))
@section('content')
@php $canSeePrices = Auth::check() && Auth::user()->role_id == 1; @endphp
<div class="row mb-3">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ __('Destino') }} #{{ $destination->id }}</span>
        <div>
          @if(is_null($destination->confirmation_status))
            <a href="{{ route('driver.destinations.start.form', $destination->id) }}" class="btn btn-outline-primary me-2">{{ __('Iniciar Entrega') }}</a>
          @endif
          @if($destination->confirmation_status == 'started')
            <a href="{{ route('driver.destinations.finish.form', $destination->id) }}" class="btn btn-outline-success">{{ __('Finalizar Entrega') }}</a>
            @if($destination->contact_phone)
              @php
                $driverName = optional($destination->plannedShipment->driver)->full_name ?? optional($destination->plannedShipment->driver)->name;
                $saleObj = optional($destination->items->first()->sale);

                $erpCode = $saleObj->erp_code ?? '';
                $msg = "Olá " . ($destination->contact_name ?? '') . "! Meu nome é " . ($driverName ?? '') . ", sou motorista da Bassani Móveis e estamos indo até você para entregar itens do pedido #" . ($erpCode ?? '') . ". Em breve chegaremos.";
                $phone = '+55' . preg_replace('/\D+/', '', $destination->contact_phone);
                $waUrl = 'https://wa.me/' . $phone . '?text=' . urlencode($msg);
              @endphp
              <a href="{{ $waUrl }}" target="_blank" class="btn btn-success ms-2">{{ __('Enviar WhatsApp') }}</a>
            @endif
          @endif
        </div>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="mb-2"><strong>{{ __('Endereço') }}:</strong> {{ $destination->address }}</div>
            <div class="mb-2"><strong>{{ __('Contato') }}:</strong> {{ $destination->contact_name }} • {{ $destination->contact_phone }}</div>
            <div class="mb-2"><strong>{{ __('Janela') }}:</strong> {{ optional($destination->window_start)->format('d/m/Y H:i') }} - {{ optional($destination->window_end)->format('d/m/Y H:i') }}</div>
            <div class="mb-2"><strong>{{ __('Status') }}:</strong> {{ $destination->confirmation_status ?? 'planejada' }}</div>
          </div>
          <div class="col-md-6">
            <div class="mb-2"><strong>{{ __('Carga') }}:</strong> {{ $destination->plannedShipment->shipment_number }}</div>
            <div class="mb-2"><strong>{{ __('Motorista') }}:</strong> {{ optional($destination->plannedShipment->driver)->full_name ?? optional($destination->plannedShipment->driver)->name }}</div>
            <div class="mb-2"><strong>{{ __('Veículo') }}:</strong> {{ optional($destination->plannedShipment->vehicle)->modelo }} {{ optional($destination->plannedShipment->vehicle)->placa }}</div>
            <div class="mb-2"><strong>{{ __('Status da carga') }}:</strong> {{ $destination->plannedShipment->status }}</div>
          </div>
        </div>

        <hr/> 
        @if(isset($evaluation) && $evaluation)
          <div class="alert alert-info d-flex justify-content-between align-items-center">
            <div>{{ __('Compartilhe o link de avaliação com o cliente:') }}</div>
            <a href="{{ route('delivery-evaluation.show', $evaluation->token) }}" target="_blank" class="btn btn-sm btn-outline-primary">{{ __('Abrir Avaliação de Entrega') }}</a>
          </div>
        @endif

        @if (isset($evaluation) && $evaluation->submitted_at)
                                <div class="mb-2"><strong>{{ __('NPS:') }}</strong>
                                    <span class="badge px-2 bg-label-{{ $evaluation->nps_score >= 7 ? 'success' : ($evaluation->nps_score >= 4 ? 'warning' : 'danger') }} text-capitalized">
                                        {{ $evaluation->nps_score }}
                                    </span>
                                </div>
                                @if ($evaluation->comments)
                                    <div class="mb-2"><strong>{{ __('Comentários:') }}</strong> {{ $evaluation->comments }}</div>
                                @endif
                                @php $evalPhotos = $evaluation->photo_paths ? json_decode($evaluation->photo_paths, true) : []; @endphp
                                @if (!empty($evalPhotos))
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($evalPhotos as $photo)
                                            <img src="{{ Storage::url($photo) }}" alt="Foto da avaliação" class="img-thumbnail" style="max-width: 200px; height: auto;">
                                        @endforeach
                                    </div>
                                @endif
                                <div class="mt-2 text-muted small">{{ __('Enviado em:') }} {{ \Carbon\Carbon::parse($evaluation->submitted_at)->format('d/m/Y H:i') }}</div>
                            @endif

        <hr/>
        <h6>{{ __('Itens vinculados a este destino') }}</h6>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead><tr><th>{{ __('Pedido') }}</th><th>{{ __('Produto') }}</th><th>{{ __('Descrição') }}</th><th>{{ __('Quantidade') }}</th><th>{{ __('Subtotal') }}</th></tr></thead>
            <tbody>
              @foreach($destination->items as $it)
                <tr>
                  <td>{{ $it->sale_id }}</td>
                  <td>{{ optional($it->product)->name }}</td>
                  <td>{{ $it->description }}</td>
                  <td>{{ $it->quantity }}</td>
                  <td>@if($canSeePrices) R$ {{ number_format((float)($it->subtotal ?? ($it->quantity * $it->unit_price)),2,',','.') }} @else — @endif</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <hr/>
        <div class="row g-3">
          <div class="col-md-6">
            <h6>{{ __('Evidência de início') }}</h6>
            <div class="mb-2"><strong>{{ __('Status') }}:</strong> {{ $destination->confirmation_status }}</div>
            <div class="mb-2"><strong>{{ __('Iniciado em') }}:</strong> {{ optional($destination->started_at)->format('d/m/Y H:i') }}</div>
            @if($destination->start_photo_path)
              <img src="{{ Storage::disk('public')->url($destination->start_photo_path) }}" alt="start" class="img-fluid rounded border" style="max-width:280px"/>
            @endif
            @if($destination->start_latitude)
              <div class="small text-muted">GPS: {{ $destination->start_latitude }}, {{ $destination->start_longitude }} (±{{ $destination->start_accuracy }}m)</div>
            @endif
          </div>
          <div class="col-md-6">
            <h6>{{ __('Evidência de término') }}</h6>
            <div class="mb-2"><strong>{{ __('Finalizado em') }}:</strong> {{ optional($destination->finished_at)->format('d/m/Y H:i') }}</div>
            @if($destination->finish_photo_paths)
              <div class="d-flex flex-wrap gap-2">
                @foreach($destination->finish_photo_paths as $p)
                  <img src="{{ Storage::disk('public')->url($p) }}" alt="finish" class="img-fluid rounded border" style="max-width:150px"/>
                @endforeach
              </div>
            @endif
            @if($destination->finish_notes)
              <div class="mt-2"><strong>{{ __('Notas') }}:</strong> {{ $destination->finish_notes }}</div>
            @endif
            @if($destination->finish_pending_reason)
              <div class="mt-2"><strong>{{ __('Pendências') }}:</strong> {{ $destination->finish_pending_reason }}</div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
