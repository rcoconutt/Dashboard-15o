<template>
    <li class="nav-item dropdown notifications-nav">
        <a class="nav-link dropdown-toggle waves-effect" id="badgeNotificaciones" data-toggle="dropdown" aria-haspopup="true"
           aria-expanded="false">
            <span class="badge red">{{ total }}</span>
            Notificaciones
        </a>
        <div class="dropdown-menu dropdown-info notificacion-container" id="notificaciones" aria-labelledby="supportedContentDropdown">
            <!--  MOSTRAR NOTIFICACIONES  -->
            <div v-if="notificaciones.length === 0" href="#" class="notificacion-link" style="margin: 12px" >
                <strong class="text-muted">Sin Notificaciones</strong>
            </div>
            <div v-else>
                <strong style="margin-left: 12px">Notificaciones</strong>:
                <hr>
                <div v-for="notificacion in notificaciones">
                    <a href="#" @click="read( notificacion.id )" class="notificacion-link waves-effect text-secondary">
                        <div class="notificacion">
                            {{ notificacion.message }}

                            <br>
                            <div style="font-size: 10px">hace {{ notificacion.diff }}</div>
                            <br>
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
                        this.notificaciones = [];
                        response.data.notificaciones.forEach((notificacion) => {
                            let minutes = Math.floor(moment.duration(moment().diff(moment(notificacion.created_at))).asMinutes());
                            let diff = Math.floor(moment.duration(moment().diff(moment(notificacion.created_at))).asHours());

                            if (diff > 23) {
                                if (diff > 743) {
                                    let meses = Math.floor((diff / 744));

                                    if (meses < 2) {
                                        notificacion.diff = meses + " mes";
                                    } else {
                                        notificacion.diff = meses + " meses";
                                    }
                                } else {
                                    let horas = Math.floor((diff / 24));

                                    if (horas < 2) {
                                        notificacion.diff = horas + " día";
                                    } else {
                                        notificacion.diff = horas + " días";
                                    }
                                }
                            } else {
                                if (diff === 0) {
                                    if (minutes === 1) {
                                        notificacion.diff = minutes + " minuto"
                                    } else {
                                        notificacion.diff = minutes + " minutos"
                                    }
                                } else {
                                    if (diff === 1) {
                                        notificacion.diff = diff + " hora"
                                    } else {
                                        notificacion.diff = diff + " horas"
                                    }
                                }
                            }
                            this.notificaciones.push(notificacion);
                        });

                        this.total = response.data.notificaciones.length;
                    })
            },
            read: function (id) {
                axios.get('/api/notificaciones/' + id + '/read')
                    .then((response) => {
                        this.getNotifications()
                    })
            }
        },
        mounted () {
            this.getNotifications()

            setInterval(() => { this.getNotifications() }, 60000);
        }
    }
</script>

<style scoped>
    .notificacion {
        max-width: 300px;
        width: 290px;
        margin-left: 15px;
    }
    .notificacion-link {
        text-decoration: none !important
    }
    .notificacion-container {
        overflow-y: auto; height:400px;
    }
</style>