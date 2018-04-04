<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 col-sm-12 offset-md-5">
                <a class="btn btn-block btn-mdb-color" href="/users/create">Crear usuario</a>
            </div>
            <div class="col-md-12">
                <hr>
                <table id="users" cellspacing="0" class="table table-bordered table-hover dt-responsive" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "users",
        props: ['brand'],
        methods: {
            getUsers: function () {
                $('#users').DataTable({
                    ajax: {
                        url: '/api/users/' + this.brand,
                        dataSrc: 'users',
                    },
                    scrollY: "300px",
                    responsive: true,
                    columns: [
                        { data: 'name' },
                        { data: 'last_name' },
                        { data: 'phone' },
                        { data: 'email' },
                        { data: 'rol', render: function ( data, type, row, meta ) {
                                if (data == 1) {
                                    return "Gerente";
                                }

                                if (data == 2) {
                                    return "Supervisor";
                                }

                                if (data == 3) {
                                    return "Embajador";
                                }

                                if (data == 4) {
                                    return "Administrador Tickets";
                                }

                                return "";
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
                        info: "Mostrando _START_ a _END_ de _TOTAL_ usuarios",
                        lengthMenu:    "Mostrar _MENU_ usuarios",
                        loading: "Cargando"
                    }
                });
            }
        },
        mounted () {
            $().ready(() => {
                this.getUsers()
            });
        }
    }
</script>

<style scoped>

</style>