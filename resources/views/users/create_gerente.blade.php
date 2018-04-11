@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Crear cuenta</div>

                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }} <a href="{{ route('users.index') }}" class="btn btn-link">Volver</a>
                        </div>
                    @endif
                    @if(isset($errors) && count($errors->all()) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="md-form form-sm">
                                        <input id="name" type="text"
                                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               name="name"
                                               value="{{ old('name') }}" required autofocus>
                                        <label for="name">Nombre(s)</label>
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="md-form form-sm">
                                        <input id="last_name" type="text"
                                               class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                                               name="last_name" value="{{ old('last_name') }}" required>
                                        <label for="last_name">Apellidos</label>
                                        @if ($errors->has('last_name'))
                                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="md-form form-sm">
                                        <input id="email" type="email"
                                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                               name="email"
                                               value="{{ old('email') }}" required>
                                        <label for="email">Correo electrónico</label>
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="md-form form-sm">
                                        <input id="phone" type="text"
                                               class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                               name="phone"
                                               value="{{ old('phone') }}" required>
                                        <label for="phone">Teléfono</label>
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="md-form form-sm">
                                        <input id="password" type="password"
                                               class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                               name="password" required>
                                        <label for="password">Contraseña</label>
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="md-form form-sm">
                                        <input id="password-confirm" type="password" class="form-control"
                                               name="password_confirmation" required>
                                        <label for="password-confirm">Confirmar
                                            Contraseña</label>
                                    </div>
                                </div>

                                <input type="hidden" name="brand" id="brand" value="{{ \Illuminate\Support\Facades\Auth::user()->brand_id }}">

                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="rol" class="col-form-label text-md-right">Permisos</label>

                                        <select name="rol" id="rol" class="form-control">
                                            <option selected disabled>Selecciona una opción</option>
                                            <option value="1">Gerente</option>
                                            <option value="2">Supervisor</option>
                                            <option value="3">Embajador</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-2">
                                            <button type="submit" class="btn btn-block btn-outline-primary">
                                                Crear cuenta
                                            </button>
                                        </div>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
