@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes do Motorista'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Detalhes do Motorista') }}</h4>
        <p class="text-muted mb-0">{{ $driver->full_name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn-primary">
            <i class="bx bx-edit me-1"></i> {{ __('Editar') }}
        </a>
        <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- Driver Info Card --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted mb-4">
                    <i class="bx bx-user me-2 text-danger"></i>{{ __('Informações do Motorista') }}
                </h6>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-user"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Nome Completo') }}</small>
                                <span class="fw-semibold">{{ $driver->full_name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-id-card"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('CPF') }}</small>
                                <span class="fw-semibold">{{ $driver->cpf ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $driver->cpf) : '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-credit-card"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Número CNH') }}</small>
                                <span class="fw-semibold">{{ $driver->cnh_number }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-secondary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-category"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Categoria CNH') }}</small>
                                <span class="fw-semibold">{{ $driver->cnh_category }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-danger d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-calendar"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Validade CNH') }}</small>
                                <span class="fw-semibold">{{ $driver->cnh_expiration_date ? \Carbon\Carbon::parse($driver->cnh_expiration_date)->format('d/m/Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Contacts & Status Card --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                {{-- Status --}}
                <div class="text-center mb-4 pb-4 border-bottom">
                    <div class="mb-3">
                        @if($driver->status == 'Ativo')
                            <span class="badge bg-success rounded-pill px-3 py-2">{{ __('Ativo') }}</span>
                        @elseif($driver->status == 'Inativo')
                            <span class="badge bg-danger rounded-pill px-3 py-2">{{ __('Inativo') }}</span>
                        @else
                            <span class="badge bg-warning rounded-pill px-3 py-2">{{ __('Suspenso') }}</span>
                        @endif
                    </div>
                </div>
                
                {{-- Contacts --}}
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-phone me-2 text-danger"></i>{{ __('Contatos') }}
                </h6>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="bx bx-phone text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('Telefone') }}</small>
                        <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $driver->phone) }}?text=Olá" target="_blank" class="fw-semibold text-primary">{{ $driver->phone }}</a>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-4">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="bx bx-envelope text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('Email') }}</small>
                        <span class="fw-semibold">{{ $driver->user ? $driver->user->email : '-' }}</span>
                    </div>
                </div>
                
                <hr class="my-4">
                
                {{-- Timestamps --}}
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-time me-2 text-danger"></i>{{ __('Informações do Sistema') }}
                </h6>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">{{ __('Criado em') }}</span>
                    <span class="fw-semibold small">{{ $driver->created_at ? $driver->created_at->format('d/m/Y H:i') : '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">{{ __('Atualizado em') }}</span>
                    <span class="fw-semibold small">{{ $driver->updated_at ? $driver->updated_at->format('d/m/Y H:i') : '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Documents Section --}}
<div class="row mt-4">
    <div class="col-12">
        @php
            $documents = $driver->documents()->latest()->get();
        @endphp

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-folder-open text-danger me-2"></i>{{ __('Documentos Digitalizados') }}
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-label-primary">{{ $documents->count() }} {{ __('doc(s)') }}</span>
                    <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-upload me-1"></i> {{ __('Gerenciar') }}
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if($documents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 px-4" style="width:40px"></th>
                                    <th class="border-0 py-3">{{ __('Descrição') }}</th>
                                    <th class="border-0 py-3">{{ __('Categoria') }}</th>
                                    <th class="border-0 py-3">{{ __('Validade') }}</th>
                                    <th class="border-0 py-3">{{ __('Enviado em') }}</th>
                                    <th class="border-0 py-3 text-center">{{ __('Ver') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $doc)
                                    @php
                                        $isExpired      = $doc->expires_at && $doc->expires_at->isPast();
                                        $isExpiringSoon = $doc->expires_at && !$isExpired
                                                         && $doc->expires_at->diffInDays(\Carbon\Carbon::today()) <= 30;
                                    @endphp
                                    <tr class="{{ $isExpired ? 'table-danger' : ($isExpiringSoon ? 'table-warning' : '') }}">
                                        <td class="py-3 px-4 text-center">
                                            @if($doc->file_type === 'pdf')
                                                <i class="bx bxs-file-pdf fs-4 text-danger"></i>
                                            @else
                                                <i class="bx bxs-image fs-4 text-info"></i>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            <span class="fw-semibold">{{ $doc->description }}</span>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-label-secondary">{{ $doc->category }}</span>
                                        </td>
                                        <td class="py-3">
                                            @if(!$doc->expires_at)
                                                <span class="text-muted small">—</span>
                                            @elseif($isExpired)
                                                <span class="badge bg-danger">{{ __('Vencido') }} {{ $doc->expires_at->format('d/m/Y') }}</span>
                                            @elseif($isExpiringSoon)
                                                <span class="badge bg-warning text-dark">{{ __('Vence') }} {{ $doc->expires_at->format('d/m/Y') }}</span>
                                            @else
                                                <span class="badge bg-success">{{ $doc->expires_at->format('d/m/Y') }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-muted small">{{ $doc->created_at->format('d/m/Y') }}</td>
                                        <td class="py-3 text-center">
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
                    <div class="text-center py-5">
                        <i class="bx bx-folder-open fs-1 text-muted opacity-50"></i>
                        <p class="text-muted mt-2">{{ __('Nenhum documento cadastrado') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Image Modal --}}
<div class="modal fade" id="showImgModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h6 class="modal-title" id="showImgCaption">{{ __('Visualizar Imagem') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-2">
                <img id="showImgSrc" src="" alt="" class="img-fluid rounded" style="max-height:80vh;object-fit:contain">
            </div>
            <div class="modal-footer justify-content-between">
                <a id="showImgLink" href="#" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="bx bx-external-link me-1"></i> {{ __('Abrir em nova aba') }}
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('Fechar') }}</button>
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
