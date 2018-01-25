<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div v-if="languagesLoaded">
                        <form >
                            <div class="form-row">
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Search in</label>
                                        <select class="form-control" v-model="params.language_id">
                                            <option value="" disabled>Select language</option>
                                            <option v-for="language in languages" :value="language.id">{{ language.ref_name }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Translate to</label>
                                        <select class="form-control" v-model="params.translate_to">
                                            <option value="" disabled>Select language</option>
                                            <option v-for="language in languages" :value="language.id">{{ language.ref_name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-sm-10 col-xs-12">
                                    <input type="text"
                                           class="form-control input-lg"
                                           placeholder="Enter term"
                                           aria-describedby="termHelp"
                                           v-model="params.term">
                                    <small id="termHelp"
                                           class="form-text text-muted"
                                           :class="{'text-danger': status.termNotEntered}">Enter the term to search for.</small>
                                </div>
                                <div class="form-group col-sm-2 col-xs-12">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" @click.prevent="search">Go</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div v-else>
                        <p class="text-center">Please wait while we prepare data...</p>
                        <div class="loader"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12" v-if="status.searchInProgress">
                    <p class="text-center">Searching...</p>
                    <div class="loader"></div>
                </div>
                <div class="col-xs-12" v-else>
                    <search-results :status="status"
                                    :results="results"></search-results>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LanguagesSelectInput from './form-elements/LanguagesSelectInput';
    import SearchResults from './SearchResults';
    export default {
        name: "search-form",
        data: function () {
            return {
                name: 'search-form',
                params: {
                    term: 'server',
                    language_id: 'eng',
                    translate_to: 'hrv',
                    scientific_field_id: 19,
                },
                status: {
                    searchInProgress: false,
                    resultsReturned: false,
                    apiError: false,
                    termNotEntered: false
                },
                results: {
                    exactMatch: null,
                    similarTerms: []
                }
            }
        },
        methods: {
            search() {

                if ( ! this.params.term) {
                    this.status.termNotEntered = true;
                    return;
                } else {
                    this.status.termNotEntered = false;
                    this.status.resultsReturned = false;
                    this.status.searchInProgress = true;
                }

                let app = this;

                axios.get('api/v1/search', {
                    params: {
                        ...app.params
                    }

                })
                .then(function (response) {
                    app.status.apiError = false;
                    app.status.searchInProgress = false;
                    app.status.resultsReturned = true;
                    app.results = response.data;
                })
                .catch(function (error) {
                    app.status.apiError = true;
                    app.status.searchInProgress = false;
                    app.status.resultsReturned = false;
                    console.log('Error: ', error);
                });
            }
        },
        components: {
            LanguagesSelectInput, SearchResults
        },
        computed: {
            languagesLoaded() {
                return this.$store.state.languagesLoaded;
            },
            languages() {
                return this.$store.state.languages;
            }
        }
    }
</script>

<style scoped>
    .loader {
        margin: 0 auto;
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>