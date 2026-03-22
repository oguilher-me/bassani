{{-- =========================================================
     Despesas de Campo — Card para Montador
     var: $assemblySchedule (AssemblySchedule)
          $assembler (Assembler — logged in)
     ========================================================= --}}
<style>
    .expense-cat-btn {
        display: flex; flex-direction: column; align-items: center;
        gap: 4px; padding: 14px 8px; border-radius: 12px;
        border: 2px solid var(--bs-border-color);
        background: var(--bs-body-bg);
        cursor: pointer; transition: all .18s; min-width: 80px;
        font-size: .78rem; font-weight: 600; color: var(--bs-body-color);
    }
    .expense-cat-btn i { font-size: 1.6rem; }
    .expense-cat-btn:hover,
    .expense-cat-btn.selected {
        border-color: var(--bs-primary);
        background: rgba(var(--bs-primary-rgb), .08);
        color: var(--bs-primary);
    }
    .expense-cat-btn.selected i { color: var(--bs-primary); }
    #receipt-preview { max-height: 160px; border-radius: 10px; display: none; }
    .expense-status-dot { width: 9px; height: 9px; border-radius: 50%; display: inline-block; }
</style>

<div class="card mb-4" id="despesas-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bx bx-receipt me-2 text-primary"></i>Despesas de Campo</h6>
        <span class="badge bg-label-primary">{{ $assemblySchedule->expenses()->where('assembler_id', $assembler->id)->count() }} lançamento(s)</span>
    </div>
    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="bx bx-error-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ── Upload Form ─────────────────────────────────── --}}
        <form action="{{ route('assembly-expenses.store') }}" method="POST"
              enctype="multipart/form-data" id="expense-form">
            @csrf
            <input type="hidden" name="assembly_schedule_id" value="{{ $assemblySchedule->id }}">

            {{-- Category buttons (mobile-first big touch targets) --}}
            <p class="fw-semibold mb-2 small text-uppercase text-muted">Categoria</p>
            <div class="d-flex flex-wrap gap-2 mb-3" id="cat-buttons">
                @foreach([
                    ['Alimentação','bx-restaurant'],
                    ['Hospedagem','bx-hotel'],
                    ['Combustível','bxs-gas-pump'],
                    ['Pedágio','bx-transfer'],
                    ['Estacionamento','bx-car'],
                    ['Material Extra','bx-package'],
                    ['Outros','bx-receipt'],
                ] as [$cat, $icon])
                    <button type="button"
                            class="expense-cat-btn {{ old('category') == $cat ? 'selected' : '' }}"
                            data-category="{{ $cat }}">
                        <i class="bx {{ $icon }}"></i>{{ $cat }}
                    </button>
                @endforeach
            </div>
            <input type="hidden" name="category" id="selected-category" value="{{ old('category') }}" required>
            @error('category')
                <div class="text-danger small mb-2">{{ $message }}</div>
            @enderror

            <div class="row g-3 mb-3">
                {{-- Amount --}}
                <div class="col-6">
                    <label class="form-label">Valor (R$) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="number" step="0.01" min="0.01" name="amount"
                               class="form-control form-control-lg"
                               value="{{ old('amount') }}"
                               placeholder="0,00" required>
                    </div>
                    @error('amount')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                {{-- Date --}}
                <div class="col-6">
                    <label class="form-label">Data <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control form-control-lg"
                           value="{{ old('date', date('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}" required>
                    @error('date')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                {{-- Description --}}
                <div class="col-12">
                    <label class="form-label">Descrição</label>
                    <textarea name="description" class="form-control" rows="2"
                              placeholder="Ex: Almoço em restaurante, km rodados..." maxlength="1000">{{ old('description') }}</textarea>
                    @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Camera / file input — single input avoids duplicate-field bug --}}
            <div class="mb-3">
                <label class="form-label">Comprovante / Cupom Fiscal</label>

                {{-- Single hidden input — label buttons control it --}}
                <input type="file" id="receipt-input" name="receipt"
                       accept="image/*,.pdf" class="d-none">

                <div class="d-grid gap-2">
                    {{-- Camera: adds capture="environment" before opening --}}
                    <button type="button" id="btn-camera"
                            class="btn btn-outline-primary btn-lg"
                            style="border-radius:12px">
                        <i class="bx bx-camera me-2"></i>Fotografar Cupom
                    </button>
                    {{-- File: removes capture so gallery/file picker opens --}}
                    <button type="button" id="btn-file"
                            class="btn btn-outline-secondary"
                            style="border-radius:10px">
                        <i class="bx bx-folder-open me-2"></i>Escolher Arquivo (PDF / Imagem)
                    </button>
                </div>

                <img id="receipt-preview" src="" alt="Pré-visualização" class="mt-2 img-fluid"
                     style="max-height:160px;border-radius:10px;display:none">
                <div id="receipt-filename" class="mt-1 small text-muted"></div>
                @error('receipt')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg" style="border-radius:12px">
                    <i class="bx bx-send me-2"></i>Lançar Despesa
                </button>
            </div>
        </form>


        {{-- ── My Expenses for this schedule ──────────────── --}}
        @php
            $myExpenses = $assemblySchedule->expenses()
                ->where('assembler_id', $assembler->id)
                ->latest()
                ->get();
        @endphp
        @if($myExpenses->count() > 0)
            <hr class="my-4">
            <h6 class="mb-3"><i class="bx bx-list-ul me-1 text-muted"></i>Seus Lançamentos</h6>
            @foreach($myExpenses as $exp)
                <div class="d-flex align-items-start gap-3 mb-3 p-2 rounded" style="background:var(--bs-tertiary-bg)">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bx {{ $exp->category_icon }} text-primary"></i>
                            <strong class="small">{{ $exp->category }}</strong>
                            <span class="badge {{ $exp->status_badge }} ms-auto">{{ $exp->status_label }}</span>
                        </div>
                        <div class="small text-muted">{{ $exp->date->format('d/m/Y') }} · {{ $exp->description ?: '—' }}</div>
                        @if($exp->rejection_reason)
                            <div class="small text-danger mt-1"><i class="bx bx-x-circle me-1"></i>{{ $exp->rejection_reason }}</div>
                        @endif
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">R$ {{ number_format($exp->amount, 2, ',', '.') }}</div>
                        @if($exp->receipt_path)
                            <a href="{{ asset('storage/' . $exp->receipt_path) }}" target="_blank"
                               class="btn btn-icon btn-sm btn-outline-secondary mt-1" title="Ver comprovante">
                                <i class="bx bx-show"></i>
                            </a>
                        @endif
                        @if($exp->status === 'pendente')
                            <form action="{{ route('assembly-expenses.destroy', $exp->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Remover este lançamento?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-icon btn-sm btn-outline-danger mt-1" title="Excluir">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="text-end fw-bold small text-primary mt-2">
                Total aprovado: R$ {{ number_format($myExpenses->where('status','aprovado')->sum('amount'), 2, ',', '.') }}
            </div>
        @endif

    </div>{{-- /card-body --}}
</div>

<script>
(function(){
    // ── Category button toggle ────
    const catBtns = document.querySelectorAll('.expense-cat-btn');
    const catInput = document.getElementById('selected-category');
    catBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            catBtns.forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            catInput.value = btn.dataset.category;
        });
    });

    // ── Single receipt input — toggle capture attribute ─────────
    const fileInput  = document.getElementById('receipt-input');
    const btnCamera  = document.getElementById('btn-camera');
    const btnFile    = document.getElementById('btn-file');
    const preview    = document.getElementById('receipt-preview');
    const filenameEl = document.getElementById('receipt-filename');

    btnCamera.addEventListener('click', () => {
        fileInput.setAttribute('capture', 'environment');
        fileInput.accept = 'image/*';
        fileInput.click();
    });

    btnFile.addEventListener('click', () => {
        fileInput.removeAttribute('capture');
        fileInput.accept = 'image/*,.pdf';
        fileInput.click();
    });

    fileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        filenameEl.textContent = '📎 ' + file.name;
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
})();
</script>

