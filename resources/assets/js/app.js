
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Autocomplete from 'vuejs-auto-complete'
import Chart from 'chart.js';

Vue.component('dinamicas', require('./components/Dinamicas'));
Vue.component('dinamicas-admin', require('./components/DinamicasAdmin'));
Vue.component('dinamicas-gerente', require('./components/DinamicasGerente'));
Vue.component('dinamicas-supervisor', require('./components/DinamicasSupervisor'));
Vue.component('dinamicas-embajador', require('./components/DinamicasEmbajador'));
Vue.component('create-dinamica', require('./components/CreateDinamica'));
Vue.component('users', require('./components/Users'));
Vue.component('notificaciones', require('./components/Notificaciones'));
Vue.component('users-admin', require('./components/UsersAdmin'));
Vue.component('admin', require('./components/admin'));

// Zonas
Vue.component('zonas-index', require('./components/zonas/ZonasIndex'));
Vue.component('zonas-create', require('./components/zonas/ZonasCreate'));
Vue.component('zonas-update', require('./components/zonas/ZonasUpdate'));

// Centros de consumo
Vue.component('venues-index', require('./components/venues/VenuesIndex'));
Vue.component('venues-create', require('./components/venues/VenuesCreate'));
Vue.component('venues-update', require('./components/venues/VenuesUpdate'));

// Destilados
Vue.component('destilados-index', require('./components/destilados/DestiladosIndex'));
Vue.component('destilados-update', require('./components/destilados/DestiladosUpdate'));

Vue.component(Autocomplete);

const app = new Vue({
    el: '#app'
});
