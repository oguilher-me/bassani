@extends('layouts/contentNavbarLayout')

@section('title', 'Documentos - ' . $driver->full_name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Documentos de {{ $driver->full_name }}</h5>
                <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn-primary">
                    <i class="bx bx-arrow-back me-1"></i> Voltar para Edição
                </a>
            </div>
        </div>
    </div>
</div>

@include('admin.drivers.documents._section', ['driver' => $driver, 'documents' => $documents])
@endsection
