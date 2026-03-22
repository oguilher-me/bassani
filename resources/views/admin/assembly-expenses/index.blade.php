@extends('layouts/contentNavbarLayout')

@section('title', 'Despesas de Campo — Conciliação Financeira')

@section('content')

<style>
    .expense-receipt-thumb {
        width: 72px; height: 72px; object-fit: cover;
        border-radius: 8px; cursor: pointer;
        border: 2px solid var(--bs-border-color);
        transition: transform .15s;
    }
    .expense-receipt-thumb:hover { transform: scale(1.08); }
    .status-dot {
        width: 9px; height: 9px; border-radius: 50%; display: inline-block; margin-right: 5px;
    }
</style>

{{-- ─── Header ──────────────────────────────────────────────────── --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-0"><i class="bx bx-receipt me-2 text-primary"></i>Despesas de Campo</h5>
                    <small class="text-muted">Conciliação Financeira — Montadores</small>
                </div>
                {{-- Summary badges --}}
                @php
                    $total    = $expenses->total();
                    $pending  = $expenses->getCollection()->where('status','pendente')->count();
                    $approved = $expenses->getCollection()->where('status','aprovado')->count();
                    $rejected = $expenses->getCollection()->where('status','rejeitado')->count();
                    $totalAmt = $expenses->getCollection()->where('status','aprovado')->sum('amount');
                @endphp
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge bg-label-warning fs-6">{{ $pending }} Pendente(s)</span>
                    <span class="badge bg-label-success fs-6">{{ $approved }} Aprovado(s)</span>
                    <span class="badge bg-label-danger fs-6">{{ $rejected }} Rejeitado(s)</span>
                    <span class="badge bg-label-primary fs-6">R$ {{ number_format($totalAmt, 2, ',', '.') }} aprovados</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ─── Flash Messages ─────────────────────────────────────────── --}}
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

{{-- ─── Filter bar ──────────────────────────────────────────────── --}}
<div class="card mb-4">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('assembly-expenses.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1 small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    @foreach(['pendente'=>'Pendente','aprovado'=>'Aprovado','rejeitado'=>'Rejeitado'] as $val => $lbl)
                        <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1 small">Montagem #</label>
                <input type="number" name="assembly_schedule_id" class="form-control form-control-sm"
                       value="{{ request('assembly_schedule_id') }}" placeholder="ID da montagem">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bx bx-filter-alt me-1"></i> Filtrar
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('assembly-expenses.index') }}" class="btn btn-outline-secondary btn-sm w-100">Limpar</a>
            </div>
        </form>
    </div>
</div>

