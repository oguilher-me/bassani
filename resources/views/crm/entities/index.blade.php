@extends('layouts/contentNavbarLayout')

@php
    $typeLabel = match($type ?? '') {
        'lead' => __('Leads'),
        'architect' => __('Arquitetos'),
        'partner' => __('Parceiros'),
        default => __('Entidades')
    };
    $createRoute = match($type ?? '') {
        'lead' => route('crm.leads.create'),
        'architect' => route('crm.architects.create'),
        'partner' => route('crm.partners.create'),
        default => route('crm.entities.create')
    };
    $typeName = match($type ?? '') {
        'lead' => __('Lead'),
        'architect' => __('Arquiteto'),
        'partner' => __('Parceiro'),
        default => __('Entidade')
    };
@endphp

@section('title', $typeLabel)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $typeLabel }}</h4>
            <p class="text-muted mb-0">{{ __('CRM') }}</p>
        </div>
        <a href="{{ $createRoute }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> {{ __('Novo') }} {{ $typeName }}
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bx bx-group text-danger me-2"></i>{{ __('Lista de') }} {{ $typeLabel }}
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 py-3 px-4">{{ __('Nome') }}</th>
                            <th class="border-0 py-3">{{ __('Tipo') }}</th>
                            <th class="border-0 py-3">{{ __('Segmento') }}</th>
                            <th class="border-0 py-3">{{ __('Documento') }}</th>
                            <th class="border-0 py-3">{{ __('Responsável') }}</th>
                            <th class="border-0 py-3 text-end">{{ __('Ações') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entities as $entity)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ substr($entity->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <span class="fw-semibold">{{ $entity->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-label-{{ 
                                        $entity->type == 'client' ? 'primary' : 
                                        ($entity->type == 'architect' ? 'info' : 
                                        ($entity->type == 'partner' ? 'warning' : 'secondary')) 
                                    }} rounded-pill">{{ ucfirst($entity->type) }}</span>
                                </td>
                                <td class="py-3">{{ ucfirst($entity->segment) }}</td>
                                <td class="py-3">{{ $entity->document ?? '-' }}</td>
                                <td class="py-3">{{ $entity->assignedUser->name ?? '-' }}</td>
                                <td class="py-3 text-end">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="{{ route('crm.entities.show', $entity->id) }}">
                                                <i class="bx bx-show-alt me-1"></i> {{ __('Visão 360º') }}
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0);">
                                                <i class="bx bx-edit-alt me-1"></i> {{ __('Editar') }}
                                            </a>
                                            <a class="dropdown-item text-danger" href="javascript:void(0);">
                                                <i class="bx bx-trash me-1"></i> {{ __('Excluir') }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bx bx-user-x fs-1 d-block mb-2"></i>
                                    {{ __('Nenhuma entidade encontrada.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
