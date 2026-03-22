@extends('layouts/contentNavbarLayout')

@section('title', __('Motoristas'))

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
@if($expiredCnhDrivers->isNotEmpty())
    <div class="alert alert-warning" role="alert">
        <h4 class="alert-heading">{{ __('Atenção!') }}</h4>
        <p class="mb-0">{{ __('Existem motoristas ativos com CNH vencida. Por favor, verifique a situação:') }}</p>
        <ul>
            @foreach($expiredCnhDrivers as $driver)
                <li>{{ $driver->full_name }} ({{ \Carbon\Carbon::parse($driver->cnh_expiration_date)->format('d/m/Y') }})</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Motoristas') }}</h5>
                <a href="{{ route('drivers.create') }}" class="btn btn-success mb-3"><i class="icon-base bx bx-plus icon-sm text-white"></i> {{ __('Adicionar Novo Motorista') }}</a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table display" id="drivers-table">
                        <thead>
                            <tr>
                                <th>{{ __('Nome') }}</th>
                                <th>{{ __('CNH') }}</th>
                                <th>{{ __('Vencimento CNH') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th style="width: 5%;">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            {{-- DataTables will populate this tbody --}}
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
        $('#drivers-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('drivers.data') }}",
            columns: [
                { data: 'full_name', name: 'full_name', orderable: true },
                { data: 'cnh_number', name: 'cnh_number', orderable: true },
                { data: 'cnh_expiration_date', name: 'cnh_expiration_date', orderable: true },
                { data: 'status', name: 'status', orderable: true },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            language: {
                url: '{{ asset('assets/js/datatables/pt-BR.json') }}'
            }
        });

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, inativar!',
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