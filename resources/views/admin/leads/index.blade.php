@extends('layouts.app')

@section('title', 'Gestão de Leads')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Gestão de Leads</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLeadModal">
            Novo Lead
        </button>
    </div>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nome / Lead</th>
                        <th>Contato</th>
                        <th>Status</th>
                        <th>Data Criação</th>
                        <th>Origem</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($leads as $lead)
                        <tr>
                            <td>
                                <strong><a href="{{ route('crm.leads.show', $lead->id) }}">{{ $lead->name }}</a></strong>
                                <br><small class="text-muted">{{ $lead->city }}/{{ $lead->uf }}</small>
                            </td>
                            <td>
                                @if($lead->whatsapp)
                                    <div><i class='bx bxl-whatsapp text-success'></i> {{ $lead->whatsapp }}</div>
                                @endif
                                @if($lead->email)
                                    <small class='text-muted'><i class='bx bx-envelope'></i> {{ $lead->email }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-label-{{ match($lead->status) { 'new' => 'info', 'converted' => 'success', 'discarded' => 'danger', default => 'primary' } }}">
                                    {{ ucfirst($lead->status) }}
                                </span>
                            </td>
                            <td>{{ $lead->created_at->format('d/m/Y') }}</td>
                            <td>{{ ucfirst($lead->source) }}</td>
                            <td>
                                <div class="d-flex">
                                    @if($lead->whatsapp)
                                        <a href="https://web.whatsapp.com/send?phone={{ preg_replace('/[^0-9]/', '', $lead->whatsapp) }}&text=Olá {{ $lead->name }}, falo da Bassani..." target="_blank" class="btn btn-sm btn-icon btn-outline-success me-1" title="Whatsapp Web">
                                            <i class='bx bxl-whatsapp'></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('crm.leads.edit', $lead->id) }}" class="btn btn-sm btn-icon btn-outline-primary me-1" title="Editar Lead">
                                        <i class='bx bx-edit-alt'></i>
                                    </a>
                                    <form id="delete-form-{{ $lead->id }}" action="{{ route('crm.leads.destroy', $lead->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-icon btn-outline-danger" title="Excluir Lead" onclick="confirmDelete('{{ $lead->id }}', '{{ $lead->name }}')">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">Nenhum Lead encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $leads->links() }}
        </div>
    </div>
</div>

{{-- SweetAlert script for deletion --}}
@section('page-script')
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Você tem certeza?',
            text: "O lead '" + name + "' e todo o seu histórico serão excluídos permanentemente!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-danger me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection

<!-- Quick Create Modal -->
<div class="modal fade" id="createLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('crm.leads.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Novo Lead Rápido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Nome *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Celular/WhatsApp</label>
                            <input type="text" name="phone" class="form-control" placeholder="(00) 00000-0000">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Origem</label>
                            <select name="source" class="form-select">
                                <option value="store">Loja Física</option>
                                <option value="instagram">Instagram</option>
                                <option value="site">Site</option>
                                <option value="referral">Indicação</option>
                                <option value="architect">Arquiteto</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Tipo</label>
                            <select name="type" class="form-select">
                                <option value="PF">Pessoa Física</option>
                                <option value="PJ">Pessoa Jurídica</option>
                            </select>
                        </div>
                        <input type="hidden" name="status" value="new">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
