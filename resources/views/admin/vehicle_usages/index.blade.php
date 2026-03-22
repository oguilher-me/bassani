@extends('layouts/contentNavbarLayout')

@section('title', __('Controle de Uso de Veículos'))

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card"> 
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Controle de Uso de Veículos') }}</h5>
                <a href="{{ route('vehicle_usages.create') }}" class="btn btn-success mb-3"><i class="icon-base bx bx-plus icon-sm text-white"></i> {{ __('Registrar Novo Uso') }}</a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="vehicle-usages-table">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Veículo') }}</th>
                                <th>{{ __('Motorista') }}</th>
                                <th>{{ __('Saída') }}</th>
                                <th>{{ __('Retorno') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center" style="width: 5%;">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($vehicleUsages as $vehicleUsage)
                                <tr>
                                    <td>{{ $vehicleUsage->id }}</td>
                                    <td>{{ $vehicleUsage->vehicle->brand }} {{ $vehicleUsage->vehicle->model }} ({{ $vehicleUsage->vehicle->plate }})</td>
                                    <td>{{ $vehicleUsage->driver->full_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($vehicleUsage->departure_date)->format('d/m/Y H:i') }} ({{ $vehicleUsage->departure_mileage }} km)</td>
                                    <td>
                                        @if($vehicleUsage->return_date)
                                            {{ \Carbon\Carbon::parse($vehicleUsage->return_date)->format('d/m/Y H:i') }} ({{ $vehicleUsage->return_mileage }} km)
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($vehicleUsage->trip_status == 'Em andamento')
                                            <span class="badge bg-warning">{{ $vehicleUsage->trip_status }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $vehicleUsage->trip_status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('vehicle_usages.show', $vehicleUsage->id) }}" class="btn btn-icon item-show"><i class="icon-base bx bx-show icon-sm text-primary"></i></a>
                                        <a href="{{ route('vehicle_usages.edit', $vehicleUsage->id) }}" class="btn btn-icon item-edit"><i class="icon-base bx bx-edit icon-sm text-warning"></i></a>
                                        <form action="{{ route('vehicle_usages.destroy', $vehicleUsage->id) }}" method="POST" class="d-inline delete-form">
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
        $('#vehicle-usages-table').DataTable({
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