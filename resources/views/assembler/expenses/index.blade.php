@extends('assembler.layout')

@section('title', 'Minhas Despesas - Bassani')

@section('content')
{{-- Header --}}
<div class="app-header">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ url('/assembler/home') }}" class="text-white">
                <i class="bx bx-arrow-back fs-4"></i>
            </a>
            <div>
                <div class="greeting">Minhas Despesas</div>
                <div class="user-name small opacity-75">{{ $expenses->total() ?? 0 }} lançamento(s)</div>
            </div>
        </div>
        <a href="{{ url('/assembler/expenses/create') }}" class="btn btn-sm" style="background: rgba(255,255,255,0.2); border: none; color: white;">
            <i class="bx bx-plus"></i>
        </a>
    </div>
</div>

{{-- Filter Tabs --}}
<div class="px-3 py-3">
    <div class="d-flex gap-2 overflow-auto pb-2" style="margin: 0 -16px; padding: 0 16px;">
        <a href="{{ url('/assembler/expenses?filter=all') }}" 
           class="btn btn-sm {{ request('filter', 'all') == 'all' ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-3 flex-shrink-0">
            Todos
        </a>
        <a href="{{ url('/assembler/expenses?filter=pendente') }}" 
           class="btn btn-sm {{ request('filter') == 'pendente' ? 'btn-warning' : 'btn-outline-warning' }} rounded-pill px-3 flex-shrink-0">
            Pendentes
        </a>
        <a href="{{ url('/assembler/expenses?filter=aprovado') }}" 
           class="btn btn-sm {{ request('filter') == 'aprovado' ? 'btn-success' : 'btn-outline-success' }} rounded-pill px-3 flex-shrink-0">
            Aprovados
        </a>
        <a href="{{ url('/assembler/expenses?filter=rejeitado') }}" 
           class="btn btn-sm {{ request('filter') == 'rejeitado' ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill px-3 flex-shrink-0">
            Rejeitados
        </a>
    </div>
</div>

{{-- Summary Card --}}
<div class="form-card mx-3 mb-3" style="background: linear-gradient(135deg, var(--bassani-navy) 0%, #2a3a5c 100%); color: white;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <div class="small opacity-75">Total Aprovado</div>
            <div class="fs-4 fw-bold" style="color: #4ade80;">R$ {{ number_format($approvedTotal ?? 0, 2, ',', '.') }}</div>
        </div>
        <div class="text-end">
            <div class="small opacity-75">Total Pendente</div>
            <div class="fs-4 fw-bold" style="color: #fbbf24;">R$ {{ number_format($pendingTotal ?? 0, 2, ',', '.') }}</div>
        </div>
    </div>
</div>

{{-- Expenses List --}}
@if(isset($expenses) && $expenses->count() > 0)
    @foreach($expenses as $expense)
    <div class="expense-card" onclick="toggleExpenseDetails({{ $expense->id }})">
        <div class="expense-icon {{ $expense->status === 'aprovado' ? 'bg-label-success text-success' : ($expense->status === 'rejeitado' ? 'bg-label-danger text-danger' : 'bg-label-warning text-warning') }}">
            <i class="bx {{ match($expense->category) {
                'Alimentação' => 'bx-restaurant',
                'Hospedagem' => 'bx-hotel',
                'Combustível' => 'bx-gas-pump',
                'Pedágio' => 'bx-transfer',
                'Estacionamento' => 'bx-car',
                'Material Extra' => 'bx-package',
                default => 'bx-receipt'
            } }}"></i>
        </div>
        <div class="expense-details">
            <div class="expense-category">{{ $expense->category }}</div>
            <div class="expense-date">{{ $expense->date->format('d/m/Y') }}</div>
        </div>
        <div class="text-end">
            <div class="expense-amount">R$ {{ number_format($expense->amount, 2, ',', '.') }}</div>
            <span class="expense-status {{ $expense->status === 'aprovado' ? 'bg-label-success text-success' : ($expense->status === 'rejeitado' ? 'bg-label-danger text-danger' : 'bg-label-warning text-warning') }}">
                {{ $expense->status_label }}
            </span>
        </div>
    </div>
    
    {{-- Expanded Details --}}
    <div id="details-{{ $expense->id }}" class="mx-3 mb-3" style="display: none;">
        <div class="form-card" style="margin: 0;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-light text-dark">
                    <i class="bx bx-wrench me-1"></i> Montagem #{{ $expense->assembly_schedule_id }}
                </span>
                @if($expense->receipt_path)
                    <a href="{{ asset('storage/' . $expense->receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-show me-1"></i> Comprovante
                    </a>
                @endif
            </div>
            
            @if($expense->description)
                <div class="mb-3">
                    <div class="small text-muted mb-1">Descrição</div>
                    <div class="small">{{ $expense->description }}</div>
                </div>
            @endif
            
            @if($expense->rejection_reason)
                <div class="alert alert-danger py-2 small mb-0">
                    <i class="bx bx-x-circle me-1"></i>
                    <strong>{{ __('Motivo da rejeição:') }}</strong> {{ $expense->rejection_reason }}
                </div>
            @endif
            
            @if($expense->status === 'pendente')
                <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-3" onclick="deleteExpense({{ $expense->id }})">
                    <i class="bx bx-trash me-1"></i> Excluir
                </button>
                <form id="delete-form-{{ $expense->id }}" action="{{ route('assembly-expenses.destroy', $expense->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>
    </div>
    @endforeach
    
    {{-- Pagination --}}
    @if($expenses->hasPages())
    <div class="px-3 py-3">
        <div class="d-flex justify-content-center gap-2">
            @if($expenses->onFirstPage())
                <span class="btn btn-sm btn-outline-secondary disabled">Anterior</span>
            @else
                <a href="{{ $expenses->previousPageUrl() }}" class="btn btn-sm btn-outline-primary">Anterior</a>
            @endif
            
            <span class="btn btn-sm btn-outline-secondary disabled">
                {{ $expenses->currentPage() }} / {{ $expenses->lastPage() }}
            </span>
            
            @if($expenses->hasMorePages())
                <a href="{{ $expenses->nextPageUrl() }}" class="btn btn-sm btn-outline-primary">Próxima</a>
            @else
                <span class="btn btn-sm btn-outline-secondary disabled">Próxima</span>
            @endif
        </div>
    </div>
    @endif
@else
    <div class="empty-state">
        <i class="bx bx-receipt"></i>
        <p>Nenhuma despesa lançada ainda.</p>
        <a href="{{ url('/assembler/expenses/create') }}" class="btn btn-sm btn-primary mt-2" style="border-radius: 10px;">
            <i class="bx bx-plus me-1"></i> Lançar primeira despesa
        </a>
    </div>
@endif
@endsection

@section('scripts')
<script>
function toggleExpenseDetails(id) {
    const details = document.getElementById('details-' + id);
    if (details) {
        const isVisible = details.style.display === 'block';
        // Hide all details first
        document.querySelectorAll('[id^="details-"]').forEach(el => el.style.display = 'none');
        // Show clicked if it was hidden
        if (!isVisible) {
            details.style.display = 'block';
            if (navigator.vibrate) navigator.vibrate(10);
        }
    }
}

function deleteExpense(id) {
    if (confirm('Tem certeza que deseja excluir esta despesa?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection
