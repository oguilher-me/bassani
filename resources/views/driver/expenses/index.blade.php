@extends('driver.layout')

@section('title', 'Minhas Despesas - Bassani')

@section('content')
<div class="app-header">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ url('/driver/home') }}" class="text-white">
                <i class="bx bx-arrow-back fs-4"></i>
            </a>
            <div>
                <div class="greeting">Minhas Despesas</div>
                <div class="user-name small opacity-75">{{ $expenses->total() ?? 0 }} lançamento(s)</div>
            </div>
        </div>
        <a href="{{ url('/driver/expenses/create') }}" class="btn btn-sm" style="background: rgba(255,255,255,0.2); border: none; color: white;">
            <i class="bx bx-plus"></i>
        </a>
    </div>
</div>

<div class="px-3 py-3">
    <div class="d-flex gap-2 overflow-auto pb-2" style="margin: 0 -16px; padding: 0 16px;">
        <a href="{{ url('/driver/expenses?filter=all') }}" 
           class="btn btn-sm {{ request('filter', 'all') == 'all' ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-3 flex-shrink-0">
            Todos
        </a>
        <a href="{{ url('/driver/expenses?filter=pendente') }}" 
           class="btn btn-sm {{ request('filter') == 'pendente' ? 'btn-warning' : 'btn-outline-warning' }} rounded-pill px-3 flex-shrink-0">
            Pendentes
        </a>
        <a href="{{ url('/driver/expenses?filter=aprovado') }}" 
           class="btn btn-sm {{ request('filter') == 'aprovado' ? 'btn-success' : 'btn-outline-success' }} rounded-pill px-3 flex-shrink-0">
            Aprovados
        </a>
        <a href="{{ url('/driver/expenses?filter=rejeitado') }}" 
           class="btn btn-sm {{ request('filter') == 'rejeitado' ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill px-3 flex-shrink-0">
            Rejeitados
        </a>
    </div>
</div>

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
            <div class="expense-date">{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</div>
        </div>
        <div class="text-end">
            <div class="expense-amount">R$ {{ number_format($expense->amount, 2, ',', '.') }}</div>
            <span class="expense-status {{ $expense->status === 'aprovado' ? 'bg-label-success text-success' : ($expense->status === 'rejeitado' ? 'bg-label-danger text-danger' : 'bg-label-warning text-warning') }}">
                {{ $expense->status_label }}
            </span>
        </div>
    </div>
    
    <div id="details-{{ $expense->id }}" class="mx-3 mb-3" style="display: none;">
        <div class="form-card" style="margin: 0;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-light text-dark">
                    <i class="bx bx-truck me-1"></i> Entrega #{{ $expense->shipment_id }}
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
                    <strong>Motivo da rejeição:</strong> {{ $expense->rejection_reason }}
                </div>
            @endif
        </div>
    </div>
    @endforeach
    
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
        <a href="{{ url('/driver/expenses/create') }}" class="btn btn-sm btn-primary mt-2" style="border-radius: 10px;">
            <i class="bx bx-plus me-1"></i> Lançar primeira despesa
        </a>
    </div>
@endif
@endsection

@section('scripts')
<style>
.expense-card {
    background: white;
    border-radius: 16px;
    margin: 0 16px 12px;
    padding: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    display: flex;
    align-items: center;
    gap: 12px;
}

.expense-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.expense-details {
    flex: 1;
    min-width: 0;
}

.expense-category {
    font-weight: 600;
    color: var(--bassani-navy);
    font-size: 0.95rem;
}

.expense-date {
    font-size: 0.8rem;
    color: #6c757d;
}

.expense-amount {
    font-weight: 700;
    color: var(--bassani-navy);
    font-size: 1rem;
}

.expense-status {
    font-size: 0.7rem;
    padding: 2px 8px;
    border-radius: 10px;
    display: inline-block;
    margin-top: 2px;
}
</style>
<script>
function toggleExpenseDetails(id) {
    const details = document.getElementById('details-' + id);
    if (details) {
        const isVisible = details.style.display === 'block';
        document.querySelectorAll('[id^="details-"]').forEach(el => el.style.display = 'none');
        if (!isVisible) {
            details.style.display = 'block';
            if (navigator.vibrate) navigator.vibrate(10);
        }
    }
}
</script>
@endsection
