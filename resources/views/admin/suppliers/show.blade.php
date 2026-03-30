@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes do Fornecedor'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Detalhes do Fornecedor') }}</h4>
        <p class="text-muted mb-0">{{ $supplier->company_name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-primary">
            <i class="bx bx-edit me-1"></i> {{ __('Editar') }}
        </a>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- Supplier Info Card --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted mb-4">
                    <i class="bx bx-building me-2 text-danger"></i>{{ __('Informações do Fornecedor') }}
                </h6>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx {{ $supplier->supplier_type == 'Pessoa Jurídica' ? 'bx-building' : 'bx-user' }}"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Razão Social / Nome') }}</small>
                                <span class="fw-semibold">{{ $supplier->company_name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-info d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-id-card"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('CNPJ / CPF') }}</small>
                                <span class="fw-semibold">{{ $supplier->document_number }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-category"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Tipo') }}</small>
                                <span class="fw-semibold">{{ $supplier->supplier_type }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle bg-label-secondary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bx bx-user"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('Pessoa de Contato') }}</small>
                                <span class="fw-semibold">{{ $supplier->contact_person ?? __('Não informado') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($supplier->address)
                <hr class="my-4">
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-map me-2 text-danger"></i>{{ __('Endereço') }}
                </h6>
                <p class="mb-0">{{ $supplier->address }}</p>
                @endif
                
                @if($supplier->services_offered)
                <hr class="my-4">
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-briefcase me-2 text-danger"></i>{{ __('Serviços Oferecidos') }}
                </h6>
                <p class="mb-0">{{ $supplier->services_offered }}</p>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Contact & Status Card --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                {{-- Status --}}
                <div class="text-center mb-4 pb-4 border-bottom">
                    <div class="mb-3">
                        @if($supplier->status == 'Ativo')
                            <span class="badge bg-success rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-check-circle me-1"></i>{{ __('Ativo') }}
                            </span>
                        @elseif($supplier->status == 'Inativo')
                            <span class="badge bg-danger rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-x-circle me-1"></i>{{ __('Inativo') }}
                            </span>
                        @else
                            <span class="badge bg-warning rounded-pill px-4 py-2 fs-6">
                                <i class="bx bx-pause-circle me-1"></i>{{ __('Suspenso') }}
                            </span>
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
                        <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $supplier->phone) }}?text=Olá" target="_blank" class="fw-semibold text-primary">{{ $supplier->phone }}</a>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-4">
                    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="bx bx-envelope text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('E-mail') }}</small>
                        <a href="mailto:{{ $supplier->email }}" class="fw-semibold">{{ $supplier->email }}</a>
                    </div>
                </div>
                
                <hr class="my-4">
                
                {{-- Timestamps --}}
                <h6 class="text-uppercase text-muted mb-3">
                    <i class="bx bx-time me-2 text-danger"></i>{{ __('Informações do Sistema') }}
                </h6>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">{{ __('Criado em') }}</span>
                    <span class="fw-semibold small">{{ $supplier->created_at ? $supplier->created_at->format('d/m/Y H:i') : '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">{{ __('Atualizado em') }}</span>
                    <span class="fw-semibold small">{{ $supplier->updated_at ? $supplier->updated_at->format('d/m/Y H:i') : '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">{{ __('Precisa fazer alterações?') }}</span>
                    <div class="d-flex gap-2">
                        <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $supplier->phone) }}?text=Olá" target="_blank" class="btn btn-success">
                            <i class="bx bxl-whatsapp me-1"></i> {{ __('Contatar via WhatsApp') }}
                        </a>
                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-outline-primary">
                            <i class="bx bx-edit me-1"></i> {{ __('Editar Fornecedor') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection