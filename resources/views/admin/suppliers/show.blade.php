@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes do Fornecedor'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Detalhes do Fornecedor') }}</h5>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ __('Razão Social / Nome Fantasia') }}: {{ $supplier->company_name }}</h5>
                <p class="card-text">{{ __('CNPJ / CPF') }}: {{ $supplier->document_number }}</p>
                <p class="card-text">{{ __('Tipo de Fornecedor') }}: {{ $supplier->supplier_type }}</p>
                <p class="card-text">{{ __('Pessoa de Contato') }}: {{ $supplier->contact_person }}</p>
                <p class="card-text">{{ __('Telefone') }}: {{ $supplier->phone }}</p>
                <p class="card-text">{{ __('E-mail') }}: {{ $supplier->email }}</p>
                <p class="card-text">{{ __('Endereço Completo') }}: {{ $supplier->address }}</p>
                <p class="card-text">{{ __('Serviços Oferecidos') }}: {{ $supplier->services_offered }}</p>
                <p class="card-text">{{ __('Situação do Cadastro') }}:
                    @if ($supplier->status == 'Ativo')
                        <span class="badge bg-label-success">{{ __('Ativo') }}</span>
                    @elseif ($supplier->status == 'Inativo')
                        <span class="badge bg-label-danger">{{ __('Inativo') }}</span>
                    @else
                        <span class="badge bg-label-warning">{{ __('Suspenso') }}</span>
                    @endif
                </p>
                <a href="{{ route('suppliers.index') }}" class="btn btn-primary">{{ __('Voltar para Fornecedores') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection