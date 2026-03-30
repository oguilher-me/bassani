@extends('layouts/contentNavbarLayout')

@section('title', __('Motoristas'))

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Motoristas') }}</h4>
        <p class="text-muted mb-0">{{ __('Gerenciamento da equipe de motoristas') }}</p>
    </div>
    <a href="{{ route('drivers.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> {{ __('Novo Motorista') }}
    </a>
</div>

{{-- CNH Alert --}}
@if($expiredCnhDrivers->isNotEmpty())
    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <div class="avatar rounded-circle bg-label-warning d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                <i class="bx bx-error"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-1 fw-semibold">{{ __('Atenção! CNH Vencida') }}</h6>
                <p class="mb-0 small">{{ __('Existem motoristas ativos com CNH vencida:') }} 
                    @foreach($expiredCnhDrivers as $driver)
                        <strong>{{ $driver->full_name }}</strong> ({{ \Carbon\Carbon::parse($driver->cnh_expiration_date)->format('d/m/Y') }}){{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Data Table Card --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table display" id="drivers-table">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4">{{ __('Nome') }}</th>
                        <th class="py-3">{{ __('CNH') }}</th>
                        <th class="py-3">{{ __('Vencimento CNH') }}</th>
                        <th class="py-3">{{ __('Status') }}</th>
                        <th class="py-3 text-center">{{ __('Ações') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    {{-- DataTables will populate this tbody --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<style>
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #DE0802 !important;
        border-color: #DE0802 !important;
        color: #fff !important;
        border-radius: 4px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f5f5f5 !important;
        border-color: #DE0802 !important;
        color: #DE0802 !important;
        border-radius: 4px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #ccc !important;
    }
</style>
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
            },
            order: [[0, 'asc']],
            dom: '<"row align-items-center"<"col-sm-6"l><"col-sm-6"f>>rt<"row align-items-center"<"col-sm-6"i><"col-sm-6"p>>',
            pageLength: 10
        });

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DE0802',
                cancelButtonColor: '#1F2A44',
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
