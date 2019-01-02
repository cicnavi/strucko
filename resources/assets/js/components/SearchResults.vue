<template>
    <div class="row">
        <div class="col-xs-12" id="searchResults">
            <div class="row" v-if="! status.error && ! searchInProgress">
                <div class="col-xs-12">
                    <div class="row" >
                        <div class="col-xs-12">
                            <div v-if="results.exactMatch">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h3 v-if="languagesLoaded == true">
                                            <em>{{ this.$store.getters.getLanguageById(this.language_id).ref_name }}</em>
                                        </h3>
                                        <h2>
                                            {{ results.exactMatch.term }}
                                            <small>
                                                <em>
                                                    ({{ results.exactMatch.part_of_speech.part_of_speech.toLowerCase() }})
                                                </em>
                                            </small>
                                        </h2>
                                        <p v-for="definition in results.exactMatch.definitions">
                                            {{ definition.definition }}
                                            <small style="font-style: italic;">
                                                <a :href="definition.link" :title="definition.source" target="_blank">
                                                    source
                                                </a>
                                            </small>
                                        </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <h3 v-if="languagesLoaded == true">
                                            <em>{{ this.$store.getters.getLanguageById(this.translate_to).ref_name }}</em>
                                        </h3>
                                        <div v-if="results.exactMatch.translations.length > 0">
                                            <div v-for="(translation, index) in results.exactMatch.translations">
                                                <h2>
                                                    {{ translation.translation.term }}
                                                </h2>
                                                <p v-for="definition in translation.translation.definitions">
                                                    {{ definition.definition }}
                                                    <small style="font-style: italic;">
                                                        <a :href="definition.link" :title="definition.source" target="_blank">
                                                            source
                                                        </a>
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <p class="text-info">No translations...</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">

                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-info text-center">
                                <h4>No results...</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row" v-if="results.similarTerms.length > 0">

                        <div class="col-xs-12">
                            <hr>
                            <h4>Similar terms</h4>
                            <p v-for="(term, index) in results.similarTerms">
                                {{ term.term }}
                                <small>
                                    <em>
                                        ({{ term.part_of_speech.part_of_speech.toLowerCase() }})
                                    </em>
                                </small>
                                <em v-for="(translation, index) in term.translations">
                                    {{ translation.translation.term }}<span v-if="index != (term.translations.length - 1)">,</span>
                                </em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-else>
                <div class="col-xs-12">
                    <p class="text-danger text-center">
                        {{ status.userMessage }}
                    </p>
                </div>
            </div>

            <div class="row" v-if="searchInProgress">
                <div class="col-xs-12">
                    <loader-animation></loader-animation>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import LoaderAnimation from './LoaderAnimation';

    export default {
        name: "search-results",
        components: {
            LoaderAnimation
        },
        data: function () {
            return {
                status: {
                    error: false,
                    errorMessage: '',
                    userMessage: ''
                },
                searchInProgress: false,
                results: {
                    exactMatch: null,
                    similarTerms: []
                }
            }
        },
        props: ['language_id', 'translate_to', 'term', 'setMode'],
        methods: {
            setStatus(error, errorMessage = '', userMessage = '') {
                this.status.error = error;
                this.status.errorMessage = errorMessage;
                this.status.userMessage = userMessage;
            },
            setSearchParams() {
                this.$store.commit('setSearchParams', {
                    term: this.term
                });
            },
            setLanguageParams() {
                this.$store.commit('setLanguageParams', {
                    language_id: this.language_id,
                    translate_to: this.translate_to
                });
            },
            search() {

                this.setSearchParams();
                this.setLanguageParams();
                this.setMode('search');

                if ( ! this.goodToGo) {
                    this.setStatus(
                        true,
                        'State is not good to go!',
                        'Please select appropriate option and enter term.'
                    );
                    return;
                } else {
                    this.setStatus(false);
                    this.searchInProgress = true;
                }

                let app = this;

                axios.get('api/v1/search', {
                    params: {
                        ...app.$store.state.searchParams,
                        ...app.$store.state.languageParams
                    }
                })
                .then(function (response) {
                    app.searchInProgress = false;
                    app.results = response.data;
                })
                .catch(function (error) {
                    app.searchInProgress = false;
                    app.setStatus(
                        true,
                        'API error',
                        'There was an error trying to search for a term, please try again later.'
                    );
                });
            }
        },
        computed: {
            goodToGo() {
                return this.$store.getters.searchIsGoodToGo;
            },
            languagesLoaded() {
              return this.$store.state.languagesLoaded;
            }
        },
        mounted() {
            this.search();
        },
        watch: {
            '$route': 'search'
        }

    }
</script>

<style scoped>

</style>
