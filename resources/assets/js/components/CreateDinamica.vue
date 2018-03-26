<template>
    <div class="card">
        <div class="card-header row">
            <div class="col-md-6 col-sm-12">
                <a href="/dinamicas">Volver</a>
            </div>
            <div class="col-md-6 col-sm-12">
                Crear nueva dinámica
            </div>
        </div>
        <div class="card-body">
            <form @submit.prevent="save" method="post">
                <div class="alert alert-danger" v-if="error">
                    {{ error }}
                </div>
                <div class="form-group">
                    <label for="name">Nombre de la dinámica: </label>
                    <input class="form-control" name="name" id="name" v-model="name" placeholder="Vive latino - Beers"/>
                </div>
                <div class="form-group">
                    <label for="premio">Premio de la dinámica: </label>
                    <input class="form-control" name="premio" id="premio" v-model="premio" placeholder="2 Entradas para el Vive latino"/>
                </div>
                <div class="form-group">
                    <label for="venue">Centro de consumo: </label>
                    <autocomplete
                            name="venue"
                            id="venue"
                            v-model="venue"
                            source="/api/venues/search/"
                            results-property="venues"
                            results-display="CENTRO"
                            results-value="ID_CENTRO"
                            placeholder="Centro de distribución"
                            input-class="form-control"
                            class="form-control"
                            @selected="addDistributionGroup"
                    >
                    </autocomplete>
                </div>
                <div class="form-group">
                    <label for="zona">Zona: </label>
                    <autocomplete
                            name="zona"
                            id="zona"
                            v-model="zona"
                            source="/api/municipios/search/"
                            results-property="municipios"
                            results-display="MUNICIPIO"
                            results-value="ID_MUNICIPIO"
                            placeholder="Zona"
                            input-class="form-control"
                            class="form-control"
                            @selected="addZonaGroup"
                    >
                    </autocomplete>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <label for="start">Fecha de inicio: </label>
                            <input class="form-control" name="start" v-model="start" id="start" placeholder="Fecha de inicio" type="text"/>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <label for="end">Fecha de fin: </label>
                            <input class="form-control" name="end" v-model="end" id="end" placeholder="Fecha de fin" type="text"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="radio" id="one" value="1" v-model="kind">
                    <label for="one">Por ventas</label>
                    <br>
                    <input type="radio" id="two" value="0" v-model="kind">
                    <label for="two">Por puntos</label>
                    <div v-if="kind === '0'">
                        <label for="reglas">Reglas: </label>
                        <textarea class="form-control" rows="5" id="reglas" name="reglas" v-model="reglas" placeholder="El mesero que más botellas de heineken venda semanalmente ganara unas bocinas bluetooth. Cortes semanales lo acumulado en la semana"></textarea>
                    </div>
                    <div v-if="kind === '1'">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <label for="reglas">Reglas: </label>
                                <div class="input-group mb-2">
                                    <input class="form-control" name="cantidad" v-model="cantidad" id="cantidad" placeholder="3" type="number"/>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">copas</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label for="marca">Marca: </label>
                                <autocomplete
                                        name="marca"
                                        id="marca"
                                        v-model="marca"
                                        source="/api/marcas/search/"
                                        results-property="marcas"
                                        results-display="MARCA"
                                        results-value="ID_MARCA"
                                        placeholder="Marca"
                                        input-class="form-control"
                                        class="form-control"
                                        @selected="addMarcaGroup"
                                >
                                </autocomplete>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <label for="descripcion">Descripción: </label>
                                <textarea class="form-control" rows="3" id="descripcion" name="reglas" v-model="descripcion" placeholder="Por cada 100 botellas de Indio que registres como vendidas tendrás un acceso sencillo al festival vive latino. "></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4 col-sm-12 offset-md-8">
                        <button class="btn btn-block btn-primary" >Enviar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import Autocomplete from 'vuejs-auto-complete'
    import swal from 'sweetalert'
    import Pikaday from 'pikaday'

    export default {
        name: "create-dinamica",
        components: { Autocomplete },
        data () {
            return {
                venue: null,
                zona: null,
                marca: null,
                name: null,
                reglas: null,
                premio: null,
                kind: null,
                descripcion: null,
                cantidad: null,
                start: null,
                end: null,
                error: null
            }
        },
        methods: {
            addDistributionGroup (group) {
                this.venue = group;
            },
            addZonaGroup (group) {
                this.zona = group
            },
            addMarcaGroup (group) {
                this.marca = group
            },
            save () {
                axios({
                    method: 'POST',
                    url: '/api/dinamicas',
                    json: true,
                    data: {
                        name: this.name,
                        premio: this.premio,
                        venue: this.venue,
                        zona: this.zona,
                        kind: this.kind,
                        reglas: this.reglas,
                        cantidad: this.cantidad,
                        descripcion: this.descripcion,
                        marca: this.marca
                    }
                }).then((response) => {
                    swal({
                        title: "",
                        text: response.data.message,
                        icon: "success",
                        button: "Volver",
                    });

                    window.location.href = '/dinamicas';
                }).catch((response) => {
                    this.error = response.response.data.message;
                });

            }
        },
        mounted() {
            let startPicker = new Pikaday({
                field: document.getElementById('start'),
                i18n: {
                    previousMonth : 'Mes anterior',
                    nextMonth     : 'Mes siguiente',
                    months        : ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                    weekdays      : ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
                    weekdaysShort : ['Dom','Lun','Mar','Mie','Jue','Vie','Sab']
                },
                onSelect: function() {
                    this.start = this.getMoment().format('MM-DD-YYYY');
                }
            });
            let endPicker = new Pikaday({
                field: document.getElementById('end'),
                i18n: {
                    previousMonth : 'Mes anterior',
                    nextMonth     : 'Mes siguiente',
                    months        : ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                    weekdays      : ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
                    weekdaysShort : ['Dom','Lun','Mar','Mie','Jue','Vie','Sab']
                },
                onSelect: function() {
                    this.end = this.getMoment().format('MM-DD-YYYY')
                }
            });
        }
    }
</script>

<style scoped>

</style>