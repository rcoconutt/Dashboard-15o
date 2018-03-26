<template>
    <li class="nav-item dropdown">
        <a class="nav-link " href="#" id="badgeNotificaciones" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#fff" >
            Notificaciones
            <span class="badge badge-light">{{ total }}</span>
        </a>

        <div class="dropdown-menu" id="notificaciones" aria-labelledby="supportedContentDropdown">
            <!--  MOSTRAR NOTIFICACIONES  -->
            <div v-if="notificaciones.length === 0" href="#" class="notidicacion-link" style="margin: 12px" >
                <strong class="text-muted">Sin Notificaciones</strong>
            </div>
            <div v-else>
                <strong style="margin-left: 12px">Notificaciones</strong>:
                <hr>
                <div v-for="notificacion in notificaciones">
                    <a href="#" class="notidicacion-link text-secondary">
                        <div class="notificacion">
                            {{ notificacion.message }}

                            <br>
                            <div style="font-size: 10px">hace {{ notificacion.diff }} horas</div>
                            <hr>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </li>
</template>

<script>
    import moment from 'moment'
    export default {
        name: "notificaciones",
        props: ['user'],
        data () {
            return {
                total: 0,
                notificaciones: []
            }
        },
        methods: {
            getNotifications: function () {
                axios.get('/api/notificaciones/' + this.user.id + '/0')
                    .then((response) => {
                        response.data.notificaciones.forEach((notificacion) => {
                            notificacion.diff = Math.ceil(moment.duration(moment().diff(moment(notificacion.created_at))).asHours());
                            this.notificaciones.push(notificacion);
                        });

                        this.total = response.data.notificaciones.length;
                    })
            }
        },
        mounted () {
            this.getNotifications()
        }
    }
</script>

<style scoped>
    .notificacion {
        max-width: 300px;
        width: 290px;
        margin-left: 15px;
        margin-bottom: 7px;
    }
    .notidicacion-link {
        text-decoration: none !important
    }
</style>