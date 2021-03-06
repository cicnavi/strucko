import Vue from 'vue';
import VueRouter from 'vue-router';
import VueMeta from 'vue-meta'

Vue.use(VueRouter);
Vue.use(VueMeta);

import Home from '../components/Home';
import SearchForm from '../components/SearchForm';
import SearchResults from '../components/SearchResults';
import BrowseForm from '../components/BrowseForm';
import BrowseResults from '../components/BrowseResults';
import About from '../components/pages/About';
import TermsOfUse from '../components/pages/TermsOfUse';
import Privacy from '../components/pages/Privacy';
import Cookies from '../components/pages/Cookies';
import Disclaimer from '../components/pages/Disclaimer';
import NotFound from '../components/pages/NotFound';

export const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'active',
    scrollBehavior (to, from, savedPosition) {
        if (to.hash) {
            return {
              selector: to.hash
            }
        }
        else if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    },
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
            children: [
                {
                    path: 'search/:language_id/:translate_to',
                    name: 'search',
                    component: SearchResults,
                    props: (route) => {
                        return {
                            language_id: route.params.language_id,
                            translate_to: route.params.translate_to,
                            term: route.query.term
                        };
                    }
                },
                {
                    path: 'browse/:language_id/:letter/:translate_to',
                    name: 'browse',
                    component: BrowseResults,
                    props: (route) => {
                        return {
                            language_id: route.params.language_id,
                            letter: route.params.letter,
                            translate_to: route.params.translate_to,
                            page: route.query.page
                        };
                    }
                }
            ]
        },
        {path: '/about', name: 'about', component: About},
        {path: '/tou', name: 'tou', component: TermsOfUse},
        {path: '/privacy', name: 'privacy', component: Privacy},
        {path: '/cookies', name: 'cookies', component: Cookies},
        {path: '/disclaimer', name: 'disclaimer', component: Disclaimer},
        {path: '/*', name: 'not-found', component: NotFound},
    ],

});
