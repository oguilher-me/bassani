@extends('layouts/contentNavbarLayout')

@section('title', __('Editar Montador'))

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>

@endsection


@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Editar Montador') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('assemblers.update', $assembler->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">{{ __('Nome Completo') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $assembler->name) }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $assembler->email) }}" required>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="cpf" class="form-label">{{ __('CPF') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control cpf" id="cpf" name="cpf" value="{{ old('cpf', $assembler->cpf) }}" maxlength="14">
                            @error('cpf')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="phone" class="form-label">{{ __('Telefone') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control phone-mask" id="phone" name="phone" value="{{ old('phone', $assembler->phone) }}">
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="type" class="form-label">{{ __('Tipo') }} <span class="text-danger">*</span></label>
                           <select class="form-select select2" id="type" name="type" required>
                                <option value="">{{ __('Selecione o Tipo') }}</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->value }}" {{ old('type', $assembler->type->value) == $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                                @endforeach
                            </select>

                            
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="password" class="form-label">{{ __('Senha') }}</label>
                            <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="password_confirmation" class="form-label">{{ __('Confirme a Senha') }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="address" class="form-label">{{ __('Endereço') }}</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $assembler->address) }}">
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="city" class="form-label">{{ __('Cidade') }}</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $assembler->city) }}">
                            @error('city')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="state" class="form-label">{{ __('Estado') }}</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $assembler->state) }}">
                            @error('state')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="photo" class="form-label">{{ __('Foto') }}</label>
                            <input type="file" class="form-control" id="photo" name="photo">
                            @error('photo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @if ($assembler->photo)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($assembler->photo) }}" alt="{{ $assembler->name }}" class="img-thumbnail" width="100">
                                </div>
                            @endif
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="status" class="form-label">{{ __('Status') }}</label>
                            <select class="form-select" id="status" name="status">
                                <option value="1" {{ old('status', $assembler->status) == '1' ? 'selected' : '' }}>{{ __('Ativo') }}</option>
                                <option value="0" {{ old('status', $assembler->status) == '0' ? 'selected' : '' }}>{{ __('Inativo') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">{{ __('Atualizar') }}</button>
                        <a href="{{ route('assemblers.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection