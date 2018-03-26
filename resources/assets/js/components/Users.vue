<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="col-md-2 col-sm-8 offset-md-10 ">
                    <a class="btn btn-block btn-success" href="/users/create">Crear usuario</a>
                </div>

                <hr>
                <table id="users" cellspacing="0" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Teléfono</th>
                            <th>Email</th>
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
                    columns: [
                        { data: 'name' },
                        { data: 'last_name' },
                        { data: 'phone' },
                        { data: 'email' },
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