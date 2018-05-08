<template>
    <div class="container">
        <div class="card">
            <div class="card-header row">
                <div class="col-md-6 col-sm-12 text-center">
                    <a class="btn btn-sm btn-link btn-block waves-effect" href="/zonas">Volver</a>
                </div>
                <div class="col-md-6 col-sm-12 text-center">
                    <strong>Crear nueva zona</strong>
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
                    <div class="row">
                        <div class="md-form col-md-6">
                            <input type="text" class="form-control" name="name" id="name" v-model="zona" placeholder=""
                                   required/>
                            <label for="name">Nombre de la zona: </label>
                        </div>
                        <div class="md-form col-md-6">
                            <input type="text" class="form-control" name="abreviatura" id="abreviatura" v-model="abreviatura" placeholder=""
                                   required/>
                            <label for="abreviatura">Abreviatura de la zona: </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 align-middle">
                            <label for="estado">Estado</label>
                            <select id="estado" name="estado" class="form-control" v-model="estado">
                                <option v-for="estado in estados" v-bind:value="estado.ID_ESTADO">{{ estado.ESTADO }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="status">Activo</label>
                            <select id="status" name="estado" class="form-control" v-model="activo">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 offset-md-6">
                        <button class="btn btn-outline-primary waves-effect" id="save">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "zonas-create",
        props: ['user', 'estados'],
        data() {
            return {
                error: null,
                estado: null,
                zona: null,
                activo: null
            }
        },
        methods: {
            backToError() {
                $('html,body').animate({
                    scrollTop: $("#error").offset().top
                }, 'slow');
            },
            save() {
                let button = $("#save");
                button.prop("disabled", true);
                button.html("<img src='/img/loading2.gif' height='20px'>");

                axios({
                    method: 'POST',
                    url: '/api/municipios',
                    json: true,
                    data: {
                        estado: this.estado,
                        nombre: this.zona,
                        abreviatura: this.abreviatura,
                        status: this.activo
                    }
                }).then((response) => {
                    swal({
                        title: "",
                        text: response.data.message,
                        icon: "success",
                        button: "Entendido",
                        timer: 3000,
                    }).then((response) => {
                        window.location.href = '/zonas';
                    });
                }).catch((response) => {
                    this.error = response.response.data.message;
                    this.backToError();
                    button.prop("disabled", false);
                    button.html('ENVIAR');
                });

            }
        }
    }
</script>

<style scoped>

</style>