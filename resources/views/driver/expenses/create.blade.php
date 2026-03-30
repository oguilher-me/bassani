@extends('driver.layout')

@section('title', 'Lançar Despesa - Bassani')

@section('content')
<div class="app-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ url('/driver/expenses') }}" class="text-white">
            <i class="bx bx-arrow-back fs-4"></i>
        </a>
        <div class="greeting">Lançar Despesa</div>
    </div>
</div>

<form action="{{ route('driver.expenses.store') }}" method="POST" enctype="multipart/form-data" id="expenseForm">
    @csrf
    
    <div class="form-card">
        <div class="mb-3">
            <label class="form-label">Entrega *</label>
            <select name="shipment_id" class="form-select" required>
                <option value="">Selecione a entrega</option>
                @foreach($shipments as $shipment)
                    <option value="{{ $shipment->shipment_id }}">
                        #{{ $shipment->shipment_id }} - {{ \Carbon\Carbon::parse($shipment->planned_delivery_date)->format('d/m/Y') }}
                        @if($shipment->destination_address)
                            ({{ Str::limit($shipment->destination_address, 30) }})
                        @endif
                    </option>
                @endforeach
            </select>
            @error('shipment_id')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label class="form-label">Categoria *</label>
            <select name="category" class="form-select" required>
                <option value="">Selecione a categoria</option>
                <option value="Alimentação">Alimentação</option>
                <option value="Hospedagem">Hospedagem</option>
                <option value="Combustível">Combustível</option>
                <option value="Pedágio">Pedágio</option>
                <option value="Estacionamento">Estacionamento</option>
                <option value="Material Extra">Material Extra</option>
                <option value="Outros">Outros</option>
            </select>
            @error('category')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label class="form-label">Valor (R$) *</label>
            <input type="number" name="amount" class="form-control" step="0.01" min="0.01" placeholder="0,00" required>
            @error('amount')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label class="form-label">Data *</label>
            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
            @error('date')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Observações sobre a despesa..."></textarea>
            @error('description')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label class="form-label">Comprovante (foto)</label>
            <input type="file" name="receipt" class="form-control" accept="image/*" capture="environment">
            <small class="text-muted">Envie uma foto do comprovante (máx. 5MB)</small>
            @error('receipt')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="px-3">
        <button type="submit" class="btn-submit">
            <i class="bx bx-save me-2"></i>Salvar Despesa
        </button>
    </div>
</form>
@endsection
