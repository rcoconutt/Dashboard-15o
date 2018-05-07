<template>
    <div class="container">
        <div class="card">
            <div class="card-header row">
                <div class="col-md-6 col-sm-12 text-center">
                    <a class="btn btn-sm btn-link btn-block waves-effect" href="/venues">Volver</a>
                </div>
                <div class="col-md-6 col-sm-12 text-center">
                    <strong>Crear nueva centro</strong>
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
                            <input type="text" class="form-control" name="name" id="name" v-model="centro" placeholder=""
                                   required/>
                            <label for="name">Nombre del centro: </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="estado">Zona</label>
                            <select id="estado" name="estado" class="form-control" v-model="municipio">
                                <option v-for="zona in zonas" v-bind:value="zona.ID_MUNICIPIO">{{ zona.MUNICIPIO }}
                                </option>
                            </select>
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
        name: "venues-create",
        props: ['user', 'zonas'],
        data() {
            return {
                error: null,
                municipio: null,
                centro: null,
            }
        },
        methods: {
            save() {
                let button = $("#save");
                button.prop("disabled", true);
                button.html("<img src='/img/loading2.gif' height='20px'>");

                axios({
                    method: 'POST',
                    url: '/api/venues',
                    json: true,
                    data: {
                        municipio: this.municipio,
                        nombre: this.centro,
                        status: 0
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
                    button.html('ENVIAR');
                });

            }
        }
    }
</script>

<style scoped>

</style>