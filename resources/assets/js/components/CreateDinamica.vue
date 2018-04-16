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
                    <div id="error">
                        <div class="alert alert-danger" v-if="error">
                            {{ error }}
                        </div>
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
                                :source="venues"
                                results-display="CENTRO"
                                results-value="ID_CENTRO"
                                placeholder=""
                                input-class="form-control"
                                class="form-control"
                                @selected="addDistributionGroup"
                                @clear="removeDistribution"
                        >
                        </autocomplete>
                    </div>
                    <div class="form-group">
                        <label for="zona">Zona: </label>
                        <autocomplete
                                name="zona"
                                id="zona"
                                v-model="zona"
                                :source="municipios"
                                results-display="MUNICIPIO"
                                results-value="ID_MUNICIPIO"
                                placeholder=""
                                input-class="form-control"
                                class="form-control"
                                @selected="addZonaGroup"
                                @clear="removeZona"
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
                                        <input class="form-control form-custom" name="cantidad" v-model="cantidad" id="cantidad"
                                               placeholder="" type="number"/>
                                        <select class="form-control" id="tipo_consumo" name="tipo_consumo">
                                            <option value="1">Botellas</option>
                                            <option value="2">Copas</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="marca">Marca: </label>
                                    <autocomplete
                                            name="marca"
                                            id="marca"
                                            v-model="marca"
                                            :source="marcas"
                                            results-property="marcas"
                                            results-display="MARCA"
                                            results-value="ID_MARCA"
                                            placeholder=""
                                            input-class="form-control"
                                            class="form-control"
                                            @selected="addMarcaGroup"
                                            @clear="removeMarca"
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
                        <button class="btn btn-outline-primary waves-effect" id="save" >Enviar</button>
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
                // Autocomplete
                venues: [],
                municipios: [],
                marcas: [],
            }
        },
        methods: {
            addDistributionGroup(group) {
                this.venue = group;
                let municipio_id = this.venue.selectedObject.ID_MUNICIPIO;
                let municipio = null;
                this.municipios.forEach((m) => {
                    if (m.ID_MUNICIPIO == municipio_id) {
                        municipio = m;
                    }
                });

                //this.zona.display = municipio.MUNICIPIO;
                //this.zona.value = municipio.ID_MUNICIPIO;MUNICIPIO;
            },
            removeDistribution() {
                this.venue = null;
            },
            addZonaGroup(group) {
                this.zona = group
            },
            removeZona() {
                this.zona = null;
            },
            addMarcaGroup(group) {
                this.marca = group
            },
            removeMarca() {
                this.marca = null;
            },
            backToError() {
                $('html,body').animate({
                        scrollTop: $("#error").offset().top
                }, 'slow');
            },
            save() {
                $("#save").prop("disabled", true);

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
                        button: "Entendido",
                        timer: 3000,
                    });
                    () => {
                        window.location.href = '/dinamicas';
                    };
                }).catch((response) => {
                    this.error = response.response.data.message;
                    this.backToError();
                    $("#save").prop("disabled", false);
                });

            }
        },
        created() {
            axios.get('/api/venues')
                .then((response) => {
                    response.data.venues.forEach((venue) => {
                        this.venues.push(venue)
                    });
                });

            axios.get('/api/municipios')
                .then((response) => {
                    response.data.municipios.forEach((municipio) => {
                        this.municipios.push(municipio)
                    });
                });

            axios.get('/api/marcas')
                .then((response) => {
                    response.data.marcas.forEach((marca) => {
                        this.marcas.push(marca)
                    });
                });
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
            }

            if (this.endPicker != null) {
                this.endPicker.setDate(moment());
            }

            if (this.start) {
                console.log(this.start);
                $("#start").val(this.start);
            } else {
                console.log("Nada");
            }
        }
    }
</script>

<style scoped>
    .form-custom {
        height: calc(2.19rem + 2px)!important;
    }
</style>