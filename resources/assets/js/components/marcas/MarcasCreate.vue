<template>
    <div class="container">
        <div class="card">
            <div class="card-header row">
                <div class="col-md-6 col-sm-12 text-center">
                    <a class="btn btn-sm btn-link btn-block waves-effect" href="/marcas">Volver</a>
                </div>
                <div class="col-md-6 col-sm-12 text-center">
                    <strong>Actualizar marca</strong>
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
                            <input type="text" class="form-control" name="name" id="name" v-model="marca" placeholder=""
                                   required/>
                            <label for="name">Nombre de la marca: </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="destilado">Destilado</label>
                            <select id="destilado" name="destilado" class="form-control" v-model="destilado">
                                <option v-for="destilado in destilados" v-bind:value="destilado.ID_DESTILADO">
                                    {{ destilado.DESTILADO }} - {{ destilado.ANEJAMIENTO }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 align-middle">
                            <label for="status">Activo</label>
                            <select id="status" name="estado" class="form-control" v-model="activo">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-outline-primary waves-effect" id="save" style="margin-top: 20px">Enviar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "marcas-create",
        props: ['user', 'destilados'],
        data() {
            return {
                error: null,
                marca:  null,
                destilado: null,
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
                    url: '/api/v1/marcas',
                    json: true,
                    data: {
                        marca: this.marca,
                        destilado: this.destilado,
                        estado: this.activo
                    }
                }).then((response) => {
                    swal({
                        title: "",
                        text: response.data.message,
                        icon: "success",
                        button: "Entendido",
                        timer: 3000,
                    }).then((response) => {
                        window.location.href = '/marcas';
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