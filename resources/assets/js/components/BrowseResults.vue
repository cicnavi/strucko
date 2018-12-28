<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="row" v-if="! status.error && ! browseInProgress">
                <div class="col-xs-12">
                    <div class="row" >
                        <div class="col-xs-12">
                            <div v-if="results.total > 0">
                                <div class="row">
                                    <div class="col-xs-12">

                                        <h2>
                                            Browsing letter <em>{{ this.letter }}</em>
                                        </h2>

                                        <div class="table-responsive">
                                          <table class="table table-striped">
                                            <thead v-if="languagesLoaded == true">
                                              <tr>
                                                <th>
                                                  {{ this.$store.getters.getLanguageById(this.language_id).ref_name }}
                                                  /
                                                  {{ this.$store.getters.getLanguageById(this.translate_to).ref_name }}
                                                </th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              <tr v-for="(term, index) in results.data">
                                                <td>
                                                  {{ term.term}}
                                                  <span v-if="term.translations.length > 0">
                                                    /
                                                    <span v-for="(translation, index) in term.translations">
                                                      {{ translation.translation.term }}<span v-if="index < (term.translations.length - 1)">, </span>
                                                    </span>
                                                  </span>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
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

                </div>
            </div>
            <div class="row" v-else>
                <div class="col-xs-12">
                    <p class="text-danger text-center">
                        {{ status.userMessage }}
                    </p>
                </div>
            </div>

            <div class="row" v-if="browseInProgress">
                <div class="col-xs-12">
                    <p class="text-center">Loading</p>
                    <div class="loader"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "browse-results",
        data: function () {
            return {
                status: {
                    error: false,
                    errorMessage: '',
                    userMessage: ''
                },
                browseInProgress: false,
                results: {
                    toral: 0
                },
                currentPage: this.page
            }
        },
        props: ['language_id', 'translate_to', 'letter', 'page'],
        methods: {
            setStatus(error, errorMessage = '', userMessage = '') {
                this.status.error = error;
                this.status.errorMessage = errorMessage;
                this.status.userMessage = userMessage;
            },
            setBrowseParams() {
                this.$store.commit('setBrowseParams', {
                    letter: this.letter,
                    page: this.currentPage
                });
            },
            setLanguageParams() {
                this.$store.commit('setLanguageParams', {
                    language_id: this.language_id,
                    translate_to: this.translate_to
                });
            },
            browse() {

                this.setBrowseParams();
                this.setLanguageParams();

                if ( ! this.goodToGo) {
                    this.setStatus(
                        true,
                        'State is not good to go!',
                        'Please check if browseParams are set.'
                    );
                    return;
                } else {
                    this.setStatus(false);
                    this.browseInProgress = true;
                }

                let app = this;

                axios.get('api/v1/browse', {
                    params: {
                        ...app.$store.state.browseParams,
                        ...app.$store.state.languageParams
                    }
                })
                .then(function (response) {
                    app.browseInProgress = false;
                    app.results = response.data.terms;
                })
                .catch(function (error) {
                    app.browseInProgress = false;
                    app.setStatus(
                        true,
                        'API error',
                        'There was an error trying to browse for terms, please try again later.'
                    );
                });
            }
        },
        computed: {
            goodToGo() {
                return this.$store.getters.browseIsGoodToGo;
            },
            languagesLoaded() {
              return this.$store.state.languagesLoaded;
            }
        },
        created() {
            this.browse();
        },
        watch: {
            '$route': 'browse'
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
