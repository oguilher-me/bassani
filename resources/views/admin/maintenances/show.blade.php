@extends('layouts/contentNavbarLayout')

@section('title', __('Detalhes da Manutenção'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Detalhes da Manutenção') }}</h5>
                <a href="{{ route('maintenances.index') }}" class="btn btn-primary">{{ __('Voltar para Manutenções') }}</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <p class="card-text"><strong>{{ __('Veículo') }}:</strong> {{ $maintenance->vehicle->placa }} - {{ $maintenance->vehicle->modelo }}</p>
                        <p class="card-text"><strong>{{ __('Tipo') }}:</strong> {{ $maintenance->type }}</p>
                        <p class="card-text"><strong>{{ __('Data da Manutenção') }}:</strong> {{ \Carbon\Carbon::parse($maintenance->maintenance_date)->format('d/m/Y') }}</p>
                        <p class="card-text"><strong>{{ __('Quilometragem') }}:</strong> {{ number_format($maintenance->mileage, 0, ',', '.') }} KM</p>
                        <p class="card-text"><strong>{{ __('Custo') }}:</strong> R$ {{ number_format($maintenance->cost, 2, ',', '.') }}</p>
                    </div>
                    <div class="mb-3 col-md-6">
                        <p class="card-text"><strong>{{ __('Fornecedor') }}:</strong> {{ $maintenance->supplier ?? 'N/A' }}</p>
                        <p class="card-text"><strong>{{ __('Status') }}:</strong>
                            @if ($maintenance->status == 'Concluída')
                                <span class="badge bg-label-success">{{ __('Concluída') }}</span>
                            @elseif ($maintenance->status == 'Em execução')
                                <span class="badge bg-label-warning">{{ __('Em execução') }}</span>
                            @elseif ($maintenance->status == 'Agendada')
                                <span class="badge bg-label-info">{{ __('Agendada') }}</span>
                            @else
                                <span class="badge bg-label-danger">{{ __('Cancelada') }}</span>
                            @endif
                        </p>
                        <p class="card-text"><strong>{{ __('Descrição') }}:</strong> {{ $maintenance->description ?? 'N/A' }}</p>
                        <p class="card-text"><strong>{{ __('Observações') }}:</strong> {{ $maintenance->observations ?? 'N/A' }}</p>
                    </div>
                </div>
                @if ($maintenance->service_proof)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <p class="card-text"><strong>{{ __('Comprovante de Serviço') }}:</strong>
                                <a href="{{ Storage::url($maintenance->service_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary">{{ __('Visualizar Comprovante') }}</a>
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection