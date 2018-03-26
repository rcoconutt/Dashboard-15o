
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Autocomplete from 'vuejs-auto-complete'

Vue.component('dinamicas', require('./components/Dinamicas'));
Vue.component('create-dinamica', require('./components/CreateDinamica'));
Vue.component(Autocomplete);

const app = new Vue({
    el: '#app'
});
