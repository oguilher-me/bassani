@extends('layouts/contentNavbarLayout')

@section('title', __('Despesas de Montagem'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Despesas de Campo') }}</h4>
        <p class="text-muted mb-0">{{ __('Conciliação Financeira — Montadores') }}</p>
    </div>
</div>

{{-- Statistics Cards --}}
@php
    $total = $expenses->total();
    $pending = $expenses->getCollection()->where('status','pendente')->count();
    $approved = $expenses->getCollection()->where('status','aprovado')->count();
    $rejected = $expenses->getCollection()->where('status','rejeitado')->count();
    $totalAmt = $expenses->getCollection()->where('status','aprovado')->sum('amount');
@endphp

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 48px; height: 48px;">
                    <i class="bx bx-time-five fs-4"></i>
                </div>
                <h4 class="mb-0 fw-bold">{{ $pending }}</h4>
                <small class="text-muted">{{ __('Pendente(s)') }}</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="avatar rounded-circle bg-label-success d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 48px; height: 48px;">
                    <i class="bx bx-check-circle fs-4"></i>
                </div>
                <h4 class="mb-0 fw-bold">{{ $approved }}</h4>
                <small class="text-muted">{{ __('Aprovado(s)') }}</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="avatar rounded-circle bg-label-danger d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 48px; height: 48px;">
                    <i class="bx bx-x-circle fs-4"></i>
                </div>
                <h4 class="mb-0 fw-bold">{{ $rejected }}</h4>
                <small class="text-muted">{{ __('Rejeitado(s)') }}</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 48px; height: 48px;">
                    <i class="bx bx-dollar fs-4"></i>
                </div>
                <h4 class="mb-0 fw-bold">R$ {{ number_format($totalAmt, 2, ',', '.') }}</h4>
                <small class="text-muted">{{ __('Aprovados') }}</small>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('assembly-expenses.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Status') }}</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">{{ __('Todos') }}</option>
                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>{{ __('Pendente') }}</option>
                    <option value="aprovado" {{ request('status') == 'aprovado' ? 'selected' : '' }}>{{ __('Aprovado') }}</option>
                    <option value="rejeitado" {{ request('status') == 'rejeitado' ? 'selected' : '' }}>{{ __('Rejeitado') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">{{ __('Montagem #') }}</label>
                <input type="number" name="assembly_schedule_id" class="form-control form-control-sm" value="{{ request('assembly_schedule_id') }}" placeholder="{{ __('ID da montagem') }}">
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="bx bx-filter-alt me-1"></i> {{ __('Filtrar') }}
                    </button>
                    <a href="{{ route('assembly-expenses.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-refresh"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Expense Cards --}}
