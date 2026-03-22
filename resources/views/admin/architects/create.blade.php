@extends('layouts.app')

@section('title', 'Novo Arquiteto')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">CRM / Arquitetos /</span> Novo Cadastro</h4>
    
    <form action="{{ route('crm.architects.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Dados Profissionais -->
            <div class="col-xl-6">
                <div class="card mb-4">
                    <h5 class="card-header">Dados Profissionais</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nome Completo</label>
                            <input type="text" name="name" class="form-control" required placeholder="Nome do profissional">
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tipo Doc.</label>
                                <select name="document_type" class="form-select" required>
                                    <option value="CAU">CAU</option>
                                    <option value="ABD">ABD</option>
                                    <option value="CREA">CREA</option>
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Número Documento</label>
                                <input type="text" name="document_number" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Especialidade</label>
                            <input type="text" name="specialty" class="form-control" placeholder="Ex: Interiores, Comercial">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Avaliação Inicial</label>
                            <input type="number" name="rating" class="form-control" min="1" max="5" value="3">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dados Financeiros e Sociais -->
            <div class="col-xl-6">
                <div class="card mb-4">
                    <h5 class="card-header">Financeiro e Redes</h5>
                    <div class="card-body">
                         <div class="mb-3">
                            <label class="form-label">Reserva Técnica (RT) Padrão (%)</label>
                            <input type="number" name="rt_percentage" class="form-control" step="0.01" value="0.00" placeholder="Ex: 5.00">
                        </div>
                        <hr class="my-3">
                        <h6 class="fw-normal">Dados Bancários</h6>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Banco</label>
                                <input type="text" name="bank_data[bank]" class="form-control">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Agência</label>
                                <input type="text" name="bank_data[agency]" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Conta Corrente</label>
                            <input type="text" name="bank_data[account]" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Chave PIX</label>
                            <input type="text" name="bank_data[pix]" class="form-control">
                        </div>
                        <hr class="my-3">
                        <h6 class="fw-normal">Redes Sociais</h6>
                        <div class="mb-3">
                            <label class="form-label">Instagram (@)</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bxl-instagram"></i></span>
                                <input type="text" name="social_links[instagram]" class="form-control" placeholder="@usuario">
                            </div>
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Portfolio (URL)</label>
                             <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-link"></i></span>
                                <input type="url" name="social_links[portfolio]" class="form-control" placeholder="https://...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end pb-4">
             <a href="{{ route('crm.architects.index') }}" class="btn btn-secondary me-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar Arquiteto</button>
        </div>
    </form>
</div>
@endsection
