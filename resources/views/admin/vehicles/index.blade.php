@extends('layouts/contentNavbarLayout')

@section('title', __('Gerenciamento de Veículos'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card"> 
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Veículos') }}</h5>
                <a href="{{ route('vehicles.create') }}" class="btn btn-success mb-3"><i class="icon-base bx bx-plus icon-sm text-white"></i> {{ __('Adicionar Novo Veículo') }}</a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="vehicles-table">
                        <thead>
                            <tr>
                                <th>{{ __('Placa') }}</th>
                                <th>{{ __('Modelo') }}</th>
                                <th class="text-center">{{ __('Marca') }}</th>
                                <th class="text-center">{{ __('KM Atual') }}</th>
                                <th class="text-center">{{ __('Cap. Cúbica') }}</th>
                                <th class="text-center">{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Data de Aquisição') }}</th>
                                <th class="text-center" style="width: 5%;">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($vehicles as $vehicle)
                                <tr>
                                    <td>{{ $vehicle->placa }}</td>
                                    <td>{{ $vehicle->modelo }}</td>
                                    <td class="text-center">{{ $vehicle->carBrand->name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $vehicle->quilometragem_atual }} KM</td>
                                    <td class="text-center">
                                        @if($vehicle->cubic_capacity !== null)
                                            <span title="Capacidade Cúbica">
                                                {{ number_format($vehicle->cubic_capacity, 2, ',', '.') }} m³
                                            </span>
                                        @else
                                            <span class="text-muted">&#8212;</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $vehicle->status }}</td>
                                    <td class="text-center">{{ $vehicle->data_aquisicao }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('vehicles.show', $vehicle->id) }}" class="btn btn-icon item-show"><i class="icon-base bx bx-show icon-sm text-primary"></i></a>
                                        <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-icon item-edit"><i class="icon-base bx bx-edit icon-sm text-warning"></i></a>
                                        <a href="{{ route('vehicle_usages.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-icon item-usage"><i class="icon-base bx bx-car icon-sm text-info"></i></a>
                                        <a href="{{ route('fuel_ups.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-icon item-fuel-up"><i class="icon-base bx bxs-gas-pump icon-sm text-success"></i></a>
                                        <a href="{{ route('fleet_report.vehicle_detailed_report', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-icon item-report"><i class="icon-base bx bx-chart icon-sm text-primary"></i></a>
                                        <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" class="d-inline delete-form">
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
    </div>

</div>
@endsection

@section('page-script')
<script type="module">
    $(document).ready(function() {
        $('#vehicles-table').DataTable({
            language: {
                url: '{{ asset('assets/js/datatables/pt-BR.json') }}'
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