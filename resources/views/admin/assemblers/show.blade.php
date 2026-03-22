@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes do Montador'))

@section('content')

<div class="row mb-6 gy-6" style="margin-bottom: 10px !important;">
    <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-0">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Detalhes do Montador') }}</h5>
                <a href="{{ route('assemblers.index') }}" class="btn btn-primary">{{ __('Voltar') }}</a>
            </div>
        </div>
    </div>
</div>
<div class="row mb-6 gy-6">
    <!-- Assembler Details -->
    <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
        <div class="card mb-4">
            <div class="card-body">
                <div class="user-avatar-section">
                    <div class="d-flex align-items-center flex-column">
                        @if ($assembler->photo)
                            <img class="img-fluid rounded mb-3 mt-4" src="{{ Storage::url($assembler->photo) }}" height="120" width="120" alt="Assembler avatar" />
                        @else
                            <img class="img-fluid rounded mb-3 mt-4" src="{{ asset('assets/img/avatars/default-avatar.png') }}" height="120" width="120" alt="Default avatar" />
                        @endif
                        <div class="user-info text-center">
                            <h4 class="mb-2">{{ $assembler->name }}</h4>
                            <span class="badge bg-label-secondary">{{ $assembler->type->label() }}</span>
                        </div>
                    </div>
                </div>
                <h5 class="pb-3 border-bottom mb-3">{{ __('Detalhes') }}</h5>
                <div class="info-container">
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2">
                            <span class="fw-medium me-1">{{ __('CPF:') }}</span>
                            <span>{{ $assembler->cpf ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $assembler->cpf) : '-' }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="fw-medium me-1">{{ __('Email:') }}</span>
                            <span>{{ $assembler->email }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="fw-medium me-1">{{ __('Telefone:') }}</span>
                            <span>{{ $assembler->phone }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="fw-medium me-1">{{ __('Endereço:') }}</span>
                            <span>{{ $assembler->address ?? '-' }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="fw-medium me-1">{{ __('Cidade:') }}</span>
                            <span>{{ $assembler->city ?? '-' }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="fw-medium me-1">{{ __('Estado:') }}</span>
                            <span>{{ $assembler->state ?? '-' }}</span>
                        </li>
                    </ul>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('assemblers.edit', $assembler->id) }}" class="btn btn-primary me-3">{{ __('Editar Detalhes') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Assembler Details -->

    <!-- Assembler Overview -->
    <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
        <div class="row">
            <!-- Status -->
            <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-check fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">
                                        @if($assembler->status == 1)
                                            {{ __('Ativo') }}
                                        @else
                                            {{ __('Inativo') }}
                                        @endif
                                    </h5>
                                    <small class="text-muted">{{ __('Status') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Created At -->
            <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-warning"><i class="bx bx-calendar fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">
                                        {{ $assembler->created_at ? $assembler->created_at->format('d/m/Y H:i:s') : '-' }}
                                    </h5>
                                    <small class="text-muted">{{ __('Criado em') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Updated At -->
            <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-info"><i class="bx bx-calendar-check fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">
                                        {{ $assembler->updated_at ? $assembler->updated_at->format('d/m/Y H:i:s') : '-' }}
                                    </h5>
                                    <small class="text-muted">{{ __('Atualizado em') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Assembler Overview -->
</div>
@endsection