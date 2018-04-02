<template>
    <div class="table-responsive">
        <table id="tickets" cellspacing="0" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th># Ticket</th>
                <th>Bartender</th>
                <th>Fecha de envío</th>
                <th>Estado</th>
                <th>Revisar</th>
            </tr>
            </thead>
        </table>
    </div>
</template>

<script>
    import moment from 'moment'

    export default {
        name: "admin",
        methods: {
            getTickets: function () {
                $('#tickets').DataTable({
                    ajax: {
                        url: '/api/tickets',
                        dataSrc: 'tickets',
                    },
                    scrollY: "300px",
                    columns: [
                        { data: 'ID_RECIBO' },
                        { data: 'usuario.NOMBRE' },
                        { data: 'FECHA' , render: function (data, type, row, meta) {
                                return moment(data).format('DD-MM-YYYY');
                            }},
                        { data: 'status', render:function (data, type, row, meta) {
                                if (data === '0' || data === 0) { return '<i class="fas fa-bookmark text-info"></i> Pendiente'; }
                                if (data === '1' || data === 1) { return '<i class="fas fa-check-circle text-success"></i> Aprobado'; }
                                if (data === '2' || data === 2) { return '<i class="fas fa-ban text-danger"></i> Rechazado'; }
                                return '';
                            }},
                        { data: 'ID_RECIBO', render: function( data, type, row, meta) {
                                return '<a class="text-primary" href="/admin/recibo/' + data + '">Revisar</a>'
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
                this.getTickets();
            });
        }
    }
</script>

<style scoped>

</style>