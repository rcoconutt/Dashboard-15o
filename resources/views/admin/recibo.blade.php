@extends('layouts.app')

@section('content')
    <div class="container">
    <div class="card">
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="view gradient-card-header text-dark">
                    <a href="{{ url('admin') }}"  class="btn btn-sm btn-outline-default navbar-btn pull-left" style="margin-top: 10px; margin-left: 15px">
                        <span class="fas fa-caret-left fa-lg" aria-hidden="true"></span> Volver
                    </a>
                    <h4 class="h2-responsive text-center" style="margin-top: 8px"><strong>Datos</strong></h4>
                </div>

                <div class="card-body">
                    <div class="row card-body">
                        <div class="col-md-12">
                            <p>Articulos vendidos: <strong>{{ $recibo->total }}</strong></p>
                            <p>Fecha:
                                <strong>{{ ucfirst(\Jenssegers\Date\Date::parse($recibo->FECHA)->format('l j \\d\\e F \\d\\e Y')) }}</strong>
                            </p>
                            <p>Hora: <strong>{{ \Jenssegers\Date\Date::parse($recibo->FECHA)->format('H:i') }}</strong>
                            </p>
                            <p>Centro: <strong>{{ $recibo->centro->CENTRO }}</strong></p>
                            <p>Acción: </p>
                            <form method="POST" action="{{ Request::url() }}">
                                {{ csrf_field() }}
                                <div class="form-group col-md-6" style="margin: 0 auto;">
                                    <select name="action" class="form-control">
                                        <option disabled selected>Selecciona una opción</option>
                                        <option value="1">Aprobar</option>
                                        <option value="2">Rechazar</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6" style="margin: 0 auto;">
                                    <button class="btn btn-outline-success btn-sm btn-block">Envíar</button>
                                </div>
                                @if (session('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }} <a href="{{ route('admin') }}" class="btn btn-link">Volver</a>
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="view gradient-card-header text-dark">
                    <h4 class="text-dark h2-responsive text-center" style="margin-top: 8px"><strong>Foto</strong></h4>
                </div>

                <div class="card-body">
                    <div class="row card-body">
                        <div class="col-md-12 text-center">
                            <a href="#" onclick="event.preventDefault();">
                                @if($recibo->RECIBO)
                                    <img src="data:image/jpeg;base64,{{ base64_encode($recibo->RECIBO) }}" width="250px" id="content" height="250px" style="margin: 0 auto;"/>
                                @else
                                    @if($recibo->url)
                                        <img src="{{ $recibo->url }}" width="250px" id="content" height="250px" style="margin: 0 auto;">
                                    @else
                                        <img src="{{ asset('images/null.png') }}" width="250px" id="content" height="250px" style="margin: 0 auto;">
                                    @endif
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section>
            <div class="card card-cascade narrower z-depth-0" style="z-index: 1;">
                <div class="view gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
                    <div></div>
                    <h4 class="h2-responsive mb-0"><strong>Detalle de venta</strong></h4>
                    <div></div>
                </div>

                <div class="px-4">
                    <div class="table-responsive">
                        <table class="table table-hover dt-responsive">
                            <thead>
                            <tr>
                                <th class="th-lg"><a href="">Destilado<i class="fa fa-sort ml-1"></i></a></th>
                                <th class="th-lg"><a href="">Marca<i class="fa fa-sort ml-1"></i></a></th>
                                <th class="th-lg"><a href="">Cantidad<i class="fa fa-sort ml-1"></i></a></th>
                                <th class="th-lg"><a href="">Tipo de consumo<i class="fa fa-sort ml-1"></i></a></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recibo->desgloce as $detalle)
                                <tr>
                                    <td>{{ $detalle->marca->destilado->DESTILADO }}</td>
                                    <td>{{ $detalle->marca->MARCA }}</td>
                                    <td>{{ $detalle->CANTIDAD }}</td>
                                    <td>{{ $detalle->consumo->TIPO_CONSUMO }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">No se encontró ningún articulo</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
    </div>
@endsection

@push('scripts')
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            let timeoutId, item = $("#content");
            item.hover(function () {
                let element = $(this);
                if (!timeoutId) {
                    timeoutId = setTimeout(function() {
                        timeoutId = null;
                        element.addClass('transition');
                    }, 500);
                }
            }, function() {
                clearTimeout(timeoutId);
                timeoutId = null;
                $(this).removeClass('transition');
            });

            item.click(function () {
                if ($(this).hasClass('transition')) {
                    $(this).removeClass('transition');
                } else {
                    $(this).addClass('transition');
                }
            });

            if (timeoutId) {
                clearTimeout(timeoutId);
                timeoutId = null;
            }
        }, false);

    </script>
@endpush