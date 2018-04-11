<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row flex-row-reverse">
                    <div class="col-md-4 first col-sm-12 text-right">
                        <a class="btn btn-block btn-mdb-color" href="/dinamicas/create">Crear dinámica</a>
                    </div>
                    <div class="col-md-4 col-sm-12"></div>
                    <div class="col-md-4 second col-sm-12"></div>
                </div>
                <hr>
                <table id="dinamicas" cellspacing="0" class="table table-bordered dt-responsive">
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
                        url: '/api/dinamicas/' + this.user.brand_id,
                        dataSrc: 'dinamicas',
                    },
                    scrollY: "300px",
                    responsive: true,
                    columns: [
                        { data: 'DINAMICA' },
                        { data: 'DESCRIPCION', width: "35%" },
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
                        loading: "Cargando",
                        emptyTable: "Aún no hay dinámicas en esta Empresa"
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