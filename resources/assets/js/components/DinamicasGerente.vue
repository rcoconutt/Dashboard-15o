<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <form action="/dinamicas/action" method="POST" id="form">
                    <input type="hidden" name="_token" v-model="csrf">
                    <div class="row flex-row-reverse">
                        <div class="col-md-4 first col-sm-12 text-right">
                            <a class="btn btn-block btn-mdb-color" href="/dinamicas/create">Crear dinámica</a>
                        </div>
                        <div class="col-md-4 col-sm-12"><br></div>
                        <div class="col-md-4 second col-sm-12">
                            <div class="row">
                                <div class="col-8">
                                    <select name="actions" id="actions" class="form-control" style="margin-top: 3px;">
                                        <option value="0" disabled selected>Seleccionar una acción</option>
                                        <option value="1">Aprobar dinámicas</option>
                                        <option value="2">Rechazar dinámicas</option>
                                        <option value="3">Eliminar dinámicas</option>
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
                    <table id="dinamicas" class="table table-bordered dt-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Dinamica</th>
                                <th>Descripción</th>
                                <th>Premio</th>
                                <th>Fecha de termino</th>
                                <th>Estado</th>
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
    import moment from 'moment'
    import swal from 'sweetalert'

    export default {
        name: "dinamicas-gerente",
        props: ['user'],
        data () {
            return {
                all: null,
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        },
        methods: {
            getDinamicas: function () {
                $('#dinamicas').DataTable({
                    ajax: {
                        url: '/api/dinamicas/' + this.user.brand_id,
                        dataSrc: 'dinamicas',
                    },
                    scrollY: "300px",
                    responsive: true,
                    columns: [
                        { data: 'ID_DINAMICA', render: function ( data, type, row, meta ) {
                            return "<div class='form-check mb-2'><input class='form-check-input' name='dinamica_id[]' type='checkbox' value='" + data + "' ></div>";
                        }},
                        { data: 'DINAMICA' },
                        { data: 'DESCRIPCION', width: "30%" },
                        { data: 'PREMIO'},
                        { data: 'FECHA_FIN' , render: function (data, type, row, meta) {
                                return moment(data).format('DD-MM-YYYY');
                        }},
                        { data: 'ACTIVO', render: function ( data, type, row, meta ) {
                                if (data === '0' || data === 0) { return '<i class="fas fa-bookmark text-info"></i> Pendiente' }
                                if (data === '1' || data === 1) { return '<i class="fas fa-check-circle text-success"></i> Aprobada' }
                                if (data === '2' || data === 2) { return '<i class="fas fa-ban text-danger"></i> Rechazada' }
                                return ''
                        }},
                    ],
                    language: {
                        search: "Buscar:",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        },
                        info: "Mostrando _START_ a _END_ de _TOTAL_ dinamicas",
                        lengthMenu:    "Mostrar _MENU_ dinamicas",
                        loading: "Cargando"
                    }
                });
            }
        },
        mounted () {
            $().ready(() => {
                this.getDinamicas();

                $('#actions').on('change', function() {
                    if (this.value == 3) {
                        swal({
                            title: "Confirmar acción",
                            text: "Realmente deseas eliminar las dinámicas seleccionadas",
                            icon: "error",
                            buttons: ["Cancelar", "Eliminar"],
                        }).then((value) => {
                            if (value) {
                                $("#form").submit();
                            }
                        });
                    } else {
                        $("#form").submit();
                    }
                })
            });
        }
    }
</script>

<style scoped>

</style>