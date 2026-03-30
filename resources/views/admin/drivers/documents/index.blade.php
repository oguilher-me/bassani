@extends('layouts/contentNavbarLayout')

@section('title', __('Documentos -') . ' ' . $driver->full_name)

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Documentos') }} - {{ $driver->full_name }}</h4>
        <p class="text-muted mb-0">{{ __('Gerenciamento de documentos do motorista') }}</p>
    </div>
    <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar para Edição') }}
    </a>
</div>

@include('admin.drivers.documents._section', ['driver' => $driver, 'documents' => $documents])
@endsection
