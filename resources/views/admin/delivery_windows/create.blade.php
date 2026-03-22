@extends('layouts/contentNavbarLayout')

@section('title', __('Cadastro de Janela de Entrega'))
 
@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl"> 
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Cadastro de Janela de Entrega') }}</h5>
            </div> 
            <div class="card-body">
                <form action="{{ route('delivery_windows.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="start_time" class="form-label">{{ __('Hora de Início') }} <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="end_time" class="form-label">{{ __('Hora de Fim') }} <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                            @error('end_time')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="day_of_week" class="form-label">{{ __('Dia da Semana') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="day_of_week" name="day_of_week" required>
                                <option value="">{{ __('Selecione o Dia') }}</option>
                                <option value="1" {{ old('day_of_week') == '1' ? 'selected' : '' }}>{{ __('Domingo') }}</option>
                                <option value="2" {{ old('day_of_week') == '2' ? 'selected' : '' }}>{{ __('Segunda-feira') }}</option>
                                <option value="3" {{ old('day_of_week') == '3' ? 'selected' : '' }}>{{ __('Terça-feira') }}</option>
                                <option value="4" {{ old('day_of_week') == '4' ? 'selected' : '' }}>{{ __('Quarta-feira') }}</option>
                                <option value="5" {{ old('day_of_week') == '5' ? 'selected' : '' }}>{{ __('Quinta-feira') }}</option>
                                <option value="6" {{ old('day_of_week') == '6' ? 'selected' : '' }}>{{ __('Sexta-feira') }}</option>
                                <option value="7" {{ old('day_of_week') == '7' ? 'selected' : '' }}>{{ __('Sábado') }}</option>
                            </select>
                            @error('day_of_week')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="status" name="status" required>
                                <option value="Ativo" {{ old('status') == 'Ativo' ? 'selected' : '' }}>{{ __('Ativo') }}</option>
                                <option value="Inativo" {{ old('status') == 'Inativo' ? 'selected' : '' }}>{{ __('Inativo') }}</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">{{ __('Salvar') }}</button>
                        <a href="{{ route('delivery_windows.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="module">
  $(function() {
    });
</script>
@endsection