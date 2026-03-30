@extends('assembler.layout')

@section('title', 'Lançar Despesa - Bassani')

@section('content')
{{-- Header --}}
<div class="app-header">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ url('/assembler/home') }}" class="text-white">
                <i class="bx bx-arrow-back fs-4"></i>
            </a>
            <div>
                <div class="greeting">Nova Despesa</div>
                <div class="user-name small opacity-75">Lançamento rápido</div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('assembly-expenses.store') }}" method="POST" enctype="multipart/form-data" id="expenseForm">
    @csrf
    
    {{-- Schedule Selection --}}
    <div class="page-title">
        <i class="bx bx-wrench"></i> Montagem
    </div>
    
    <div class="form-card">
        <select name="assembly_schedule_id" class="form-select" required id="scheduleSelect">
            <option value="">Selecione a montagem...</option>
            @foreach($schedules ?? [] as $schedule)
                <option value="{{ $schedule->id }}" 
                        data-customer="{{ $schedule->sale->customer->full_name ?? $schedule->sale->customer->company_name ?? 'N/A' }}">
                    #{{ $schedule->id }} - {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d/m') }} - {{ $schedule->sale->customer->full_name ?? $schedule->sale->customer->company_name ?? 'Cliente' }}
                </option>
            @endforeach
        </select>
        <div id="selectedSchedule" class="mt-2 small text-muted" style="display: none;">
            <i class="bx bx-check-circle text-success"></i> <span id="scheduleInfo"></span>
        </div>
    </div>

    {{-- Category Selection --}}
    <div class="page-title">
        <i class="bx bx-category"></i> Categoria
    </div>
    
    <div class="form-card">
        <input type="hidden" name="category" id="selectedCategory" value="{{ old('category', '') }}" required>
        <div class="category-grid" id="categoryGrid">
            <button type="button" class="category-btn {{ old('category') == 'Alimentação' ? 'selected' : '' }}" data-category="Alimentação">
                <i class="bx bx-restaurant" style="color: #FF6B6B;"></i>
                <span>Alimentação</span>
            </button>
            <button type="button" class="category-btn {{ old('category') == 'Combustível' ? 'selected' : '' }}" data-category="Combustível">
                <i class="bx bxs-gas-pump" style="color: #4ECDC4;"></i>
                <span>Combustível</span>
            </button>
            <button type="button" class="category-btn {{ old('category') == 'Pedágio' ? 'selected' : '' }}" data-category="Pedágio">
                <i class="bx bx-transfer" style="color: #45B7D1;"></i>
                <span>Pedágio</span>
            </button>
            <button type="button" class="category-btn {{ old('category') == 'Hospedagem' ? 'selected' : '' }}" data-category="Hospedagem">
                <i class="bx bx-hotel" style="color: #96CEB4;"></i>
                <span>Hospedagem</span>
            </button>
            <button type="button" class="category-btn {{ old('category') == 'Estacionamento' ? 'selected' : '' }}" data-category="Estacionamento">
                <i class="bx bx-car" style="color: #FFEAA7;"></i>
                <span>Estacionamento</span>
            </button>
            <button type="button" class="category-btn {{ old('category') == 'Material Extra' ? 'selected' : '' }}" data-category="Material Extra">
                <i class="bx bx-package" style="color: #DDA0DD;"></i>
                <span>Material Extra</span>
            </button>
            <button type="button" class="category-btn {{ old('category') == 'Outros' ? 'selected' : '' }}" data-category="Outros" style="grid-column: span 3;">
                <i class="bx bx-receipt" style="color: #A0A0A0;"></i>
                <span>Outros</span>
            </button>
        </div>
        @error('category')
            <div class="text-danger small mt-2"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
        @enderror
    </div>

    {{-- Amount --}}
    <div class="page-title">
        <i class="bx bx-dollar"></i> Valor
    </div>
    
    <div class="form-card">
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-light border-end-0" style="font-size: 1.2rem; font-weight: 600;">R$</span>
            <input type="number" step="0.01" min="0.01" name="amount" class="form-control border-start-0 ps-0" 
                   value="{{ old('amount') }}" placeholder="0,00" required
                   style="font-size: 1.5rem; font-weight: 600; color: #DE0802;">
        </div>
        @error('amount')
            <div class="text-danger small mt-2"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
        @enderror
    </div>

    {{-- Date --}}
    <div class="page-title">
        <i class="bx bx-calendar"></i> Data
    </div>
    
    <div class="form-card">
        <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
    </div>

    {{-- Description --}}
    <div class="page-title">
        <i class="bx bx-note"></i> Descrição <span class="text-muted small fw-normal">(opcional)</span>
    </div>
    
    <div class="form-card">
        <textarea name="description" class="form-control" rows="2" maxlength="1000" 
                  placeholder="Ex: Almoço em restaurante local...">{{ old('description') }}</textarea>
    </div>

    {{-- Receipt --}}
    <div class="page-title">
        <i class="bx bx-camera"></i> Comprovante <span class="text-muted small fw-normal">(opcional)</span>
    </div>
    
    <div class="form-card">
        <input type="file" id="receiptInput" name="receipt" accept="image/*,.pdf" class="d-none">
        
        <div class="d-grid gap-2">
            <button type="button" id="btnCamera" class="btn btn-outline-primary" style="border-radius: 12px; padding: 12px;">
                <i class="bx bx-camera me-2"></i>Tirar Foto
            </button>
            <button type="button" id="btnFile" class="btn btn-outline-secondary" style="border-radius: 12px; padding: 12px;">
                <i class="bx bx-folder-open me-2"></i>Escolher Arquivo
            </button>
        </div>
        
        <img id="receiptPreview" src="" alt="Preview" class="mt-3 rounded" style="width: 100%; max-height: 200px; object-fit: cover; display: none;">
        <div id="receiptFilename" class="mt-2 small text-muted"></div>
    </div>

    {{-- Submit --}}
    <div class="px-3 pb-4">
        <button type="submit" class="btn-submit" id="submitBtn">
            <i class="bx bx-send me-2"></i>Enviar Despesa
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category selection
    const categoryBtns = document.querySelectorAll('.category-btn');
    const categoryInput = document.getElementById('selectedCategory');
    
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            categoryBtns.forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
            categoryInput.value = this.dataset.category;
            
            // Haptic feedback (if available)
            if (navigator.vibrate) navigator.vibrate(10);
        });
    });

    // Schedule selection feedback
    const scheduleSelect = document.getElementById('scheduleSelect');
    const selectedSchedule = document.getElementById('selectedSchedule');
    const scheduleInfo = document.getElementById('scheduleInfo');
    
    scheduleSelect.addEventListener('change', function() {
        if (this.value) {
            const option = this.options[this.selectedIndex];
            selectedSchedule.style.display = 'block';
            scheduleInfo.textContent = option.dataset.customer;
        } else {
            selectedSchedule.style.display = 'none';
        }
    });

    // File input handling
    const receiptInput = document.getElementById('receiptInput');
    const btnCamera = document.getElementById('btnCamera');
    const btnFile = document.getElementById('btnFile');
    const receiptPreview = document.getElementById('receiptPreview');
    const receiptFilename = document.getElementById('receiptFilename');

    btnCamera.addEventListener('click', () => {
        receiptInput.setAttribute('capture', 'environment');
        receiptInput.accept = 'image/*';
        receiptInput.click();
    });

    btnFile.addEventListener('click', () => {
        receiptInput.removeAttribute('capture');
        receiptInput.accept = 'image/*,.pdf';
        receiptInput.click();
    });

    receiptInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        
        receiptFilename.textContent = '📎 ' + file.name;
        
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => {
                receiptPreview.src = e.target.result;
                receiptPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            receiptPreview.style.display = 'none';
            receiptFilename.innerHTML = '📄 ' + file.name;
        }
        
        if (navigator.vibrate) navigator.vibrate(10);
    });

    // Form validation visual feedback
    document.getElementById('expenseForm').addEventListener('submit', function(e) {
        const category = categoryInput.value;
        if (!category) {
            e.preventDefault();
            showToast('Selecione uma categoria', 'warning');
            return;
        }
        
        const btn = document.getElementById('submitBtn');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
        btn.disabled = true;
    });
});
</script>
@endsection
