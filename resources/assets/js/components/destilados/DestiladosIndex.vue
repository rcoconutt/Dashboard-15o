<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <form action="/destilados/action" method="POST" id="form">
                    <input type="hidden" name="_token" v-model="csrf">
                    <div class="row flex-row-reverse">
                        <div class="col-md-4 first col-sm-12 text-right">
                            <a class="btn btn-block btn-mdb-color" href="/destilados/create">Crear destilado</a>
                        </div>
                        <div class="col-md-4 col-sm-12"><br></div>
                        <div class="col-md-4 second col-sm-12">
                            <div class="row">
                                <div class="col-8">
                                    <select name="actions" id="actions" class="form-control" style="margin-top: 3px;">
                                        <option value="0" disabled selected>Seleccionar una acción</option>
                                        <option value="1">Activar destilados</option>
                                        <option value="2">Desactivar destilados</option>
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
                    <table id="destilados" class="table table-bordered dt-responsive" style="width:100%">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Destilado</th>
                            <th>Añejamiento</th>
                            <th>Caracteristicas</th>
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
        name: "destilados-index",
        props: ['user', 'message'],
        data() {
            return {
                all: null,
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        },
        methods: {
            getDestilados: function () {
                console.log("1");
                $('#destilados').DataTable({
                    ajax: {
                        url: '/api/v1/destilados',
                        dataSrc: 'destilados',
                    },
                    scrollY: "300px",
                    responsive: true,
                    columns: [
                        {
                            data: 'ID_DESTILADO', render: function (data, type, row, meta) {
                                return '<div class="form-check" style="margin-left: -20px;">\n' +
                                    '<input type="checkbox" name="destilado_id[]" id="destilado_' + data + '" class="form-check-input" value="' + data + '">\n' +
                                    '<label class="label-table grey-text"  for="destilado_' + data + '"></label>\n' +
                                    '</div>'
                                //return "<div class='form-check mb-2'><input class='form-check-input' name='destilado_id[]' type='checkbox' value='" + data + "' ></div>";
                            }
                        },
                        {data: 'DESTILADO', width: "20%"},
                        {data: 'ANEJAMIENTO', width: "20%"},
                        {data: 'CARACTERISTICAS'},
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
                            data: 'ID_DESTILADO', render: function (data, type, row, meta) {
                                return "<a class='btn btn-link btn-sm waves-effect text-primary' href='/destilados/" + data + "'>Editar</a></div>";
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
                        info: "Mostrando _START_ a _END_ de _TOTAL_ destilados",
                        lengthMenu: "Mostrar _MENU_ destilados",
                        loading: "Cargando"
                    }
                });
            }
        },
        mounted() {
            $().ready(() => {
                console.log("0");
                if (this.message.length > 0) {
                    swal({ title: "", text: this.message, button: "Entendido", icon: "success", timer:3000 });
                }

                this.getDestilados();

                $('#send').on('click', function (e) {
                    event.preventDefault();
                    let numberOfChecked = $('input:checkbox:checked').length;
                    let value = $("#actions").val();
                    if (numberOfChecked > 0) {
                        if (value == 3) {
                            swal({
                                title: "Confirmar acción",
                                text: "Realmente deseas eliminar los destilados seleccionadas",
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
                        swal({ title: "", text: "Selecciona al menos un destilados", button: "Entendido" });
                    }
                })
            });
        }
    }
</script>

<style scoped>

</style>