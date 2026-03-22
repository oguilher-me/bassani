@extends('layouts/contentNavbarLayout')

@section('title', __('Concluir Entrega'))

@section('content')
<div class="row mb-6 gy-6">
  <div class="col-xl-8 col-lg-8 col-md-10 mx-auto">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Concluir Entrega') }}</h5>
        <a href="{{ route('driver.destinations.show', $destination->id) }}" class="btn btn-outline-secondary btn-sm">{{ __('Voltar') }}</a>
      </div>
      <div class="card-body">
        <p class="mb-3">{{ __('Anexe uma ou mais fotos para documentar a entrega e adicione uma observação se necessário.') }}</p>

        <form id="finishDeliveryForm" action="{{ route('driver.destinations.finish') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="destination_id" value="{{ $destination->id }}">

          <div class="mb-3">
            <label class="form-label">{{ __('Fotos da entrega (uma ou mais)') }}</label>
            <input type="file" name="finish_photos[]" accept="image/*" multiple class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">{{ __('Observação (opcional)') }}</label>
            <textarea name="finish_notes" class="form-control" rows="3"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">{{ __('Motivos das pendências (obrigatório se concluir com pendências)') }}</label>
            <textarea name="pending_reason" id="pending_reason" class="form-control" rows="3"></textarea>
          </div>

          <input type="hidden" name="complete_type" id="complete_type" value="full">
          <button type="submit" id="btnFull" class="btn btn-primary">{{ __('Concluir Entrega') }}</button>
          <button type="submit" id="btnPending" class="btn btn-warning ms-2">{{ __('Concluir com pendências') }}</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const typeInput = document.getElementById('complete_type');
    const pendingBtn = document.getElementById('btnPending');
    const fullBtn = document.getElementById('btnFull');
    const pendingReason = document.getElementById('pending_reason');

    pendingBtn.addEventListener('click', function(e) {
      typeInput.value = 'pending';
      if (!pendingReason.value.trim()) {
        e.preventDefault();
        alert('Informe os motivos das pendências para concluir com pendências.');
      }
    });

    fullBtn.addEventListener('click', function() {
      typeInput.value = 'full';
    });
  });
</script>
@endsection