@if($expenses->count() > 0)
    @foreach($expenses as $expense)
    @php
        $borderClass = $expense->status === 'pendente' ? 'border-warning' : '';
    @endphp
    <div class="card border-0 shadow-sm mb-3 {{ $borderClass }}" id="expense-{{ $expense->id }}">
        <div class="card-body">
            <div class="row align-items-center">
                {{-- Receipt thumbnail --}}
                <div class="col-auto">
                    @if($expense->receipt_path)
                        @php
                            $ext = pathinfo($expense->receipt_path, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($ext), ['jpg','jpeg','png']);
                            $receiptUrl = asset('storage/' . $expense->receipt_path);
                        @endphp
                        @if($isImage)
                            <div class="rounded overflow-hidden" style="width: 60px; height: 60px;">
                                <img src="{{ $receiptUrl }}" class="img-fluid cursor-pointer" style="width: 100%; height: 100%; object-fit: cover;" data-bs-toggle="modal" data-bs-target="#receiptModal" data-src="{{ $receiptUrl }}" data-caption="{{ $expense->description }}" title="{{ __('Clique para ampliar') }}">
                            </div>
                        @else
                            <a href="{{ $receiptUrl }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                <i class="bx bxs-file-pdf fs-4"></i>
                            </a>
                        @endif
                    @else
                        <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bx bx-image-alt fs-4 text-muted"></i>
                        </div>
                    @endif
                </div>

                {{-- Main info --}}
                <div class="col">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bx {{ $expense->category_icon }} fs-5" style="color: #DE0802;"></i>
                        <strong>{{ $expense->category }}</strong>
                        <span class="badge {{ $expense->status_badge }} rounded-pill px-2 py-1">{{ $expense->status_label }}</span>
                    </div>
                    <div class="small text-muted">
                        <span class="me-3"><i class="bx bx-user me-1"></i>{{ $expense->assembler->name ?? '—' }}</span>
                        <span class="me-3"><i class="bx bx-calendar me-1"></i>{{ $expense->date->format('d/m/Y') }}</span>
                        <span><i class="bx bx-wrench me-1"></i>{{ __('Montagem') }} #{{ $expense->assembly_schedule_id }}</span>
                    </div>
                    @if($expense->description)
                        <div class="small text-muted mt-1">{{ $expense->description }}</div>
                    @endif
                    @if($expense->rejection_reason)
                        <div class="small text-danger mt-1">
                            <i class="bx bx-x-circle me-1"></i><strong>{{ __('Motivo da rejeição:') }}</strong> {{ $expense->rejection_reason }}
                        </div>
                    @endif
                </div>

                {{-- Amount + actions --}}
                <div class="col-auto text-end">
                    <div class="fs-5 fw-bold mb-2" style="color: #DE0802;">
                        R$ {{ number_format($expense->amount, 2, ',', '.') }}
                    </div>
                    @if($expense->status === 'pendente')
                        @if(Auth::user()->hasRole('Admin'))
                        <div class="d-flex gap-1 justify-content-end">
                            <form action="{{ route('assembly-expenses.approve', $expense->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-icon btn-sm btn-outline-success" title="{{ __('Aprovar') }}">
                                    <i class="bx bx-check"></i>
                                </button>
                            </form>
                            <button type="button" class="btn btn-icon btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal" data-expense-id="{{ $expense->id }}" title="{{ __('Rejeitar') }}">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                        @else
                        <span class="badge bg-label-warning rounded-pill px-2 py-1">
                            <i class="bx bx-time-five me-1"></i>{{ __('Pendente') }}
                        </span>
                        @endif
                    @else
                        @if(Auth::user()->hasRole('Admin'))
                        <form action="{{ route('assembly-expenses.destroy', $expense->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-icon btn-sm btn-outline-danger" title="{{ __('Excluir') }}">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bx bx-receipt fs-1 d-block mb-2 text-muted"></i>
            <p class="text-muted mb-0">{{ __('Nenhuma despesa encontrada para os filtros aplicados.') }}</p>
        </div>
    </div>
@endif

{{-- Pagination --}}
@if($expenses->hasPages())
<div class="mt-3">
    {{ $expenses->withQueryString()->links() }}
</div>
@endif

{{-- Receipt Lightbox Modal --}}
<div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h6 class="modal-title" id="receiptCaption">{{ __('Comprovante') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-2">
                <img id="receiptImg" src="" alt="" class="img-fluid rounded" style="max-height:80vh;object-fit:contain">
            </div>
            <div class="modal-footer justify-content-between">
                <a id="receiptLink" href="#" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-external-link me-1"></i> {{ __('Abrir em nova aba') }}
                </a>
                <button class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">{{ __('Fechar') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" id="rejectForm">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title" style="color: #B3211A;"><i class="bx bx-x-circle me-2"></i>{{ __('Rejeitar Despesa') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="rejection_reason" class="form-label">{{ __('Motivo da Rejeição') }} <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" maxlength="500" required placeholder="{{ __('Ex: Comprovante ilegível, valor divergente...') }}"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                    <button type="submit" class="btn btn-sm" style="background-color: #B3211A; color: white;">
                        <i class="bx bx-x me-1"></i>{{ __('Confirmar Rejeição') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<style>
    .page-item.active .page-link {
        background-color: #DE0802;
        border-color: #DE0802;
    }
    .page-link {
        color: #1F2A44;
    }
    .page-link:hover {
        color: #DE0802;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var rm = document.getElementById('receiptModal');
    if(rm){
        rm.addEventListener('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            document.getElementById('receiptImg').src = btn.dataset.src;
            document.getElementById('receiptLink').href = btn.dataset.src;
            document.getElementById('receiptCaption').textContent = btn.dataset.caption || '{{ __("Comprovante") }}';
        });
        rm.addEventListener('hidden.bs.modal', function() { 
            document.getElementById('receiptImg').src = ''; 
        });
    }

    var rejectModal = document.getElementById('rejectModal');
    if(rejectModal){
        rejectModal.addEventListener('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            var id = btn.dataset.expenseId;
            document.getElementById('rejectForm').action = '/admin/assembly-expenses/' + id + '/reject';
        });
    }
});
</script>
@endsection
