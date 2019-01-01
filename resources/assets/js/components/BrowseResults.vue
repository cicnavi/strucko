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

                                        <div >
                                          <table class="table table-striped">
                                            <thead v-if="languagesLoaded == true">
                                              <tr>
                                                <th>
                                                  {{ this.$store.getters.getLanguageById(this.language_id).ref_name }}
                                                  /
                                                  <em>{{ this.$store.getters.getLanguageById(this.translate_to).ref_name }}</em>
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
                                                      <em>{{ translation.translation.term }}</em><span v-if="index < (term.translations.length - 1)">, </span>
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
                                    <div class="col-xs-12 text-center">
                                      <nav aria-label="Browse results pagination menu">
                                        <ul class="pagination pagination-sm">
                                          <li v-if="results.current_page > 1">
                                            <a href="#" aria-label="Previous" @click.prevent="goToPage(results.current_page - 1)">
                                              <span aria-hidden="true">&laquo;</span>
                                            </a>
                                          </li>
                                          <li :class="{disabled: results.current_page == 1}">
                                            <a href="#" @click.prevent="goToPage(1)">1</a>
                                          </li>
                                          <li class="disabled" v-if="results.current_page > 3">
                                            <a disabled>...</a>
                                          </li>
                                          <li v-if="results.current_page > 2">
                                            <a href="#" @click.prevent="goToPage(results.current_page - 1)">
                                              {{ results.current_page - 1 }}
                                            </a>
                                          </li>
                                          <li class="disabled"
                                            v-if="results.current_page > 1 && results.current_page < results.last_page">
                                            <a >{{ results.current_page }}</a>
                                          </li>
                                          <li v-if="results.current_page < (results.last_page - 1)">
                                            <a href="#" @click.prevent="goToPage(results.current_page + 1)">
                                              {{ results.current_page + 1 }}
                                            </a>
                                          </li>
                                          <li class="disabled" v-if="results.current_page < (results.last_page - 2)">
                                            <a disabled>...</a>
                                          </li>
                                          <li :class="{disabled: results.current_page == results.last_page}"
                                            v-if="results.last_page > 1">
                                            <a href="#" @click.prevent="goToPage(results.last_page)">
                                              {{ results.last_page }}
                                            </a>
                                          </li>
                                          <li v-if="results.current_page < results.last_page">
                                            <a href="#" aria-label="Next" @click.prevent="goToPage(results.current_page + 1)">
                                              <span aria-hidden="true">&raquo;</span>
                                            </a>
                                          </li>
                                        </ul>
                                      </nav>
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
                    <loader-animation></loader-animation>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import LoaderAnimation from './LoaderAnimation';

    export default {
        name: "browse-results",
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
                browseInProgress: false,
                results: {
                    total: 0
                },
                currentPage: this.page
            }
        },
        props: ['language_id', 'translate_to', 'letter', 'page', 'setMode'],
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
            goToPage(page) {
              this.currentPage = page;
              this.setBrowseParams();
              this.$router.push({
                  name: 'browse',
                  params: {
                      language_id: this.languageParams.language_id,
                      translate_to: this.languageParams.translate_to,
                      letter: this.browseParams.letter
                  },
                  query: {
                      page: this.browseParams.page
                  }
              });
            },
            browse() {
                this.currentPage = this.page;
                this.setBrowseParams();
                this.setLanguageParams();
                this.setMode('browse');

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
            },
            languageParams() {
                return this.$store.state.languageParams;
            },
            browseParams() {
                return this.$store.state.browseParams;

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

</style>
