import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import { idb } from './idb';

export const store = new Vuex.Store({
    strict: true,
    state: {
        languages: [],
        languagesLoaded: false,
        letters: [],
        lettersLoaded: false,
        scientific_field_id: 19,
        languageParams: {
            language_id: 'eng',
            translate_to: 'hrv',
        },
        searchParams: {
            term: ''
        },
        browseParams: {
            letter: '',
            page: 1
        },
        /*
        start - default, start page
        browse - browsing by using letters
        search - searching by using search input field
        term - display for single term
        */
        mode: 'start'
    },
    getters: {
        searchIsGoodToGo: state => {
            return (
                state.languageParams.language_id &&
                state.languageParams.translate_to &&
                state.searchParams.term &&
                state.scientific_field_id
            );
        },
        browseIsGoodToGo: state => {
            return (
                state.languageParams.language_id &&
                state.languageParams.translate_to &&
                state.browseParams.letter &&
                state.browseParams.page &&
                state.scientific_field_id
            );
        },
        getLanguageById: (state) => (languageId) => {
            return state.languages.find(language => language.id === languageId);
        }
    },
    mutations: {
        setLanguages(state, payload) {
            state.languages = payload.languages;
            state.languagesLoaded = true;
        },
        setLetters(state, payload) {
          state.letters = payload.letters;
        },
        setLettersLoaded(state, payload) {
          state.lettersLoaded = payload.lettersLoaded;
        },
        setSearchParams(state, payload) {
            state.searchParams.term = payload.term;
        },
        setBrowseParams(state, payload) {
            state.browseParams.letter = payload.letter;
            state.browseParams.page = payload.page;
        },
        setLanguageParams(state, payload) {
            state.languageParams.language_id = payload.language_id;
            state.languageParams.translate_to = payload.translate_to;
        },
        setLanguageParamsLanguageId(state, value) {
            state.languageParams.language_id = value;
        },
        setLanguageParamsTranslateTo(state, value) {
            state.languageParams.translate_to = value;
        },
        setSearchParamsTerm(state, value) {
            state.searchParams.term = value;
        },
        setMode(state, value) {
            state.mode = value;
        }
    },
    actions: {
        setLanguages(context){
            // First we will try to get languages from IDB.
            return new Promise((resolve, reject) => {
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
                          context.dispatch('getLanguagesFromApi')
                            .then(function(response){resolve(response);})
                            .catch(function(error){reject(error);});
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
                              context.dispatch('getLanguagesFromApi')
                                .then(function(response){resolve(response);})
                                .catch(function(error){reject(error);});;
                          } else {
                              // Check if there are any languages in the IDB.
                              let languagesObjectStore = db.transaction('languages', 'readonly').objectStore('languages');
                              let countRequest = languagesObjectStore.count();
                              countRequest.onsuccess = function(countEvent) {
                                  // If there are at least two languages, use them.
                                  if (countEvent.target.result > 1) {
                                      console.log('Getting languages from IDB');
                                      let idbLanguages = [];
                                      languagesObjectStore.index('ref_name').openCursor().onsuccess = function (openCursorEvent) {
                                          let cursor = openCursorEvent.target.result;
                                          if (cursor) {
                                              idbLanguages.push(cursor.value);
                                              cursor.continue();
                                          }
                                          else {
                                              context.commit('setLanguages', {languages: idbLanguages})
                                              resolve(idbLanguages);
                                          }
                                      }
                                  } else {
                                      // There is not enough languages in the store.
                                      context.dispatch('getLanguagesFromApi')
                                        .then(function(response){resolve(response);})
                                        .catch(function(error){reject(error);});;
                                  }
                              }
                          }
                      };
                  };

                  openDBRequest.onerror = function (openDatabaseEvent) {
                      context.dispatch('getLanguagesFromApi')
                        .then(function(response){resolve(response);})
                        .catch(function(error){reject(error);});;
                  }
              } else {
                  // IDB is not available, so get the languages from API.
                  context.dispatch('getLanguagesFromApi')
                    .then(function(response){resolve(response);})
                    .catch(function(error){reject(error);});;
              }
            }); // end new Promise
        },
        getLanguagesFromApi(context) {

            console.log('Getting languages from API');
            return new Promise((resolve, reject) => {
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
                      resolve(response);
                  })
                  .catch(function (error) {
                      console.log('Could not retrieve languages from API');
                      reject(error);
                  });
            });

        },
        getLettersFromApi(context) {
          return new Promise((resolve, reject) => {
            if ( ! context.state.languagesLoaded ) {
              console.error('Error: lanugages are not yet loaded. Default value used.');
            }
            // TODO consider using idb caching
            axios.get('api/v1/languages/' + context.state.languageParams.language_id + '/letters')
                .then(function (response) {
                    context.commit('setLetters', {letters: response.data});
                    context.commit('setLettersLoaded', {lettersLoaded: true});
                    resolve(response);
                })
                .catch(function (error) {
                    context.commit('setLetters', {letters: []});
                    context.commit('setLettersLoaded', {lettersLoaded: false});
                    reject(error);
                });
          });
        }
    }
});
