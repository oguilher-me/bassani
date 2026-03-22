@extends('layouts.app')

@section('title', 'Arquitetos e Designers')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Arquitetos e Designers</h4>
        <a href="{{ route('crm.architects.create') }}" class="btn btn-primary">Novo Profissional</a>
    </div>

    <!-- Search & Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('crm.architects.index') }}" method="GET" class="row gx-3 gy-2 align-items-center">
                <div class="col-md-4">
                    <label class="form-label visually-hidden" for="search">Buscar</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Buscar por Nome ou Especialidade..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label visually-hidden" for="specialty">Especialidade</label>
                    <select class="form-select" id="specialty" name="specialty">
                        <option value="">Todas Especialidades</option>
                        <option value="Interiores" {{ request('specialty') == 'Interiores' ? 'selected' : '' }}>Interiores</option>
                        <option value="Residencial" {{ request('specialty') == 'Residencial' ? 'selected' : '' }}>Residencial</option>
                        <option value="Comercial" {{ request('specialty') == 'Comercial' ? 'selected' : '' }}>Comercial</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
                @if(request()->hasAny(['search', 'specialty']))
                <div class="col-md-2">
                    <a href="{{ route('crm.architects.index') }}" class="btn btn-outline-secondary w-100">Limpar</a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- List -->
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Documento</th>
                        <th>Especialidade</th>
                        <th>Status / Avaliação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($architects as $architect)
                        <tr>
                            <td>
                                <strong>{{ $architect->name }}</strong>
                                @if($architect->social_links && isset($architect->social_links['instagram']))
                                    <br><small class="text-muted"><i class='bx bxl-instagram'></i> {{ $architect->social_links['instagram'] }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-label-secondary">{{ $architect->document_type }}</span> {{ $architect->document_number }}
                            </td>
                            <td>{{ $architect->specialty ?? '-' }}</td>
                            <td>
                                <span class="badge bg-label-{{ $architect->status ? 'success' : 'danger' }} me-2">{{ $architect->status ? 'Ativo' : 'Inativo' }}</span>
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class='bx {{ $i <= $architect->rating ? 'bxs-star' : 'bx-star' }}'></i>
                                    @endfor
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('crm.architects.show', $architect->id) }}"><i class="bx bx-show-alt me-1"></i> Dashboard</a>
                                        <a class="dropdown-item" href="{{ route('crm.architects.edit', $architect->id) }}"><i class="bx bx-edit-alt me-1"></i> Editar</a>
                                        <form action="{{ route('crm.architects.destroy', $architect->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item"><i class="bx bx-trash me-1"></i> Excluir</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Nenhum arquiteto encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer py-2">
            {{ $architects->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
