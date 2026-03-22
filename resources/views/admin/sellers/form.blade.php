@extends('layouts/contentNavbarLayout')

@section('title', ($isEdit ? 'Editar' : 'Novo') . ' Vendedor')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">CRM / Vendedores /</span> {{ $isEdit ? 'Editar' : 'Novo' }}
</h4>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header">{{ $isEdit ? 'Editar Informações' : 'Cadastrar Vendedor' }}</h5>
            <div class="card-body">
                <form action="{{ $isEdit ? route('crm.sellers.update', $seller->id) : route('crm.sellers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($isEdit) @method('PUT') @endif
                    
                    <div class="row">
                        <!-- Dados Pessoais -->
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Nome Completo</label>
                            <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $seller->name) }}" required autofocus />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">E-mail (Login)</label>
                            <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $seller->email) }}" placeholder="vendedor@bassani.com.br" required />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="cpf" class="form-label">CPF</label>
                            <input class="form-control" type="text" id="cpf" name="cpf" value="{{ old('cpf', $seller->cpf) }}" placeholder="000.000.000-00" required />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="phone" class="form-label">WhatsApp / Telefone</label>
                            <input class="form-control" type="text" id="phone" name="phone" value="{{ old('phone', $seller->phone) }}" placeholder="(00) 00000-0000" />
                        </div>
                        
                        <!-- Comissão e Status -->
                        <div class="mb-3 col-md-6">
                            <label for="commission_percentage" class="form-label">Porcentagem de Comissão (%)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" id="commission_percentage" name="commission_percentage" value="{{ old('commission_percentage', $seller->commission_percentage) }}" required />
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="status" class="form-label">Status do Vendedor</label>
                            <select id="status" name="status" class="form-select">
                                <option value="active" {{ old('status', $seller->status) === 'active' ? 'selected' : '' }}>Ativo</option>
                                <option value="inactive" {{ old('status', $seller->status) === 'inactive' ? 'selected' : '' }}>Inativo</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-12">
                            <label for="photo" class="form-label">Foto de Perfil</label>
                            <input class="form-control" type="file" id="photo" name="photo" />
                            @if($seller->photo)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($seller->photo) }}" alt="Current Photo" class="d-block rounded" height="60" width="60">
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-4">Segurança (Login)</h5>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="password" class="form-label">Senha {{ $isEdit ? '(deixe em branco para não alterar)' : '' }}</label>
                            <input class="form-control" type="password" id="password" name="password" {{ $isEdit ? '' : 'required' }} />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                            <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" {{ $isEdit ? '' : 'required' }} />
                        </div>
                    </div>
                    
                    <div class="mt-4 text-end">
                        <a href="{{ route('crm.sellers.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Salvar Vendedor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
