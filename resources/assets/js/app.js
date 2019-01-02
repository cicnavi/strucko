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

import Home from './components/Home';

const app = new Vue({
    el: '#app',
    store,
    router,
    data: {
        appName: 'Strucko'
    },
    components: {
        Home
    },
    methods: {

    },
    created: function () {
        store.dispatch('setLanguages')
          .then(function(response){
            store.dispatch('getLettersFromApi')
              .then(function(response){console.log('Letters loaded.')})
              .catch(function(error){console.error('Letters could not be loaded.')});
          })
          .catch(function(error){
            console.error('Languages could not be set.');
          });
    }
});
// TODO implement other data when defining event
// https://developers.google.com/analytics/devguides/collection/analyticsjs/events
if (typeof ga === 'function') {
    ga('set', 'page', router.currentRoute.path);
    ga('send', 'pageview');

    router.afterEach(( to, from ) => {
        ga('set', 'page', to.fullPath);
        ga('send', 'pageview');
    });
}
