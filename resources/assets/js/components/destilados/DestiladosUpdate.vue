<template>
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
                            <input type="text" class="form-control" name="name" id="name" v-model="destilado.DESTILADO" placeholder=""
                                   required/>
                            <label for="name">Nombre del destilado: </label>
                        </div>
                        <div class="md-form col-md-6">
                            <input type="text" class="form-control" name="anejamiento" id="anejamiento" v-model="destilado.ANEJAMIENTO" placeholder=""
                                   required/>
                            <label for="anejamiento">Nombre del añejamiento: </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="md-form col-md-6">
                            <img :src="imagen"/>
                        </div>
                        <div class="md-form col-md-6">
                            <input type="text" class="form-control" name="anejamiento" id="anejamiento" v-model="destilado.ANEJAMIENTO" placeholder=""
                                   required/>
                            <label for="name">Nombre del añejamiento: </label>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 offset-md-6">
                        <button class="btn btn-outline-primary waves-effect" id="save">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "destilados-update",
        props: ['user', 'destilado', 'imagen'],
        data() {
            return {
                error: null,
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
                    method: 'PUT',
                    url: '/api/v1/destilados/' + this.destilado.ID_DESTILADO,
                    json: true,
                    data: {
                        municipio: this.venue.ID_MUNICIPIO,
                        nombre: this.venue.CENTRO,
                        status: this.venue.ACTIVO,
                    }
                }).then((response) => {
                    swal({
                        title: "",
                        text: response.data.message,
                        icon: "success",
                        button: "Entendido",
                        timer: 3000,
                    }).then((response) => {
                        window.location.href = '/venues';
                    });
                }).catch((response) => {
                    this.error = response.response.data.message;
                    this.backToError();
                    button.prop("disabled", false);
                    button.html('Guardar');
                });

            }
        }
    }
</script>

<style scoped>

</style>