<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <form action="/admin/recibo/action" method="POST" id="form">
                    <input type="hidden" name="_token" v-model="csrf">
                    <div class="row flex-row-reverse">
                        <div class="col-md-4 second col-sm-12">
                        </div>
                        <div class="col-md-4 col-sm-12"><br></div>
                        <div class="col-md-4 second col-sm-12">
                            <button type="button" id="send" class="btn btn-sm btn-outline-danger" aria-label="Left Align">
                                Eliminar seleccionados
                            </button>
                        </div>
                    </div>

                    <hr>
                    <table id="tickets" cellspacing="0" class="table table-bordered dt-responsive">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Bartender</th>
                            <th>Fecha de envío</th>
                            <th>Estado</th>
                            <th>Revisar</th>
                        </tr>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'

    export default {
        name: "admin",
        props: ['user', 'message'],
        data() {
            return {
                error: null,
                all: null,
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        },
        methods: {
            getTickets: function () {
                $('#tickets').DataTable({
                    ajax: {
                        url: '/api/tickets',
                        dataSrc: 'tickets',
                    },
                    scrollY: "300px",
                    responsive: true,
                    columns: [
                        { data: 'ID_RECIBO', render:function (data, type, row, meta) {
                                return "<div class='form-check mb-2'><input class='form-check-input' name='recibo_id[]' type='checkbox' value='" + data + "' ></div>";
                            }},
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
                                return '<a class="btn btn-link btn-sm waves-effect text-primary" href="/admin/recibo/' + data + '">Revisar</a>'
                            }},
                    ],
                    aaSorting: [ [0,'desc'] ],
                    language: {
                        search: "Buscar:",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        },
                        info: "Mostrando _START_ a _END_ de _TOTAL_ tickets",
                        lengthMenu:    "Mostrar _MENU_ tickets",
                        loading: "Cargando"
                    }
                });
            }
        },
        mounted () {
            $().ready(() => {
                if (this.message.length > 0) {
                    swal({ title: "", text: this.message, button: "Entendido", icon: "success", timer:3000 });
                }

                this.getTickets();

                $('#send').on('click', function (e) {
                    event.preventDefault();
                    let numberOfChecked = $('input:checkbox:checked').length;
                    let value = $("#actions").val();
                    if (numberOfChecked > 0) {
                        swal({
                            title: "Confirmar acción",
                            text: "Realmente deseas eliminar las tickets seleccionadas",
                            icon: "error",
                            buttons: ["Cancelar", "Eliminar"],
                        }).then((value) => {
                            if (value) {
                                $("#form").submit();
                            }
                        });
                    } else {
                        swal({ title: "", text: "Selecciona al menos un ticket", button: "Entendido" });
                    }
                });
            });
        }
    }
</script>

<style scoped>

</style>