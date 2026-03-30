@extends('driver.layout')

@section('title', 'Finalizar Entrega')

@section('content')
<div class="app-header">
    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('driver.destinations.show', $destination->id) }}" class="text-white">
            <i class="bx bx-arrow-back" style="font-size: 1.5rem;"></i>
        </a>
        <div class="text-center">
            <div class="greeting">Finalizar Entrega</div>
        </div>
        <div style="width: 24px;"></div>
    </div>
</div>

<div class="form-card" style="margin-top: 16px;">
    <p class="mb-3">Anexe uma ou mais fotos para documentar a entrega e adicione uma observação se necessário.</p>

    <form id="finishDeliveryForm" action="{{ route('driver.destinations.finish') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="destination_id" value="{{ $destination->id }}">

        <div class="mb-3">
            <label class="form-label">Fotos da entrega</label>
            <input type="file" name="finish_photos[]" accept="image/*" multiple class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Observação (opcional)</label>
            <textarea name="finish_notes" class="form-control" rows="3" placeholder="Adicione uma observação..."></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Motivos das pendências</label>
            <textarea name="pending_reason" id="pending_reason" class="form-control" rows="3" placeholder="Informe se houver pendências..."></textarea>
        </div>

        <input type="hidden" name="complete_type" id="complete_type" value="full">
        
        <div class="d-flex gap-2">
            <button type="submit" id="btnFull" class="btn-submit" style="flex: 1; background: #28a745;">
                <i class="bx bx-check"></i> Concluir
            </button>
            <button type="submit" id="btnPending" class="btn-submit" style="flex: 1;">
                <i class="bx bx-warning"></i> Com Pendências
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
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
