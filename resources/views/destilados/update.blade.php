@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="container">
                    <div class="card">
                        <div class="card-header row">
                            <div class="col-md-6 col-sm-12 text-center">
                                <a class="btn btn-sm btn-link btn-block waves-effect" href="/destilados">Volver</a>
                            </div>
                            <div class="col-md-6 col-sm-12 text-center">
                                <strong>Actualizar destilado</strong>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($errors->has('error') || count($errors) > 0)
                                <div class="alert alert-danger">
                                    <strong>{{ $errors->first() }}</strong>
                                </div>
                            @endif

                            @if (session('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }} <a href="{{ route('admin') }}" class="btn btn-link">Volver</a>
                                </div>
                            @endif

                            <form method="post" action="/destilados/{{ $destilado->ID_DESTILADO }}" enctype="multipart/form-data">
                                <input type="hidden" name="_method" value="put">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="md-form col-md-6">
                                        <input type="text" class="form-control" name="destilado" id="destilado" value="{{ $destilado->DESTILADO }}" placeholder=""
                                               required/>
                                        <label for="destilado">Nombre del destilado: </label>
                                    </div>
                                    <div class="md-form col-md-6">
                                        <input type="text" class="form-control" name="anejamiento" id="anejamiento" value="{{ $destilado->ANEJAMIENTO }}" placeholder=""
                                               required/>
                                        <label for="anejamiento">Nombre del añejamiento: </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="md-form col-md-6 text-center">
                                        <img src="data:image/jpeg;base64,{{ base64_encode($destilado->IMAGEN) }}" width="125px" id="content" height="125px" style="background-color: #1d2124; margin: 0 auto;"/>
                                    </div>
                                    <div class="col-md-6">
                                        <br>
                                        <input class="form-control" type="file" accept="image/*" name="logo" id="logo">
                                        <br>
                                        <div class="md-form">
                                            <input type="text" class="form-control" name="caracteristicas" id="caracteristicas" value="{{ $destilado->CARACTERISTICAS }}" placeholder=""
                                               required/>
                                            <label for="caracteristicas">Caracteristicas: </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 offset-md-8">
                                        <button class="btn btn-outline-primary waves-effect" id="save">Actualizar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let _URL = window.URL || window.webkitURL;
        document.addEventListener('DOMContentLoaded', function () {
            $("#logo").change(function () {
                let component = this;
                let photo = this.files[0];
                let maxSize = 2045728; // 2MB
                let mimes = ['image/png', 'image/jpeg', 'image/png'];

                let img = new Image();
                let imgwidth = 0;
                let imgheight = 0;
                let maxwidth = 480;
                let maxheight = 480;

                img.src = _URL.createObjectURL(photo);
                img.onload = function() {
                    imgwidth = this.width;
                    imgheight = this.height;

                    $("#width").text(imgwidth);
                    $("#height").text(imgheight);
                    if (imgwidth > maxwidth || imgheight > maxheight) {
                        swal('La imágen excede la resolución máxima. 480x480');
                        $("#logo").val("");
                    }
                };

                if (photo.size > maxSize) {
                    swal('La imágen excede el tamaño máximo. 2MB');
                    $("#logo").val("");
                }

                if (!mimes.includes(photo.type)) {
                    swal('Tipo de archivo invalido');
                    component.files[0] = null;
                }
            });
        }, false);
    </script>
@endpush