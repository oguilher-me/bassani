@extends('layouts/public')

@section('title', __('Obrigado'))

@section('content')
<div class="row">
    <div class="col-xl-6 col-lg-8 col-md-10 mx-auto">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="mb-3">{{ __('Obrigado por sua avaliação!') }}</h5>
                <p>{{ __('Sua opinião é muito importante para melhorarmos continuamente nossos serviços.') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
