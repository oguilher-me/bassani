@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes do Montador'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Detalhes do Montador') }}</h4>
        <p class="text-muted mb-0">{{ $assembler->name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('assemblers.edit', $assembler->id) }}" class="btn btn-primary">
            <i class="bx bx-edit me-1"></i> {{ __('Editar') }}
        </a>
        <a href="{{ route('assemblers.index') }}" class="btn btn-outline-secondary">
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
                    @if ($assembler->photo)
                        <img class="rounded-circle mb-3" src="{{ Storage::url($assembler->photo) }}" height="100" width="100" alt="{{ $assembler->name }}" style="object-fit: cover;">
                    @else
                        <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                            {{ strtoupper(substr($assembler->name, 0, 1)) }}
                        </div>
                    @endif
                    <h5 class="fw-bold mb-1">{{ $assembler->name }}</h5>
                    <span class="badge {{ $assembler->type->value == 'CONTRACTED' ? 'bg-success' : 'bg-info' }} rounded-pill px-3 py-2">
                        {{ $assembler->type->label() }}
                    </span>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="bx bx-id-card text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('CPF') }}</small>
                        <span class="fw-semibold">{{ $assembler->cpf ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $assembler->cpf) : '-' }}</span>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="bx bx-envelope text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('Email') }}</small>
                        <span class="fw-semibold">{{ $assembler->email }}</span>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="bx bx-phone text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('Telefone') }}</small>
                        <span class="fw-semibold">{{ $assembler->phone }}</span>
                    </div>
                </div>
                
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="bx bx-map text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('Endereço') }}</small>
                        <span class="fw-semibold small">
                            {{ $assembler->address ?? '-' }}
                            @if($assembler->city)
                                <br>{{ $assembler->city }}@if($assembler->state) - {{ $assembler->state }}@endif
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="col-lg-8">
        {{-- Status Card --}}
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar rounded-circle bg-label-{{ $assembler->status == 1 ? 'success' : 'danger' }} d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="bx bx-{{ $assembler->status == 1 ? 'check' : 'x' }} fs-4"></i>
                            </div>
                            <div>
                                <span class="d-block mb-1 small text-muted">{{ __('Status') }}</span>
                                <span class="fw-bold fs-5">{{ $assembler->status == 1 ? __('Ativo') : __('Inativo') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="bx bx-calendar fs-4"></i>
                            </div>
                            <div>
                                <span class="d-block mb-1 small text-muted">{{ __('Cadastrado em') }}</span>
                                <span class="fw-bold">{{ $assembler->created_at ? $assembler->created_at->format('d/m/Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Schedule Stats --}}
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-calendar-check fs-4"></i>
                        </div>
                        <span class="fw-bold fs-4 d-block">{{ $assembler->assemblySchedules()->count() }}</span>
                        <small class="text-muted">{{ __('Agendamentos') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="avatar rounded-circle bg-label-success d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-check-circle fs-4"></i>
                        </div>
                        <span class="fw-bold fs-4 d-block">{{ $assembler->assemblySchedules()->where('assembly_schedule_assembler.confirmation_status', 'completed')->count() }}</span>
                        <small class="text-muted">{{ __('Concluídos') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 48px; height: 48px;">
                            <i class="bx bx-star fs-4"></i>
                        </div>
                        <span class="fw-bold fs-4 d-block">{{ $assembler->assemblySchedules()->avg('assembly_schedule_assembler.nps_score') ? number_format($assembler->assemblySchedules()->avg('assembly_schedule_assembler.nps_score'), 1) : '-' }}</span>
                        <small class="text-muted">{{ __('Avaliação Média') }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- System Info --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-time text-danger me-2"></i>{{ __('Informações do Sistema') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">{{ __('Criado em') }}</span>
                    <span class="fw-semibold">{{ $assembler->created_at ? $assembler->created_at->format('d/m/Y H:i:s') : '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">{{ __('Atualizado em') }}</span>
                    <span class="fw-semibold">{{ $assembler->updated_at ? $assembler->updated_at->format('d/m/Y H:i:s') : '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection