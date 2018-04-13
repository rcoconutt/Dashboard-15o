@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header row">
                        <div class="col-md-6 col-sm-12 text-center">
                            <a class="btn btn-sm btn-link btn-block waves-effect" href="/users">Volver</a>
                        </div>
                        <div class="col-md-6 col-sm-12 text-center align-middle">
                            <strong>Crear cuenta</strong>
                        </div>
                    </div>

                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }} <a href="{{ route('users.index') }}"
                                                        class="btn btn-link">Volver</a>
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
                    <div class="card-body col-10 offset-1">
                        <form method="POST" id="form" action="{{ route('users.store') }}">
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

                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="rol" class="col-form-label">Permisos</label>

                                            <select name="rol" id="rol" class="form-control">
                                                <option selected disabled>Selecciona una opción</option>
                                                <option value="1">Gerente</option>
                                                <option value="2">Supervisor</option>
                                                <option value="3">Embajador</option>
                                                <option value="4">Administrador Tickets</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group" style="display: none;" id="brandKind">
                                        <label class="col col-form-label text-md-right">
                                            <input type="radio" name="brand_kind" value="0">
                                            Crear nuevo brand
                                        </label>
                                        <label class="col col-form-label text-md-right">
                                            <input type="radio" name="brand_kind" value="1">
                                            Asignar a un brand
                                        </label>
                                    </div>
                                </div>

                                <div class="offset-md-6 col-md-6 col-sm-12" style="display: none;" id="new">
                                    <div class="md-form form-sm">
                                        <input id="new_brand" type="text" class="form-control" name="new_brand">
                                        <label for="new_brand">Nuevo brand</label>
                                    </div>
                                </div>

                                <div class="offset-md-6 col-md-6 col-sm-12" style="display: none;" id="use">
                                    <div class="col-md-12 col-sm-12">
                                        <label for="brand_id" class="col-form-label text-md-right">Asignar a brand</label>
                                    </div>

                                    <div class="col-md-12 col-sm-12">
                                        <select id="brand_id" class="form-control" name="brand_id">
                                            <option selected disabled>Selecciona una opción</option>
                                            @foreach(\App\Brand::all() as $brand)
                                                <option value="{{ $brand->ID_BRAND }}">{{ $brand->BRAND }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12"><br></div>

                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-2">
                                            <button type="submit" onclick="checkForm()" class="btn btn-block btn-outline-primary">
                                                Crear cuenta
                                            </button>
                                        </div>
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

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function (event) {
            $('input[type=radio][name=brand_kind]').change(function () {
                if (this.value == '0') {
                    $("#new").show();
                    $("#use").hide();
                }
                else if (this.value == '1') {
                    $("#new").hide();
                    $("#use").show();
                }
            });

            $('#rol').change(function () {
                if (this.value === '4' || this.value === 4) {
                    $("#brandKind").hide();
                } else {
                    $("#brandKind").show();
                }
            });
        });

        function checkForm() {
            event.preventDefault();

            let rol = $("#rol").val();
            if (rol) {
                $("#form").submit();
            } else {
                error("Selecciona los permisos del usuario")
            }
        }

        function error(message) {
            swal({
                title: "",
                text: message,
                icon: "error",
                button: "Entendido",
                timer: 3000
            });
        }
    </script>
@endpush
