import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import SearchForm from '../components/SearchForm';
import SearchResults from '../components/SearchResults';

export const router = new VueRouter({
    routes: [
        {
            path: '/',
            name: 'home',
            component: SearchForm,
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
                }
            ]
        }
    ]
});