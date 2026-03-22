@extends('layouts/contentNavbarLayout')

@section('title', 'Perfil do Vendedor - ' . $seller->name)

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">CRM / Vendedores /</span> Detalhes do Perfil
</h4>

<div class="row">
  <!-- User Card -->
  <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
    <div class="card mb-4">
      <div class="card-body">
        <div class="user-avatar-section">
          <div class="d-flex align-items-center flex-column">
            @if($seller->photo)
                <img class="img-fluid rounded my-4" src="{{ Storage::url($seller->photo) }}" height="110" width="110" alt="User avatar" />
            @else
                <div class="avatar avatar-xl my-4">
                    <span class="avatar-initial rounded bg-label-primary fs-2">{{ strtoupper(substr($seller->name, 0, 2)) }}</span>
                </div>
            @endif
            <div class="user-info text-center">
              <h4 class="mb-2">{{ $seller->name }}</h4>
              <span class="badge bg-label-primary">Consultor Comercial</span>
            </div>
          </div>
        </div>
        
        <h5 class="pb-2 border-bottom mb-4">Detalhes de Contato</h5>
        <div class="info-container">
          <ul class="list-unstyled">
            <li class="mb-3">
              <span class="fw-bold me-2">E-mail:</span>
              <span>{{ $seller->email }}</span>
            </li>
            <li class="mb-3">
              <span class="fw-bold me-2">Status:</span>
              <span class="badge bg-label-{{ $seller->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($seller->status) }}</span>
            </li>
            <li class="mb-3">
              <span class="fw-bold me-2">Telefone:</span>
              <span>{{ $seller->phone ?? 'Não informado' }}</span>
            </li>
            <li class="mb-3">
              <span class="fw-bold me-2">Comissão:</span>
              <span class="text-success fw-bold">{{ number_format($seller->commission_percentage, 2) }}%</span>
            </li>
          </ul>
          <div class="d-flex justify-content-center pt-3">
            <a href="{{ route('crm.sellers.edit', $seller->id) }}" class="btn btn-primary me-3">Editar Perfil</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ User Card -->

  <!-- User Content -->
  <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
    <!-- Pills -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Taxa de Conversão</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">{{ round($seller->conversion_rate) }}%</h4>
                            </div>
                            <small>Soma de Oportunidades Ganhas</small>
                        </div>
                        <span class="badge bg-label-info rounded p-2">
                            <i class="bx bx-trending-up bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Ticket Médio</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">R$ {{ number_format($seller->average_ticket, 0, ',', '.') }}</h4>
                            </div>
                            <small>Base: Vendas Ganhas</small>
                        </div>
                        <span class="badge bg-label-success rounded p-2">
                            <i class="bx bx-dollar bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Leads em Aberto</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">{{ $seller->open_leads_count }}</h4>
                            </div>
                            <small>Aguardando conversão</small>
                        </div>
                        <span class="badge bg-label-warning rounded p-2">
                            <i class="bx bx-user-voice bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Opportunities List -->
    <div class="card mb-4">
      <h5 class="card-header">Oportunidades Ativas (Últimas 10)</h5>
      <div class="table-responsive text-nowrap">
        <table class="table border-top">
          <thead>
            <tr>
              <th>Título</th>
              <th>Valor Est.</th>
              <th>Etapa</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
              @forelse($seller->opportunities as $opp)
              <tr>
                  <td><span class="fw-semibold">{{ $opp->title }}</span></td>
                  <td>R$ {{ number_format($opp->estimated_value, 2, ',', '.') }}</td>
                  <td><span class="badge bg-label-primary">{{ $opp->stage->name ?? 'N/A' }}</span></td>
                  <td>
                      <a href="{{ route('crm.opportunities.show', $opp->id) }}" class="btn btn-sm btn-icon"><i class="bx bx-show"></i></a>
                  </td>
              </tr>
              @empty
              <tr>
                  <td colspan="4" class="text-center py-4">Nenhuma oportunidade ativa no momento.</td>
              </tr>
              @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!--/ User Content -->
</div>
@endsection
