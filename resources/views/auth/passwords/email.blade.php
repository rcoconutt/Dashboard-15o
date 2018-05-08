@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body">

                        <!--Header-->
                            <p class="h4 text-center mb-4">Recuperar contraseña</p>

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="md-form col-md-8 offset-md-2">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                <label for="email"><i class="fa fa-envelope blue-text"></i> {{ __('Email') }}</label>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 col-sm-12 offset-md-4">
                                    <button class="btn btn-outline-info" type="submit">Restaurar contraseña<i class="fas fa-paper-plane ml-2"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
