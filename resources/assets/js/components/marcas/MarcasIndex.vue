<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <form action="/marcas/action" method="POST" id="form">
                    <input type="hidden" name="_token" v-model="csrf">
                    <div class="row flex-row-reverse">
                        <div class="col-md-4 first col-sm-12 text-right">
                            <a class="btn btn-block btn-mdb-color" href="/marcas/create">Crear marcas</a>
                        </div>
                        <div class="col-md-4 col-sm-12"><br></div>
                        <div class="col-md-4 second col-sm-12">
                            <div class="row">
                                <div class="col-8">
                                    <select name="actions" id="actions" class="form-control" style="margin-top: 3px;">
                                        <option value="0" disabled selected>Seleccionar una acción</option>
                                        <option value="1">Activar marcas</option>
                                        <option value="2">Desactivar marcas</option>
                                        <!--<option value="3">Eliminar marcas</option>-->
                                    </select>
                                </div>
                                <div class="col-4">
                                    <button type="button" id="send" class="btn btn-sm btn-default" aria-label="Left Align">
                                        <span class="fas fa-caret-right fa-lg" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <table id="marcas" class="table table-bordered dt-responsive" style="width:100%">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Destilado</th>
                            <th>Marca</th>
                            <th>Estado</th>
                            <th>Editar</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "marcas-index",
        props: ['user', 'message'],
        data() {
            return {
                all: null,
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        },
        methods: {
            getMarcas: function () {
                $('#marcas').DataTable({
                    ajax: {
                        url: '/api/v1/marcas',
                        dataSrc: 'marcas',
                    },
                    scrollY: "300px",
                    responsive: true,
                    columns: [
                        {
                            data: 'ID_MARCA', render: function (data, type, row, meta) {
                                return "<div class='form-check mb-2'><input class='form-check-input' name='marca_id[]' type='checkbox' value='" + data + "' ></div>";
                            }
                        },
                        {data: 'destilado.DESTILADO', width: "20%"},
                        {data: 'MARCA', width: "30%"},
                        {
                            data: 'ACTIVO', render: function (data, type, row, meta) {
                                if (data === '0' || data === 0) {
                                    return '<i class="fas fa-bookmark text-info"></i> Deshabilitado'
                                }
                                if (data === '1' || data === 1) {
                                    return '<i class="fas fa-check-circle text-success"></i> Habilitado'
                                }
                                return ''
                            }
                        },
                        {
                            data: 'ID_MARCA', render: function (data, type, row, meta) {
                                return "<a class='btn btn-link btn-sm waves-effect text-primary' href='/marcas/" + data + "'>Editar</a></div>";
                            }
                        },
                    ],
                    language: {
                        search: "Buscar:",
                        paginate: {
                            first: "Primero",
                            previous: "Anterior",
                            next: "Siguiente",
                            last: "Último"
                        },
                        info: "Mostrando _START_ a _END_ de _TOTAL_ marcas",
                        lengthMenu: "Mostrar _MENU_ marcas",
                        loading: "Cargando"
                    }
                });
            }
        },
        mounted() {
            $().ready(() => {
                if (this.message.length > 0) {
                    swal({ title: "", text: this.message, button: "Entendido", icon: "success", timer:3000 });
                }

                this.getMarcas();

                $('#send').on('click', function (e) {
                    event.preventDefault();
                    let numberOfChecked = $('input:checkbox:checked').length;
                    let value = $("#actions").val();
                    if (numberOfChecked > 0) {
                        if (value == 3) {
                            swal({
                                title: "Confirmar acción",
                                text: "Realmente deseas eliminar las marcas seleccionadas",
                                icon: "error",
                                buttons: ["Cancelar", "Eliminar"],
                            }).then((value) => {
                                if (value) {
                                    $("#form").submit();
                                }
                            });
                        } else {
                            if (value == 0 || value == null) {
                                swal({title: "", text: "Selecciona una acción", button: "Entendido"});
                            } else {

                                $("#form").submit();
                            }
                        }
                    } else {
                        swal({ title: "", text: "Selecciona al menos una marcas", button: "Entendido" });
                    }
                })
            });
        }
    }
</script>

<style scoped>

</style>