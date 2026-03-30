@extends('layouts/contentNavbarLayout')

@section('title', __('Itens do Checklist'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Itens do Checklist') }}</h4>
        <p class="text-muted mb-0">{{ __('Gerencie os itens para check-up de veículos') }}</p>
    </div>
    <a href="{{ route('checklist-items.create') }}" class="btn btn-sm btn-primary">
        <i class="bx bx-plus me-1"></i> {{ __('Novo Item') }}
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 py-3 px-4">#</th>
                        <th class="border-0 py-3">{{ __('Descrição') }}</th>
                        <th class="border-0 py-3 text-center">{{ __('Restritivo') }}</th>
                        <th class="border-0 py-3 text-center">{{ __('Status') }}</th>
                        <th class="border-0 py-3 text-center">{{ __('Ações') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($checklistItems as $item)
                    <tr>
                        <td class="py-3 px-4">{{ $item->id }}</td>
                        <td class="py-3">{{ $item->description }}</td>
                        <td class="py-3 text-center">
                            @if($item->is_restrictive)
                                <span class="badge bg-label-danger rounded-pill px-2 py-1">
                                    <i class="bx bx-block me-1"></i>{{ __('Sim') }}
                                </span>
                            @else
                                <span class="badge bg-label-secondary rounded-pill px-2 py-1">
                                    {{ __('Não') }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3 text-center">
                            @if($item->status === 'active')
                                <span class="badge bg-label-success rounded-pill px-2 py-1">{{ __('Ativo') }}</span>
                            @else
                                <span class="badge bg-label-warning rounded-pill px-2 py-1">{{ __('Inativo') }}</span>
                            @endif
                        </td>
                        <td class="py-3 text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('checklist-items.edit', $item) }}" class="btn btn-icon btn-sm btn-outline-primary" title="{{ __('Editar') }}">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('checklist-items.destroy', $item) }}" method="POST" class="delete-form d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-sm btn-outline-danger" title="{{ __('Excluir') }}">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            {{ __('Nenhum item do checklist cadastrado.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($checklistItems->hasPages())
    <div class="card-footer bg-transparent border-0">
        {{ $checklistItems->links() }}
    </div>
    @endif
</div>
@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Este item será excluído permanentemente!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DE0802',
                    cancelButtonColor: '#1F2A44',
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
