@extends('driver.layout')
@section('title', 'Detalhes da Entrega')

@section('content')
<div class="app-header">
    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ url('/driver/my-schedule') }}" class="text-white">
            <i class="bx bx-arrow-back" style="font-size: 1.5rem;"></i>
        </a>
        <div class="text-center">
            <div class="greeting">Entrega #{{ $destination->id }}</div>
        </div>
        <div style="width: 24px;"></div>
    </div>
</div>

<div class="delivery-card" style="margin-top: 16px;">
    <div class="delivery-client">{{ $destination->customer->company_name ?? $destination->customer->full_name ?? 'Cliente' }}</div>
    <div class="delivery-address">{{ $destination->address }}, {{ $destination->neighborhood }}</div>
    <div class="delivery-address">{{ $destination->city }} - {{ $destination->state }}</div>
    
    <hr>
    
    <div class="mb-2">
        <strong><i class="bx bx-user"></i> Contato:</strong> {{ $destination->contact_name }} • {{ $destination->contact_phone }}
    </div>
    <div class="mb-2">
        <strong><i class="bx bx-time"></i> Janela:</strong> {{ optional($destination->window_start)->format('d/m H:i') }} - {{ optional($destination->window_end)->format('H:i') }}
    </div>
    <div class="mb-2">
        <strong><i class="bx bx-package"></i> Carga:</strong> {{ $destination->plannedShipment->shipment_number ?? '-' }}
    </div>
    <div class="mb-2">
        <strong><i class="bx bx-car"></i> Veículo:</strong> {{ $destination->plannedShipment->vehicle->modelo ?? '-' }} {{ $destination->plannedShipment->vehicle->placa ?? '' }}
    </div>
</div>

<div style="padding: 0 16px;">
    @if(is_null($destination->confirmation_status))
    <a href="{{ route('driver.destinations.start.form', $destination->id) }}" class="btn-submit mb-3">
        <i class="bx bx-play"></i> Iniciar Entrega
    </a>
    @endif
    
    @if($destination->confirmation_status == 'started')
    <a href="{{ route('driver.destinations.finish.form', $destination->id) }}" class="btn-submit mb-3" style="background: #28a745;">
        <i class="bx bx-check"></i> Finalizar Entrega
    </a>
    
    @if($destination->contact_phone)
        @php
            $driverName = optional($destination->plannedShipment->driver)->full_name ?? optional($destination->plannedShipment->driver)->name;
            $saleObj = optional($destination->items->first()->sale);
            $erpCode = $saleObj->erp_code ?? '';
            $msg = "Olá " . ($destination->contact_name ?? '') . "! Meu nome é " . ($driverName ?? '') . ", sou motorista da Bassani Móveis e estamos indo até você para entregar itens do pedido #" . ($erpCode ?? '') . ". Em breve chegaremos.";
            $phone = '+55' . preg_replace('/\D+/', '', $destination->contact_phone);
            $waUrl = 'https://wa.me/' . $phone . '?text=' . urlencode($msg);
        @endphp
        <a href="{{ $waUrl }}" target="_blank" class="btn-action mb-3" style="background: #25D366; color: white; display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; border-radius: 12px; text-decoration: none;">
            <i class="bx bxl-whatsapp"></i> Enviar WhatsApp
        </a>
    @endif
    @endif
</div>

<div class="section-title">Itens</div>

@foreach($destination->items as $it)
<div class="delivery-card">
    <div class="d-flex justify-content-between">
        <div>
            <div class="delivery-client">{{ optional($it->product)->name ?? 'Produto' }}</div>
            <div class="delivery-address">{{ $it->description }}</div>
        </div>
        <div class="text-end">
            <div style="font-weight: 600;">{{ $it->quantity }}x</div>
        </div>
    </div>
</div>
@endforeach
@endsection
