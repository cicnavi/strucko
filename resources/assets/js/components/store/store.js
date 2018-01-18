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
            // First we will try to get languages from IDB.
            if ( idb ) {

                let openDBRequest = idb.open();

                openDBRequest.onsuccess = function(openDatabaseEvent) {
                    // Save DB instance.
                    let db = openDatabaseEvent.target.result;
                    // Save object store for timestamps.
                    let timestampsObjectStore = db.transaction('timestamps', 'readonly').objectStore('timestamps');
                    // Save current timestamp so we can compare it to the one from the store.
                    const currentTimestamp = new Date();
                    // Get the timestamp for the languages (when the languages were lastly updated).
                    const languagesTimestampRequest = timestampsObjectStore.get('languages');

                    languagesTimestampRequest.onerror = function () {
                        context.dispatch('getLanguagesFromApi');
                    };

                    languagesTimestampRequest.onsuccess = function (event) {
                        // Store the actual value and make new Date instance from it.
                        let languagesTimestamp = null;
                        if (event.target.result) {
                            languagesTimestamp = new Date(event.target.result.value);
                        }
                        // We will store languages in IDB on a daily basis. So, if the IDB date is different
                        // from current date, update languages from the API.
                        if ( ! languagesTimestamp || languagesTimestamp.getDate() != currentTimestamp.getDate() ) {
                            context.dispatch('getLanguagesFromApi');
                        } else {
                            // Check if there are any languages in the IDB.
                            let languagesObjectStore = db.transaction('languages', 'readonly').objectStore('languages');
                            let countRequest = languagesObjectStore.count();
                            countRequest.onsuccess = function(countEvent) {
                                // If there is at least two languages, use them.
                                if (countEvent.target.result > 1) {
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
                                } else {
                                    // There is not enough languages in the store.
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
                // IDB is not available, so get the languages from API.
                context.dispatch('getLanguagesFromApi');
            }
        },
        getLanguagesFromApi(context) {

            console.log('Getting languages from API');

            axios.get('api/v1/languages')
                .then(function (response) {
                    // Save languages to the store.
                    context.commit('setLanguages', {languages: response.data});

                    // If IDB is available, save the languages to it.
                    if ( idb ) {
                        let openDBRequest = idb.open();
                        openDBRequest.onsuccess = function(event) {
                            console.log('Storing languages in IDB');
                            let db = event.target.result;
                            let languagesObjectStore = db.transaction('languages', 'readwrite').objectStore('languages');
                            // First clear existing data.
                            languagesObjectStore.clear().onsuccess = function (event) {
                                // Now add languages from the API to the IDB.
                                const objectStore  = event.target.source;
                                for (const language of response.data) {
                                    objectStore.add(language);
                                }
                            };

                            // Update the timestamp so we know when we have updated languages in IDB.
                            let timestampsObjectStore = db.transaction('timestamps', 'readwrite').objectStore('timestamps');
                            timestampsObjectStore.put({id: 'languages', value: new Date().toISOString()});
                        };
                    }
                })
                .catch(function (error) {
                    console.log('Could not retrieve languages from API');
                });
        }
    }
});