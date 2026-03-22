@extends('layouts/contentNavbarLayout')

@section('title', __('Listagem de Abastecimentos'))

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">{{ __('Abastecimentos') }} /</span> {{ __('Listagem') }}</h4>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Abastecimentos') }}</h5>
        <a href="{{ route('fuel_ups.create') }}" class="btn btn-success mb-3"><i class="icon-base bx bx-plus icon-sm text-white"></i> {{ __('Registrar Novo Abastecimento') }}</a>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table" id="fuel-ups-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>{{ __('Veículo') }}</th>
                        <th>{{ __('Data') }}</th>
                        <th>{{ __('Combustível') }}</th>
                        <th>{{ __('Litros') }}</th>
                        <th>{{ __('Valor Total') }}</th>
                        <th>{{ __('KM Atual') }}</th>
                        <th>{{ __('KM Anterior') }}</th>
                        <th>{{ __('Distância Percorrida') }}</th>
                        <th>{{ __('Consumo (Km/L)') }}</th>
                        <th>{{ __('Custo por KM') }}</th>
                        <th class="text-center" style="width: 5%;">{{ __('Ações') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($fuelUps as $fuelUp)
                        <tr>
                            <td>{{ $fuelUp->vehicle->placa }} - {{ $fuelUp->vehicle->modelo }}</td>
                            <td>{{ \Carbon\Carbon::parse($fuelUp->fuel_up_date)->format('d/m/Y H:i') }}</td>
                            <td>{{ $fuelUp->fuel_type }}</td>
                            <td>{{ number_format($fuelUp->quantity, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($fuelUp->total_value, 2, ',', '.') }}</td>
                            <td>{{ number_format($fuelUp->current_km, 0, ',', '.') }}</td>
                            <td>{{ number_format($fuelUp->previous_km, 0, ',', '.') ?? 'N/A' }}</td>
                            <td>{{ number_format($fuelUp->distance_traveled, 2, ',', '.') ?? 'N/A' }}</td>
                            <td>{{ number_format($fuelUp->consumption_km_l, 2, ',', '.') ?? 'N/A' }}</td>
                            <td>R$ {{ number_format($fuelUp->cost_per_km, 2, ',', '.') ?? 'N/A' }}</td>
                            <td class="text-center">
                                <a href="{{ route('fuel_ups.show', $fuelUp->id) }}" class="btn btn-icon item-show"><i class="icon-base bx bx-show icon-sm text-primary"></i></a>
                                <a href="{{ route('fuel_ups.edit', $fuelUp->id) }}" class="btn btn-icon item-edit"><i class="icon-base bx bx-edit icon-sm text-warning"></i></a>
                                <form action="{{ route('fuel_ups.destroy', $fuelUp->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon item-delete"><i class="icon-base bx bx-trash icon-sm text-danger"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="module">
    $(document).ready(function() {
        $('#fuel-ups-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            }
        });

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: '{{ __('Tem certeza?') }}',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection