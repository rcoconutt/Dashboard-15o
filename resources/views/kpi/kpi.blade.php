@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="mt-lg-5">
            <div class="row justify-content-center">
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control" id="marca" name="marca">
                            <option disabled selected> Selecciona una marca</option>
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->ID_MARCA }}">{{ $marca->MARCA }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio" placeholder="Fecha de inicio">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" id="fecha_fin" name="fecha_fin" placeholder="Fecha de fin">
                    </div>
                </div>

                <div class="col-md-3 text-center">
                    <button type="button" id="send" onclick="getMarcaData()" class="btn btn-sm btn-default" aria-label="Left Align">
                        <span class="fas fa-caret-right fa-lg" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </section>

        <div>
            <hr>
            <h2 id="data_marca" class="font-weight-bold dark-grey-text"></h2>
        </div>

        <section class="mt-lg-5">
            <div class="row">
                <div class="col-xl-6 col-md-4">
                    <div class="card card-cascade cascading-admin-card" style="min-height: 160px">
                        <div class="admin-up">
                            <div class="data" style="margin: 20px;">
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="data">
                                <img src="{{ asset('images/cup.png') }}" style="position: absolute">
                                <div style="margin-left: 100px">
                                    <p>Centro de consumo</p>
                                    <h3 id="data_centro" class="font-weight-bold dark-grey-text"></h3>
                                    <h4 id="data_centro_copas" class="font-weight-bold dark-grey-text"></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-4">
                    <div class="card card-cascade cascading-admin-card" style="min-height: 160px">
                        <div class="admin-up">
                            <div class="data" style="margin: 20px;">
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="data">
                                <img src="{{ asset('images/cup.png') }}" style="position: absolute">
                                <div style="margin-left: 100px">
                                    <p>Bartender con más ventas</p>
                                    <h3 id="data_bartender" class="font-weight-bold dark-grey-text"></h3>
                                    <h4 id="data_bartender_copas" class="font-weight-bold dark-grey-text"></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div>
            <hr>
            <div class="row justify-content-center">
                <h2 class="font-weight-bold dark-grey-text">Reportes de vendedores</h2>
            </div>
        </div>


        <section class="mb-5">
            <div class="card card-cascade narrower">
                <section>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 mr-0">
                            <div class="card-body pb-0">
                                <div class="row card-body pt-3">
                                    <canvas id="chart_vendedores"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </section>

        <div>
            <hr>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2 class="font-weight-bold dark-grey-text">Reportes de desplazamiento</h2>
                </div>

                <div class="col-md-3 text-center">
                    <div class="form-group">
                        <select class="form-control" id="centro" name="centro">
                            <option disabled selected> Selecciona una centro de consumo</option>
                            @foreach($centros as $centro)
                                <option value="{{ $centro->ID_CENTRO }}">{{ $centro->CENTRO }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3 text-center">
                    <button type="button" id="send_centro" onclick="getCentroData()" class="btn btn-sm btn-default" aria-label="Left Align" disabled>
                        <span class="fas fa-caret-right fa-lg" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>


        <section class="mb-5">
            <div class="card card-cascade narrower">
                <section>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 mr-0">
                            <div class="card-body pb-0">
                                <div class="row card-body pt-3">
                                    <canvas id="chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', init, false);
        function init() {
            createPikaday();
        }

        function createChart(data, chart_id) {
            let ctx = document.getElementById(chart_id);
            let chart = new Chart(ctx, {
                type: 'bar',
                responsive: true,
                maintainAspectRatio: false,
                data: data,
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }

        function createPikaday() {
            let lang = {
                previousMonth: 'Mes anterior',
                nextMonth: 'Mes siguiente',
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                weekdays: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
                weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab']
            };

            let startPicker = new Pikaday({
                field: document.getElementById('fecha_inicio'),
                i18n: lang,
                onSelect: function (date) {
                    $("#fecha_inicio").val(moment(date).format('DD-MM-YYYY'));
                }
            });

            let endPicker = new Pikaday({
                field: document.getElementById('fecha_fin'),
                i18n: lang,
                onSelect: function (date) {
                    $("#fecha_fin").val(moment(date).format('DD-MM-YYYY'));
                }
            });
        }

        function validateMarca() {
            let marca = $("#marca").val();
            let fecha_inicio = $("#fecha_inicio").val();
            let fecha_fin = $("#fecha_fin").val();

            if (marca && marca > 0) {
                let parsed_fecha = moment(fecha_inicio, 'DD-MM-YYYY');


                if (fecha_inicio && parsed_fecha.isValid()) {
                    fecha_inicio = parsed_fecha;
                    parsed_fecha = moment(fecha_fin, 'DD-MM-YYYY');

                    if (fecha_fin && parsed_fecha.isValid()) {
                        fecha_fin = parsed_fecha;
                        if (fecha_fin.isAfter(fecha_inicio)) {
                            return true;
                        } else {
                            error("La fecha de fin debe ser posterior a la fecha de inicio");
                            return false;
                        }
                    } else {
                        error("Ingresa una fecha de fin valida");
                        return false;
                    }
                } else {
                    error("Ingresa una fecha de inicio valida");
                    return false;
                }
            } else {
                error("Selecciona una marca",);
                return false;
            }
        }

        function validateCentro() {
            let centro = $("#centro").val();
            if (centro) {
                return true;
            } else {
                error("Selecciona un centro de consumo valido");
                return false;
            }
        }

        let dynamicColors = function () {
            let r = Math.floor(Math.random() * 255);
            let g = Math.floor(Math.random() * 255);
            let b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
        };

        function getMarcaData() {
            if (validateMarca()) {
                let marca = $("#marca").val();
                let fecha_inicio = $("#fecha_inicio").val();
                let fecha_fin = $("#fecha_fin").val();
                let marca_text = $("#marca").find("option[value='" + marca + "']").text();


                let data_marca = $("#data_marca");

                let centro_centro = $("#data_centro");
                let centro_copas = $("#data_centro_copas");

                let vendedor_vendedor = $("#data_bartender");
                let vendedor_copas = $("#data_bartender_copas");

                axios.post('/api/kpi', {
                    marca_id: marca,
                    fecha_inicio: fecha_inicio,
                    fecha_fin: fecha_fin,
                }).then((response) => {
                    let usuario = response.data.usuario;
                    vendedor_vendedor.html(usuario.NOMBRE);
                    vendedor_copas.html(usuario.total + " copas");

                    let centro = response.data.centro;
                    centro_centro.html(centro.CENTRO);
                    centro_copas.html(centro.total + " copas");

                    data_marca.html(marca_text);

                    $("#send_centro").prop('disabled', false);

                    let labels = [];
                    let values = [];
                    let color = [];

                    response.data.usuarios.forEach((usuario) => {
                        labels.push(usuario.NOMBRE);
                        values.push(usuario.total);
                        color.push(dynamicColors());
                    });
                    let data = {
                        labels: labels,
                        datasets: [{
                            label: '# de ventas',
                            data: values,
                            borderWidth: 1/*,
                            backgroundColor: color,
                            borderColor: 'rgba(200, 200, 200, 0.75)',
                            hoverBorderColor: 'rgba(200, 200, 200, 1)'*/
                        }]
                    };

                    createChart(data, "chart_vendedores");
                }).catch((response) => {
                    if (response.response) {
                        error(response.response.data.message);
                    } else {
                        error("Esta marca aún no cuenta con datos")
                    }
                })
            }
        }

        function getCentroData() {
            if (validateCentro()) {
                let marca = $("#marca").val();
                let fecha_inicio = $("#fecha_inicio").val();
                let fecha_fin = $("#fecha_fin").val();
                let centro = $("#centro").val();

                axios.post('/api/kpi/centro', {
                    marca_id: marca,
                    fecha_inicio: fecha_inicio,
                    fecha_fin: fecha_fin,
                    centro_id: centro
                }).then((response) => {
                    let labels = [];
                    let values = [];
                    response.data.dates.forEach((date) => {
                        labels.push(date.fecha);
                        values.push(date.total);

                    });
                    let data = {
                        labels: labels,
                        datasets: [{
                            label: '# de ventas',
                            data: values,
                            borderWidth: 1
                        }]
                    };

                    createChart(data, "chart");
                }).catch((response) => {
                    error(response.response.data.message);
                });
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