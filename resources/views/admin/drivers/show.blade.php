@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes do Motorista'))

@section('content')

<div class="row mb-6 gy-6" style="margin-bottom: 10px !important;">
    <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-0">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Detalhes do Motorista') }}</h5>
                <a href="{{ route('drivers.index') }}" class="btn btn-primary">{{ __('Voltar') }}</a>
            </div> 
        </div>
    </div>
</div>
<div class="row mb-6 gy-6">
    <!-- Left Column: Driver Details -->
    <div class="col-xl-8 col-lg-8 col-md-8 order-0 order-md-0">
        <!-- About User Card -->
        <div class="card mb-4">

            <div class="card-body">
                <small class="card-text text-uppercase">{{ __('Informações do Motorista') }}</small>
                <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-user"></i><span class="fw-medium mx-2">{{ __('Nome Completo:') }}</span>
                        <span>{{ $driver->full_name }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-id-card"></i><span class="fw-medium mx-2">{{ __('CPF:') }}</span>
                        <span>{{ $driver->cpf ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $driver->cpf) : '-' }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-credit-card"></i><span class="fw-medium mx-2">{{ __('Número CNH:') }}</span>
                        <span>{{ $driver->cnh_number }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-category"></i><span class="fw-medium mx-2">{{ __('Categoria CNH:') }}</span>
                        <span>{{ $driver->cnh_category }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar"></i><span class="fw-medium mx-2">{{ __('Validade CNH:') }}</span>
                        <span>{{ $driver->cnh_expiration_date ? \Carbon\Carbon::parse($driver->cnh_expiration_date)->format('d/m/Y') : '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /About User Card -->
    </div>
    <!--/ Left Column: Driver Details -->

    <!-- Right Column: Activity Timeline -->
    <div class="col-xl-4 col-lg-4 col-md-4 order-1 order-md-1">
        <div class="card card-action mb-4">
            
            <div class="card-header align-items-center">
                
                <div class="card-action-element">
                    <small class="card-text text-uppercase">{{ __('Contatos') }}</small>
                <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-phone"></i><span class="fw-medium mx-2">{{ __('Contato:') }}</span>
                        <a style="color: #696cff" href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $driver->phone) }}?text=Ol%C3%A1%20{{ urlencode(data_get(explode(' ', $driver->full_name), '0', '')) }}" target="_blank">{{ $driver->phone }}</a>
                    </li>

                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-envelope"></i><span class="fw-medium mx-2">{{ __('Email:') }}</span>
                        <span>{{ $driver->user ? $driver->user->email : '-' }}</span>
                    </li>
                </ul>
                <small class="card-text text-uppercase mt-5">{{ __('Outras Informações') }}</small>
                <ul class="list-unstyled mb-4 mt-3">
                     <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-check"></i><span class="fw-medium mx-2">{{ __('Status:') }}</span>
                        @if($driver->status == 'Ativo')
                            <span class="badge bg-label-success">{{ $driver->status }}</span>
                        @elseif($driver->status == 'Inativo')
                            <span class="badge bg-label-danger">{{ $driver->status }}</span>
                        @else
                            <span class="badge bg-label-warning">{{ $driver->status }}</span>
                        @endif
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar"></i><span class="fw-medium mx-2">{{ __('Criado em:') }}</span>
                        <span>{{ $driver->created_at ? $driver->created_at->format('d/m/Y H:i:s') : '-' }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar-check"></i><span class="fw-medium mx-2">{{ __('Atualizado em:') }}</span>
                        <span>{{ $driver->updated_at ? $driver->updated_at->format('d/m/Y H:i:s') : '-' }}</span>
                    </li>
                </ul>
                </div>
            </div>
            
        </div>
    </div>
    <!--/ Right Column: Activity Timeline -->
</div>

{{-- ═══ Documents Section (Read-Only) ═══ --}}
<div class="row mb-6 gy-6">
    <div class="col-12">
        @php
            $documents = $driver->documents()->latest()->get();
        @endphp

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="bx bx-folder-open me-2 text-primary"></i>
                    Documentos Digitalizados
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-label-primary">{{ $documents->count() }} doc(s)</span>
                    <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-upload me-1"></i> Gerenciar
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($documents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:40px"></th>
                                <th>Descrição</th>
                                <th>Categoria</th>
                                <th>Validade</th>
                                <th>Enviado em</th>
                                <th style="width:80px">Ver</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $doc)
                                @php
                                    $isExpired      = $doc->expires_at && $doc->expires_at->isPast();
                                    $isExpiringSoon = $doc->expires_at && !$isExpired
                                                     && $doc->expires_at->diffInDays(\Carbon\Carbon::today()) <= 30;
                                    $rowCls = $isExpired ? 'table-danger' : ($isExpiringSoon ? 'table-warning' : '');
                                @endphp
                                <tr class="{{ $rowCls }}">
                                    <td class="text-center">
                                        @if($doc->file_type === 'pdf')
                                            <i class="bx bxs-file-pdf fs-4 text-danger"></i>
                                        @else
                                            <i class="bx bxs-image fs-4 text-info"></i>
                                        @endif
                                    </td>
                                    <td><span class="fw-semibold">{{ $doc->description }}</span></td>
                                    <td><span class="badge bg-label-secondary">{{ $doc->category }}</span></td>
                                    <td>
                                        @if(!$doc->expires_at)
                                            <span class="text-muted small">—</span>
                                        @elseif($isExpired)
                                            <span class="badge bg-danger">Vencido {{ $doc->expires_at->format('d/m/Y') }}</span>
                                        @elseif($isExpiringSoon)
                                            <span class="badge bg-warning text-dark">Vence {{ $doc->expires_at->format('d/m/Y') }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $doc->expires_at->format('d/m/Y') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ $doc->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($doc->file_type === 'image')
                                            <button type="button"
                                                    class="btn btn-icon btn-sm btn-outline-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#showImgModal"
                                                    data-src="{{ asset('storage/' . $doc->file_path) }}"
                                                    data-caption="{{ $doc->description }}">
                                                <i class="bx bx-show"></i>
                                            </button>
                                        @else
                                            <a href="{{ asset('storage/' . $doc->file_path) }}"
                                               target="_blank" class="btn btn-icon btn-sm btn-outline-info">
                                                <i class="bx bx-show"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="text-center py-3 text-muted">
                        <i class="bx bx-folder-open fs-1 d-block opacity-50 mb-1"></i>
                        Nenhum documento cadastrado.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Image Modal for show view --}}
<div class="modal fade" id="showImgModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="showImgCaption">Visualizar Imagem</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-2">
                <img id="showImgSrc" src="" alt="" class="img-fluid rounded" style="max-height:80vh;object-fit:contain">
            </div>
            <div class="modal-footer justify-content-between">
                <a id="showImgLink" href="#" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="bx bx-external-link me-1"></i> Abrir em nova aba
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<script>
(function(){
    const m = document.getElementById('showImgModal');
    if(m){
        m.addEventListener('show.bs.modal', function(e){
            const btn = e.relatedTarget;
            const src = btn.dataset.src;
            document.getElementById('showImgSrc').src = src;
            document.getElementById('showImgLink').href = src;
            document.getElementById('showImgCaption').textContent = btn.dataset.caption || 'Imagem';
        });
        m.addEventListener('hidden.bs.modal',function(){ document.getElementById('showImgSrc').src=''; });
    }
})();
</script>

@endsection