@extends('driver.layout')

@section('title', 'Check-up do Veículo')

@section('content')
<div class="app-header">
    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ url('/driver/my-schedule') }}" class="text-white">
            <i class="bx bx-arrow-back" style="font-size: 1.5rem;"></i>
        </a>
        <div class="text-center">
            <div class="greeting">Check-up</div>
        </div>
        <div style="width: 24px;"></div>
    </div>
</div>

<form action="{{ route('driver.checkups.store') }}" method="POST" id="checkup-form">
    @csrf

    <div class="form-card" style="margin-top: 16px;">
        <label class="form-label">Veículo <span class="text-danger">*</span></label>
        <select class="form-select" id="vehicle_id" name="vehicle_id" required>
            <option value="">Selecione o veículo</option>
            @foreach($vehicles as $vehicle)
                <option value="{{ $vehicle->id }}">{{ $vehicle->placa }} - {{ $vehicle->modelo }}</option>
            @endforeach
        </select>
    </div>

    <div class="section-title">Itens do Checklist</div>

    @forelse($checklistItems as $index => $item)
    <div class="delivery-card">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <div class="delivery-client">{{ $item->description }}</div>
                @if($item->is_restrictive)
                <span class="delivery-status status-pending" style="background: #f8d7da; color: #721c24; margin-top: 4px; display: inline-block;">Restritivo</span>
                @endif
            </div>
        </div>
        
        <input type="hidden" name="responses[{{ $index }}][checklist_item_id]" value="{{ $item->id }}">
        
        <div class="d-flex gap-3 mt-2">
            <div class="form-check">
                <input class="form-check-input response-radio" 
                       type="radio" 
                       name="responses[{{ $index }}][is_ok]" 
                       id="response_{{ $index }}_ok" 
                       value="1"
                       data-index="{{ $index }}"
                       required>
                <label class="form-check-label text-success" for="response_{{ $index }}_ok">
                    OK
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input response-radio" 
                       type="radio" 
                       name="responses[{{ $index }}][is_ok]" 
                       id="response_{{ $index }}_nok" 
                       value="0"
                       data-index="{{ $index }}">
                <label class="form-check-label text-danger" for="response_{{ $index }}_nok">
                    Não OK
                </label>
            </div>
        </div>
        
        <div class="observation-field mt-2" id="observation_{{ $index }}" style="display: none;">
            <input type="text" 
                   class="form-control form-control-sm" 
                   name="responses[{{ $index }}][observation]"
                   id="observation_{{ $index }}_text"
                   placeholder="Descreva o problema...">
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="bx bx-error-circle"></i>
        <p>Nenhum item de checklist ativo</p>
    </div>
    @endforelse

    <div class="form-card">
        <label class="form-label">Observações Gerais</label>
        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Observações adicionais..."></textarea>
    </div>

    @if($checklistItems->count() > 0)
    <div style="padding: 0 16px 16px;">
        <button type="submit" class="btn-submit">
            <i class="bx bx-check"></i> Finalizar Check-up
        </button>
    </div>
    @endif
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.response-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                const index = this.dataset.index;
                const observationField = document.getElementById(`observation_${index}`);
                
                if (this.value === '0') {
                    observationField.style.display = 'block';
                    observationField.querySelector('input').setAttribute('required', 'required');
                } else {
                    observationField.style.display = 'none';
                    observationField.querySelector('input').removeAttribute('required');
                }
            });
        });

        document.getElementById('checkup-form').addEventListener('submit', function(e) {
            const radios = document.querySelectorAll('.response-radio:checked');
            const totalItems = {{ $checklistItems->count() }};
            
            if (radios.length < totalItems) {
                e.preventDefault();
                alert('Por favor, responda todos os itens do checklist.');
                return false;
            }
        });
    });
</script>
@endsection
