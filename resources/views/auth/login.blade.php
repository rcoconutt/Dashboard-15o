@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">

                    <!--Header-->
                    <p class="h4 text-center mb-4"> Iniciar sesión</p>

                    @if(isset($errors) && count($errors->all()) > 0)
                        <div class="alert alert-danger">
                            Usuario o contraseña incorrectos
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="md-form">
                            <input type="text" id="username" class="form-control" name="email" value="{{ old('email') }}" required>
                            <label for="username"><i class="fa fa-envelope blue-text"></i> {{ __('Email') }}</label>
                        </div>

                        <div class="md-form">
                            <input type="password" id="password" class="form-control" name="password" required>
                            <label for="password"><i class="fa fa-lock blue-text"></i> {{ __('Contraseña') }}</label>
                        </div>

                        <div class="form-check text-center my-4">
                            <input type="checkbox" name="remember" class="form-check-input"  id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="label-table grey-text"  for="remember">{{ __('Recordarme') }}</label>
                        </div>

                        <div class="text-center mt-4">
                            <button class="btn btn-outline-info" type="submit">Iniciar sesión<i class="fas fa-lock ml-2"></i></button>
                        </div>
                    </form>
                </div>

                <!--Footer-->
                <div class="modal-footer">
                    <div class="options">
                        <p>¿Olvidaste la contraseña?
                            <a href="{{ route('password.request') }}">reestablecer</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
