@extends('layouts/contentNavbarLayout')

@section('title', 'Gestão de Vendedores')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Vendedores</h5>
        <a href="{{ route('crm.sellers.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Novo Vendedor
        </a>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Vendedor</th>
                    <th>CPF</th>
                    <th>Comissão</th>
                    <th>Vendas (Mês)</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach($sellers as $seller)
                <tr>
                    <td>
                        <div class="d-flex justify-content-start align-items-center seller-name">
                            <div class="avatar-wrapper">
                                <div class="avatar avatar-sm me-3">
                                    @if($seller->photo)
                                        <img src="{{ Storage::url($seller->photo) }}" alt="Avatar" class="rounded-circle">
                                    @else
                                        <span class="avatar-initial rounded-circle bg-label-primary">{{ strtoupper(substr($seller->name, 0, 2)) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <a href="{{ route('crm.sellers.show', $seller->id) }}" class="text-body text-truncate fw-semibold">{{ $seller->name }}</a>
                                <small class="text-muted">{{ $seller->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $seller->cpf }}</td>
                    <td>{{ number_format($seller->commission_percentage, 2) }}%</td>
                    <td>R$ {{ number_format($seller->monthly_sales, 2, ',', '.') }}</td>
                    <td>
                        <span class="badge bg-label-{{ $seller->status === 'active' ? 'success' : 'secondary' }}">
                            {{ $seller->status === 'active' ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-menu-item" href="{{ route('crm.sellers.show', $seller->id) }}"><i class="bx bx-show-alt me-1"></i> Detalhes</a>
                                <a class="dropdown-item" href="{{ route('crm.sellers.edit', $seller->id) }}"><i class="bx bx-edit-alt me-1"></i> Editar</a>
                                <form action="{{ route('crm.sellers.destroy', $seller->id) }}" method="POST" onsubmit="return confirm('Deseja realmente desativar este vendedor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger"><i class="bx bx-trash me-1"></i> Desativar</button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
