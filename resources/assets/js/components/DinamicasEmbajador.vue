<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="col-md-2 col-sm-8 offset-md-10 ">
                    <a class="btn btn-block btn-success" href="/dinamicas/create">Proponer dinámica</a>
                </div>
                <hr>
                <table id="dinamicas" cellspacing="0" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Dinamica</th>
                            <th>Descripción</th>
                            <th>Premio</th>
                            <th>Fecha de termino</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'

    export default {
        name: "dinamicas-embajador",
        props: ['user'],
        methods: {
            getDinamicas: function () {
                $('#dinamicas').DataTable({
                    ajax: {
                        url: '/api/dinamicas',
                        dataSrc: 'dinamicas',
                    },
                    scrollY: "300px",
                    columns: [
                        { data: 'DINAMICA' },
                        { data: 'DESCRIPCION', width: "35%" },
                        { data: 'PREMIO'},
                        { data: 'FECHA_FIN' , render: function (data, type, row, meta) {
                                return moment(data).format('DD-MM-YYYY');
                            }},
                        { data: 'ACTIVO', render: function ( data, type, row, meta ) {
                                if (data === '0' || data === 0) { return 'Pendiente' }
                                if (data === '1' || data === 1) { return 'Aprobada' }
                                if (data === '2' || data === 2) { return 'Rechazada' }
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
            });
        }
    }
</script>

<style scoped>

</style>