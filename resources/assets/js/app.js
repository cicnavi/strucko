/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import { store } from "./store/store";
import { router } from "./router/router";

import SearchForm from './components/SearchForm';

const app = new Vue({
    el: '#app',
    store,
    router,
    data: {
        appName: 'Strucko app'
    },
    components: {
        SearchForm
    },
    methods: {

    },
    created: function () {
        store.dispatch('setLanguages');
    }
});