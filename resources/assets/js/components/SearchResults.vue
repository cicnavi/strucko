<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="row" v-if="! status.error && ! searchInProgress">
                <div class="col-xs-12">
                    <div class="row" >
                        <div class="col-xs-12">
                            <div v-if="results.exactMatch">
                                <h2>
                                    {{ results.exactMatch.term }}
                                    <small>
                                        <em v-for="(translation, index) in results.exactMatch.translations">
                                            {{ translation.translation.term }}<span v-if="index != ( results.exactMatch.translations.length - 1)">,</span>
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
                    <p class="text-center">Searching</p>
                    <div class="loader"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "search-results",
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
        props: ['language_id', 'translate_to', 'term'],
        methods: {
            setStatus(error, errorMessage = '', userMessage = '') {
                this.status.error = error;
                this.status.errorMessage = errorMessage;
                this.status.userMessage = userMessage;
            },
            setSearchParams() {
                this.$store.commit('setSearchParams', {
                    language_id: this.language_id,
                    translate_to: this.translate_to,
                    term: this.term
                });
            },
            search() {

                this.setSearchParams();

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
                        ...app.$store.state.searchParams
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
            }
        },
        created() {
            this.search();
        },
        watch: {
            '$route': 'search'
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