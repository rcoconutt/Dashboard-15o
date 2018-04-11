<template>
    <div class="container">
        <div class="card">
            <div class="card-header row">
                <div class="col-md-6 col-sm-12 text-center">
                    <a class="btn btn-sm btn-link btn-block waves-effect" href="/dinamicas">Volver</a>
                </div>
                <div class="col-md-6 col-sm-12 text-center">
                    <strong>Crear nueva dinámica</strong>
                </div>
            </div>
            <div class="card-body">
                <form @submit.prevent="save" method="post">
                    <div class="alert alert-danger" v-if="error">
                        {{ error }}
                    </div>
                    <input type="hidden" v-model="user.brand_id"/>
                    <input type="hidden" v-model="user.id"/>
                    <div class="md-form">
                        <input type="text" class="form-control" name="name" id="name" v-model="name" placeholder=""
                               required/>
                        <label for="name">Nombre de la dinámica: </label>
                    </div>
                    <div class="md-form">
                        <input type="text" class="form-control" name="premio" id="premio" v-model="premio"
                               placeholder="" required/>
                        <label for="premio">Premio de la dinámica: </label>
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
                                placeholder=""
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
                                placeholder=""
                                input-class="form-control"
                                class="form-control"
                                @selected="addZonaGroup"
                        >
                        </autocomplete>
                    </div>
                    <div class="form-group">
                        <input type="radio" id="one" value="1" v-model="kind">
                        <label for="one">Por ventas</label>
                        <br>
                        <input type="radio" id="two" value="0" v-model="kind">
                        <label for="two">Por puntos</label>
                        <div v-if="kind === '0'">
                            <label for="reglas">Reglas: </label>
                            <textarea class="form-control" rows="5" id="reglas" name="reglas" v-model="reglas"
                                      placeholder=""></textarea>
                        </div>
                        <div v-if="kind === '1'">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <label for="reglas">Reglas: </label>
                                    <div class="input-group mb-2">
                                        <input class="form-control" name="cantidad" v-model="cantidad" id="cantidad"
                                               placeholder="" type="number"/>
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
                                            placeholder=""
                                            input-class="form-control"
                                            class="form-control"
                                            @selected="addMarcaGroup"
                                    >
                                    </autocomplete>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <label for="descripcion">Descripción: </label>
                                    <textarea class="form-control" rows="3" id="descripcion" name="reglas"
                                              v-model="descripcion" placeholder=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <label for="start">Fecha de inicio: </label>
                                <input class="form-control" name="start" v-model="start" id="start"
                                       placeholder="Fecha de inicio" type="text"/>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label for="end">Fecha de fin: </label>
                                <input class="form-control" name="end" v-model="end" id="end" placeholder="Fecha de fin"
                                       type="text"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 offset-md-6">
                        <button class="btn btn-outline-primary waves-effect">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import Autocomplete from 'vuejs-auto-complete'
    import swal from 'sweetalert'
    import moment from 'moment'
    import Pikaday from 'pikaday'

    export default {
        name: "create-dinamica",
        components: {Autocomplete},
        props: ['user'],
        data() {
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
                error: null,
                // Date pickers
                startPicker: null,
                endPicker: null,
            }
        },
        methods: {
            addDistributionGroup(group) {
                this.venue = group;
            },
            addZonaGroup(group) {
                this.zona = group
            },
            addMarcaGroup(group) {
                this.marca = group
            },
            save() {
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
                        marca: this.marca,
                        user_id: this.user.id,
                        brand_id: this.user.brand_id,
                        start: $("#start").val(),
                        end: $("#end").val()
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
            let lang = {
                previousMonth: 'Mes anterior',
                nextMonth: 'Mes siguiente',
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                weekdays: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
                weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab']
            };

            this.startPicker = new Pikaday({
                field: document.getElementById('start'),
                i18n: lang,
                onSelect: function (date) {
                    this.start = moment(date).format('DD-MM-YYYY');
                    $("#start").val(moment(date).format('DD-MM-YYYY'));
                }
            });

            this.endPicker = new Pikaday({
                field: document.getElementById('end'),
                i18n: lang,
                onSelect: function (date) {
                    this.end = moment(date).format('DD-MM-YYYY');
                    $("#end").val(moment(date).format('DD-MM-YYYY'));
                }
            });

            this.startPicker.setDate(moment());
            this.endPicker.setDate(moment());
        },
        updated() {
            if (this.startPicker != null) {
                this.startPicker.setDate(moment());
                console.log("Test");
            }

            if (this.endPicker != null) {
                this.endPicker.setDate(moment());
                console.log("Test");
            }
        }
    }
</script>

<style scoped>

</style>