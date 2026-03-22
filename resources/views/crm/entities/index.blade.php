@extends('layouts.app')

@php
    $typeLabel = match($type ?? '') {
        'lead' => 'Leads',
        'architect' => 'Arquitetos',
        'partner' => 'Parceiros',
        default => 'Entidades'
    };
    $createRoute = match($type ?? '') {
        'lead' => route('crm.leads.create'),
        'architect' => route('crm.architects.create'),
        'partner' => route('crm.partners.create'),
        default => route('crm.entities.create')
    };
@endphp

@section('title', $typeLabel)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">{{ $typeLabel }}</h4>
        <a href="{{ $createRoute }}" class="btn btn-primary">Novo {{ rtrim($typeLabel, 's') }}</a>
    </div>

    <div class="card">
        <h5 class="card-header">Lista de {{ $typeLabel }}</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Segmento</th>
                        <th>Documento</th>
                        <th>Responsável</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($entities as $entity)
                        <tr>
                            <td><strong>{{ $entity->name }}</strong></td>
                            <td>
                                <span class="badge bg-label-{{ 
                                    $entity->type == 'client' ? 'primary' : 
                                    ($entity->type == 'architect' ? 'info' : 
                                    ($entity->type == 'partner' ? 'warning' : 'secondary')) 
                                }} me-1">{{ ucfirst($entity->type) }}</span>
                            </td>
                            <td>{{ ucfirst($entity->segment) }}</td>
                            <td>{{ $entity->document ?? '-' }}</td>
                            <td>{{ $entity->assignedUser->name ?? '-' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('crm.entities.show', $entity->id) }}"><i class="bx bx-show-alt me-1"></i> Visão 360º</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Editar</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Excluir</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhuma entidade encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
