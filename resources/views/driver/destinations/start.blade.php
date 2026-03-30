@extends('driver.layout')

@section('title', 'Iniciar Entrega')

@section('content')
<div class="app-header">
    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('driver.destinations.show', $destination->id) }}" class="text-white">
            <i class="bx bx-arrow-back" style="font-size: 1.5rem;"></i>
        </a>
        <div class="text-center">
            <div class="greeting">Iniciar Entrega</div>
        </div>
        <div style="width: 24px;"></div>
    </div>
</div>

<div class="form-card" style="margin-top: 16px;">
    <p class="mb-3">Para iniciar a entrega, tire uma foto do painel do veículo. As coordenadas geográficas serão capturadas para confirmar que você está na fábrica.</p>

    <form id="startDeliveryForm" action="{{ route('driver.destinations.start') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="destination_id" value="{{ $destination->id }}">
        <input type="hidden" id="start_latitude" name="start_latitude">
        <input type="hidden" id="start_longitude" name="start_longitude">
        <input type="hidden" id="start_accuracy" name="start_accuracy">

        <div class="mb-3">
            <label class="form-label">Foto do local</label>
            <input type="file" name="start_photo" accept="image/*" capture="environment" class="form-control" required>
        </div>

        <div class="mb-3" id="locationStatus" style="padding: 10px; border-radius: 8px; text-align: center;"></div>

        <button type="submit" class="btn-submit">
            <i class="bx bx-play"></i> Iniciar Entrega
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
  function setStatus(message, type) {
    var el = document.getElementById('locationStatus');
    el.textContent = message;
    if (type === 'success') {
        el.style.background = '#d4edda';
        el.style.color = '#155724';
    } else if (type === 'warning') {
        el.style.background = '#fff3cd';
        el.style.color = '#856404';
    } else if (type === 'danger') {
        el.style.background = '#f8d7da';
        el.style.color = '#721c24';
    } else {
        el.style.background = '#cce5ff';
        el.style.color = '#004085';
    }
  }

  function requestLocation() {
    if (!navigator.geolocation) {
      setStatus('Geolocalização não suportada neste dispositivo.', 'warning');
      return;
    }
    setStatus('Obtendo localização...', 'info');
    navigator.geolocation.getCurrentPosition(function(pos) {
      document.getElementById('start_latitude').value = pos.coords.latitude;
      document.getElementById('start_longitude').value = pos.coords.longitude;
      document.getElementById('start_accuracy').value = pos.coords.accuracy;
      setStatus('Localização capturada com sucesso.', 'success');
    }, function(err) {
      setStatus('Não foi possível obter a localização. Autorize o acesso e tente novamente.', 'danger');
    }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
  }

  document.addEventListener('DOMContentLoaded', function() {
    requestLocation();
    document.getElementById('startDeliveryForm').addEventListener('submit', function(e) {
      var lat = document.getElementById('start_latitude').value;
      var lng = document.getElementById('start_longitude').value;
      if (!lat || !lng) {
        e.preventDefault();
        setStatus('Localização obrigatória para iniciar a entrega.', 'danger');
      }
    });
  });
</script>
@endsection
