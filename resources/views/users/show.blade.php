@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes do Usuário'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Detalhes do Usuário') }}</h4>
        <p class="text-muted mb-0">{{ $user->name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
            <i class="bx bx-edit me-1"></i> {{ __('Editar') }}
        </a>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
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
                    <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <span class="badge bg-primary rounded-pill px-3 py-2">{{ $user->role->name ?? 'N/A' }}</span>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="bx bx-envelope text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('Email') }}</small>
                        <span class="fw-semibold">{{ $user->email }}</span>
                    </div>
                </div>
                
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="bx bx-shield text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('Perfil de Acesso') }}</small>
                        <span class="fw-semibold">{{ $user->role->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Status Card --}}
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body p-4">
                <div class="text-center">
                    <div class="mb-2">
                        @if ($user->status)
                            <span class="badge bg-success rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-check-circle me-1"></i>{{ __('Ativo') }}
                            </span>
                        @else
                            <span class="badge bg-danger rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-x-circle me-1"></i>{{ __('Inativo') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="col-lg-8">
        <div class="row g-4">
            {{-- Permissions Card --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-lock text-danger me-2"></i>{{ __('Permissões do Perfil') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($user->role && $user->role->permissions && $user->role->permissions->count() > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($user->role->permissions as $permission)
                                    <span class="badge bg-light text-dark px-3 py-2">
                                        <i class="bx bx-check text-success me-1"></i>{{ $permission->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="bx bx-lock-open fs-1 text-muted opacity-50"></i>
                                <p class="text-muted mt-2 mb-0">{{ __('Nenhuma permissão definida') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- System Info --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="bx bx-calendar-plus fs-4"></i>
                            </div>
                            <div>
                                <span class="d-block mb-1 small text-muted">{{ __('Criado em') }}</span>
                                <span class="fw-semibold">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="bx bx-calendar-check fs-4"></i>
                            </div>
                            <div>
                                <span class="d-block mb-1 small text-muted">{{ __('Última Atualização') }}</span>
                                <span class="fw-semibold">{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection