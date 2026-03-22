@extends('layouts/contentNavbarLayout')

@section('title', __('Iniciar Entrega'))

@section('content')
<div class="row mb-6 gy-6">
  <div class="col-xl-8 col-lg-8 col-md-10 mx-auto">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Iniciar Entrega') }}</h5>
        <a href="{{ route('driver.destinations.show', $destination->id) }}" class="btn btn-outline-secondary btn-sm">{{ __('Voltar') }}</a>
      </div>
      <div class="card-body">
        <p class="mb-3">{{ __('Para iniciar a entrega, tire uma foto do painel do Caminhão. As coordenadas geográficas serão capturadas para confirmar que você está na fábrica.') }}</p>

        <form id="startDeliveryForm" action="{{ route('driver.destinations.start') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="destination_id" value="{{ $destination->id }}">
          <input type="hidden" id="start_latitude" name="start_latitude">
          <input type="hidden" id="start_longitude" name="start_longitude">
          <input type="hidden" id="start_accuracy" name="start_accuracy">

          <div class="mb-3">
            <label class="form-label">{{ __('Foto do local da entrega') }}</label>
            <input type="file" name="start_photo" accept="image/*" capture="environment" class="form-control" required>
          </div>

          <div class="mb-3" id="locationStatus"></div>

          <button type="submit" class="btn btn-primary">{{ __('Iniciar Entrega') }}</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  function setStatus(message, type) {
    var el = document.getElementById('locationStatus');
    el.textContent = message;
    el.className = type ? ('alert alert-' + type) : '';
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
      setStatus('Não foi possível obter a localização. Autorize o acesso à localização e tente novamente.', 'danger');
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

