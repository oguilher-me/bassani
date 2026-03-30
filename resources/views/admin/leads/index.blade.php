@extends('layouts/contentNavbarLayout')

@section('title', 'Gestão de Leads')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Gestão de Leads</h4>
                <p class="text-muted mb-0">CRM / Leads</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLeadModal">
                <i class="bx bx-plus me-1"></i> Novo Lead
            </button>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="bx bx-user text-danger me-2"></i>Lista de Leads
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 px-4">Nome / Lead</th>
                                <th class="border-0 py-3">Contato</th>
                                <th class="border-0 py-3">Status</th>
                                <th class="border-0 py-3">Data</th>
                                <th class="border-0 py-3">Origem</th>
                                <th class="border-0 py-3 text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leads as $lead)
                                <tr>
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">{{ substr($lead->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <a href="{{ route('crm.leads.show', $lead->id) }}" class="fw-semibold text-decoration-none">{{ $lead->name }}</a>
                                                <div class="small text-muted">{{ $lead->city }}/{{ $lead->uf }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        @if($lead->whatsapp)
                                            <div class="mb-1"><i class="bx bxl-whatsapp text-success me-1"></i>{{ $lead->whatsapp }}</div>
                                        @endif
                                        @if($lead->email)
                                            <small class="text-muted"><i class="bx bx-envelope me-1"></i>{{ $lead->email }}</small>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        @php
                                            $colors = ['new' => 'info', 'contacted' => 'primary', 'qualified' => 'warning', 'converted' => 'success', 'lost' => 'danger', 'discarded' => 'secondary'];
                                            $labels = ['new' => 'Novo', 'contacted' => 'Contatado', 'qualified' => 'Qualificado', 'converted' => 'Convertido', 'lost' => 'Perdido', 'discarded' => 'Descartado'];
                                        @endphp
                                        <span class="badge bg-label-{{ $colors[$lead->status] ?? 'secondary' }} rounded-pill">{{ $labels[$lead->status] ?? ucfirst($lead->status) }}</span>
                                    </td>
                                    <td class="py-3">{{ $lead->created_at->format('d/m/Y') }}</td>
                                    <td class="py-3">{{ ucfirst($lead->source) }}</td>
                                    <td class="py-3 text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            @if($lead->whatsapp)
                                                <a href="https://web.whatsapp.com/send?phone={{ preg_replace('/[^0-9]/', '', $lead->whatsapp) }}" target="_blank" class="btn btn-sm btn-icon btn-outline-success">
                                                    <i class="bx bxl-whatsapp"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('crm.leads.edit', $lead->id) }}" class="btn btn-sm btn-icon btn-outline-primary">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            <form id="del-{{ $lead->id }}" action="{{ route('crm.leads.destroy', $lead->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-icon btn-outline-danger" onclick="delLead('{{ $lead->id }}', '{{ $lead->name }}')">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bx bx-user-x fs-1 d-block mb-2"></i>
                                        Nenhum Lead encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($leads->hasPages())
            <div class="card-footer bg-transparent">
                {{ $leads->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="createLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('crm.leads.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Novo Lead Rápido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nome *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Celular/WhatsApp</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Origem</label>
                            <select name="source" class="form-select">
                                <option value="store">Loja Física</option>
                                <option value="instagram">Instagram</option>
                                <option value="site">Site</option>
                                <option value="referral">Indicação</option>
                                <option value="architect">Arquiteto</option>
                            </select>
                        </div>
                        <div class="col-6">
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
                    <button type="submit" class="btn btn-primary"><i class="bx bx-check me-1"></i> Salvar Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('page-script')
<script>
function delLead(id, name) {
    Swal.fire({
        title: 'Você tem certeza?',
        text: "O lead '" + name + "' será excluído permanentemente!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DE0802',
        cancelButtonColor: '#1F2A44',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('del-' + id).submit();
        }
    });
}
</script>
@endsection
