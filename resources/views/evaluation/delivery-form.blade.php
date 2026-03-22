@extends('layouts/public')

@section('title', __('Avalie sua Entrega'))

@section('content')
<div class="row">
  <div class="col-xl-6 col-lg-8 col-md-10 mx-auto">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">{{ __('Avalie sua experiência (NPS)') }}</h5>
      </div>
      <div class="card-body">
        <div class="mb-4">
          <div class="border rounded p-3">
            <div class="mb-2"><strong>{{ __('Pedido:') }}</strong> {{ optional(optional($evaluation->destination->items->first())->sale)->erp_code ?? 'N/A' }}</div>
            <div class="mb-2"><strong>{{ __('Endereço:') }}</strong> {{ $evaluation->destination->address }}</div>
            <div class="mb-2"><strong>{{ __('Motorista:') }}</strong> {{ optional($evaluation->destination->plannedShipment->driver)->full_name ?? optional($evaluation->destination->plannedShipment->driver)->name }}</div>
            <div class="mb-2"><strong>{{ __('Itens:') }}</strong>
              <ul class="mb-0">
                @forelse ($evaluation->destination->items as $item)
                  <li>{{ $item->product->name ?? 'Produto' }} ({{ ceil($item->quantity ?? 0) }})</li>
                @empty
                  <li>{{ __('N/A') }}</li>
                @endforelse
              </ul>
            </div>
          </div>
        </div>

        <form action="{{ route('delivery-evaluation.submit', $evaluation->token) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label class="form-label">{{ __('Em uma escala de 0 a 10, qual a probabilidade de você recomendar nossos serviços?') }}</label>
            <div class="d-flex flex-wrap gap-2">
              @for ($i = 0; $i <= 10; $i++)
                <label class="btn btn-outline-secondary">
                  <input type="radio" name="nps_score" value="{{ $i }}" required> {{ $i }}
                </label>
              @endfor
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">{{ __('Comentários (opcional)') }}</label>
            <textarea name="comments" class="form-control" rows="4"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">{{ __('Fotos (opcional)') }}</label>
            <input type="file" name="photos[]" accept="image/*" multiple class="form-control">
          </div>

          <button type="submit" class="btn btn-primary">{{ __('Enviar Avaliação') }}</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