{{-- ─── Expense Cards ───────────────────────────────────────────── --}}
@forelse($expenses as $expense)
<div class="card mb-3 {{ $expense->status === 'pendente' ? 'border-warning' : '' }}" id="expense-{{ $expense->id }}">
    <div class="card-body">
        <div class="row align-items-center">

            {{-- Receipt thumbnail --}}
            <div class="col-auto">
                @if($expense->receipt_path)
                    @php $ext = pathinfo($expense->receipt_path, PATHINFO_EXTENSION); @endphp
                    @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                        <img src="{{ asset('storage/' . $expense->receipt_path) }}"
                             class="expense-receipt-thumb"
                             data-bs-toggle="modal"
                             data-bs-target="#receiptModal"
                             data-src="{{ asset('storage/' . $expense->receipt_path) }}"
                             data-caption="{{ $expense->description }}"
                             title="Clique para ampliar">
                    @else
                        <a href="{{ asset('storage/' . $expense->receipt_path) }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-danger px-2 py-3">
                            <i class="bx bxs-file-pdf fs-3 d-block"></i>
                            <span style="font-size:.7rem">Comprovante</span>
                        </a>
                    @endif
                @else
                    <div class="expense-receipt-thumb d-flex align-items-center justify-content-center bg-light text-muted" style="cursor:default">
                        <i class="bx bx-image-alt fs-4"></i>
                    </div>
                @endif
            </div>

            {{-- Main info --}}
            <div class="col">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bx {{ $expense->category_icon }} fs-5 text-primary"></i>
                    <strong>{{ $expense->category }}</strong>
                    <span class="badge {{ $expense->status_badge }}">{{ $expense->status_label }}</span>
                    <span class="text-muted small">Montagem #{{ $expense->assembly_schedule_id }}</span>
                </div>
                <div class="row g-2 small text-muted">
                    <div class="col-auto"><i class="bx bx-user me-1"></i>{{ $expense->assembler->name ?? '—' }}</div>
                    <div class="col-auto"><i class="bx bx-calendar me-1"></i>{{ $expense->date->format('d/m/Y') }}</div>
                    @if($expense->description)
                        <div class="col-12 mt-1">{{ $expense->description }}</div>
                    @endif
                    @if($expense->rejection_reason)
                        <div class="col-12 mt-1 text-danger">
                            <i class="bx bx-x-circle me-1"></i><strong>Motivo da rejeição:</strong> {{ $expense->rejection_reason }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Amount + actions --}}
            <div class="col-auto text-end">
                <div class="fs-4 fw-bold text-primary mb-2">
                    R$ {{ number_format($expense->amount, 2, ',', '.') }}
                </div>

                @if($expense->status === 'pendente')
                    <div class="d-flex gap-2 justify-content-end">
                        {{-- Approve --}}
                        <form action="{{ route('assembly-expenses.approve', $expense->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" title="Aprovar">
                                <i class="bx bx-check me-1"></i>Aprovar
                            </button>
                        </form>
                        {{-- Reject --}}
                        <button type="button" class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#rejectModal"
                                data-expense-id="{{ $expense->id }}"
                                title="Rejeitar">
                            <i class="bx bx-x me-1"></i>Rejeitar
                        </button>
                    </div>
                @else
                    <div class="d-flex gap-2 justify-content-end">
                        {{-- Delete --}}
                        <form action="{{ route('assembly-expenses.destroy', $expense->id) }}" method="POST"
                              onsubmit="return confirm('Remover esta despesa?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-icon btn-sm btn-outline-danger" title="Excluir">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@empty
    <div class="card">
        <div class="card-body text-center py-5 text-muted">
            <i class="bx bx-receipt fs-1 d-block mb-2 opacity-50"></i>
            Nenhuma despesa encontrada para os filtros aplicados.
        </div>
    </div>
@endforelse

{{-- Pagination --}}
<div class="mt-3">{{ $expenses->withQueryString()->links() }}</div>

{{-- ─── Receipt Lightbox Modal ─────────────────────────────────── --}}
<div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="receiptCaption">Comprovante</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-2">
                <img id="receiptImg" src="" alt="" class="img-fluid rounded" style="max-height:80vh;object-fit:contain">
            </div>
            <div class="modal-footer justify-content-between">
                <a id="receiptLink" href="#" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="bx bx-external-link me-1"></i> Abrir em nova aba
                </a>
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

{{-- ─── Reject Modal ────────────────────────────────────────────── --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="rejectForm">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title text-danger"><i class="bx bx-x-circle me-2"></i>Rejeitar Despesa</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="rejection_reason" class="form-label">Motivo da Rejeição <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="rejection_reason" name="rejection_reason"
                              rows="3" maxlength="500" required
                              placeholder="Ex: Comprovante ilegível, valor divergente..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger"><i class="bx bx-x me-1"></i>Confirmar Rejeição</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function(){
    // Receipt lightbox
    const rm = document.getElementById('receiptModal');
    if(rm){
        rm.addEventListener('show.bs.modal', e => {
            const btn = e.relatedTarget;
            document.getElementById('receiptImg').src = btn.dataset.src;
            document.getElementById('receiptLink').href = btn.dataset.src;
            document.getElementById('receiptCaption').textContent = btn.dataset.caption || 'Comprovante';
        });
        rm.addEventListener('hidden.bs.modal', () => { document.getElementById('receiptImg').src = ''; });
    }

    // Reject modal — set form action dynamically
    const rejectModal = document.getElementById('rejectModal');
    if(rejectModal){
        rejectModal.addEventListener('show.bs.modal', e => {
            const btn = e.relatedTarget;
            const id  = btn.dataset.expenseId;
            document.getElementById('rejectForm').action = `/admin/assembly-expenses/${id}/reject`;
        });
    }
})();
</script>
@endsection
