<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
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
        name: "dinamicas-supervisor",
        props: ['user'],
        methods: {
            getDinamicas: function () {
                $('#dinamicas').DataTable({
                    ajax: {
                        url: '/api/dinamicas/' + this.user.brand_id,
                        dataSrc: 'dinamicas',
                    },
                    scrollY: "300px",
                    columns: [
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
            });
        }
    }
</script>

<style scoped>

</style>