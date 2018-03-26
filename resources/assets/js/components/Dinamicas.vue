<template>
    <table id="dinamicas" cellspacing="0" class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Dinamica</th>
            <th>Descripción</th>
            <th>Premio</th>
            <th>Fecha de termino</th>
        </tr>
        </thead>
    </table>
</template>

<script>
    export default {
        name: "dinamicas",
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
                        { data: 'DESCRIPCION' },
                        { data: 'PREMIO'},
                        { data: 'FECHA_FIN' },
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