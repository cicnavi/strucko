import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import { idb } from './idb';

export const store = new Vuex.Store({
    state: {
        languages: [],
        languagesLoaded: false
    },
    mutations: {
        setLanguages(state, payload) {
            state.languages = payload.languages;
            state.languagesLoaded = true;
        }
    },
    actions: {
        setLanguages(context){
            if ( idb ) {
                // indexDB exists, so we will try and use it.
                let openDBRequest = idb.open();

                openDBRequest.onsuccess = function(openDatabaseEvent) {
                    // Open object store for languages.
                    let db = openDatabaseEvent.target.result;
                    let timestampsObjectStore = db.transaction('timestamps', 'readonly').objectStore('timestamps');

                    const currentTimestamp = new Date();

                    const languagesTimestampRequest = timestampsObjectStore.get('languages');

                    languagesTimestampRequest.onerror = function () {
                        context.dispatch('getLanguagesFromApi');
                    };

                    languagesTimestampRequest.onsuccess = function (event) {
                        const languagesTimestamp = new Date(event.target.result.value);
                        if ( ! languagesTimestamp || languagesTimestamp.getDate() != currentTimestamp.getDate() ) {
                            context.dispatch('getLanguagesFromApi');
                        } else {
                            // If there are some languages, use them instead of getting them from api.
                            let languagesObjectStore = db.transaction('languages', 'readonly').objectStore('languages');
                            let countRequest = languagesObjectStore.count();
                            countRequest.onsuccess = function(countEvent) {
                                if (countEvent.target.result > 0) {
                                    console.log('Getting languages from IDB');
                                    let idbLanguages = [];
                                    countEvent.target.source.openCursor().onsuccess = function (openCursorEvent) {
                                        let cursor = openCursorEvent.target.result;
                                        if (cursor) {
                                            idbLanguages.push(cursor.value);
                                            cursor.continue();
                                        }
                                        else {
                                            context.commit('setLanguages', {languages: idbLanguages});
                                        }
                                    }
                                }
                                else {
                                    context.dispatch('getLanguagesFromApi');
                                }
                            }
                        }
                    };
                };

                openDBRequest.onerror = function (openDatabaseEvent) {
                    context.dispatch('getLanguagesFromApi');
                }
            } else {
                context.dispatch('getLanguagesFromApi');
            }
        },
        getLanguagesFromApi(context) {

            console.log('Getting languages from API');

            axios.get('api/v1/languages')
                .then(function (response) {
                    context.commit('setLanguages', {languages: response.data});

                    if (idb) {
                        let openDBRequest = idb.open();
                        openDBRequest.onsuccess = function(event) {
                            console.log('Storing languages in IDB');
                            let db = event.target.result;
                            let languagesObjectStore = db.transaction('languages', 'readwrite').objectStore('languages');
                            languagesObjectStore.clear().onsuccess = function (event) {
                                const objectStore  = event.target.source;
                                for (const language of response.data) {
                                    objectStore.add(language);
                                }
                            };

                            let timestampsObjectStore = db.transaction('timestamps', 'readwrite').objectStore('timestamps');
                            timestampsObjectStore.put({id: 'languages', value: new Date()});
                        };
                    }
                })
                .catch(function (error) {
                    console.log('Could not retrieve languages from API');
                });
        }
    }
});