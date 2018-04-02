
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Autocomplete from 'vuejs-auto-complete'

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
Vue.component(Autocomplete);

const app = new Vue({
    el: '#app'
});
