@extends('layouts/contentNavbarLayout')

@section('title', __('Perfil do Vendedor') . ' - ' . $seller->name)

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Perfil do Vendedor') }}</h4>
        <p class="text-muted mb-0">{{ $seller->name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('crm.sellers.edit', $seller->id) }}" class="btn btn-primary">
            <i class="bx bx-edit me-1"></i> {{ __('Editar') }}
        </a>
        <a href="{{ route('crm.sellers.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="text-center mb-4 pb-4 border-bottom">
                    @if($seller->photo)
                        <img class="rounded-circle mb-3" src="{{ Storage::url($seller->photo) }}" height="100" width="100" alt="{{ $seller->name }}" style="object-fit: cover;">
                    @else
                        <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                            {{ strtoupper(substr($seller->name, 0, 2)) }}
                        </div>
                    @endif
                    <h5 class="fw-bold mb-1">{{ $seller->name }}</h5>
                    <span class="badge bg-primary rounded-pill px-3 py-2">{{ __('Consultor Comercial') }}</span>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="bx bx-envelope text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('E-mail') }}</small>
                        <span class="fw-semibold small">{{ $seller->email }}</span>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="bx bx-phone text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('Telefone') }}</small>
                        <span class="fw-semibold">{{ $seller->phone ?? __('Não informado') }}</span>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="bx bx-id-card text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('CPF') }}</small>
                        <span class="fw-semibold">{{ $seller->cpf }}</span>
                    </div>
                </div>
                
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="bx bx-dollar text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('Comissão') }}</small>
                        <span class="fw-semibold text-success">{{ number_format($seller->commission_percentage, 2) }}%</span>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Status Card --}}
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body p-4">
                <div class="text-center">
                    <div class="mb-2">
                        @if($seller->status === 'active')
                            <span class="badge bg-success rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-check-circle me-1"></i>{{ __('Ativo') }}
                            </span>
                        @else
                            <span class="badge bg-secondary rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-x-circle me-1"></i>{{ __('Inativo') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats & Content --}}
    <div class="col-lg-8">
        {{-- Stats Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="d-block mb-1 small text-muted">{{ __('Taxa de Conversão') }}</span>
                                <span class="fw-bold fs-4">{{ round($seller->conversion_rate) }}%</span>
                                <small class="text-muted d-block">{{ __('Soma de Oportunidades Ganhas') }}</small>
                            </div>
                            <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="bx bx-trending-up fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="d-block mb-1 small text-muted">{{ __('Ticket Médio') }}</span>
                                <span class="fw-bold fs-4">R$ {{ number_format($seller->average_ticket, 0, ',', '.') }}</span>
                                <small class="text-muted d-block">{{ __('Base: Vendas Ganhas') }}</small>
                            </div>
                            <div class="avatar rounded-circle bg-label-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="bx bx-dollar fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="d-block mb-1 small text-muted">{{ __('Leads em Aberto') }}</span>
                                <span class="fw-bold fs-4">{{ $seller->open_leads_count }}</span>
                                <small class="text-muted d-block">{{ __('Aguardando conversão') }}</small>
                            </div>
                            <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="bx bx-user-voice fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Opportunities Card --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-briefcase text-danger me-2"></i>{{ __('Oportunidades Ativas (Últimas 10)') }}
                </h6>
                <span class="badge bg-label-primary">{{ $seller->opportunities->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($seller->opportunities->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4">{{ __('Título') }}</th>
                                    <th class="py-3">{{ __('Valor Est.') }}</th>
                                    <th class="py-3">{{ __('Etapa') }}</th>
                                    <th class="py-3 text-center">{{ __('Ações') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($seller->opportunities as $opp)
                                    <tr>
                                        <td class="py-3 px-4">
                                            <span class="fw-semibold">{{ $opp->title }}</span>
                                        </td>
                                        <td class="py-3">
                                            <span class="fw-semibold text-success">R$ {{ number_format($opp->estimated_value, 2, ',', '.') }}</span>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-primary rounded-pill px-3 py-2">{{ $opp->stage->name ?? 'N/A' }}</span>
                                        </td>
                                        <td class="py-3 text-center">
                                            <a href="{{ route('crm.opportunities.show', $opp->id) }}" class="btn btn-icon btn-sm btn-outline-info" title="{{ __('Ver') }}">
                                                <i class="bx bx-show"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bx bx-briefcase fs-1 text-muted opacity-50"></i>
                        <p class="text-muted mt-2">{{ __('Nenhuma oportunidade ativa no momento.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection