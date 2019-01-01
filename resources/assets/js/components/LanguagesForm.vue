<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="row" v-if="languagesLoaded">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-10 col-sm-5 vcenter">

                            <div class="form-group">
                                <label>Language</label>
                                <select class="form-control" v-model="languageParamsLanguageId" @keyup.enter="go">
                                  <option value="" disabled>Select language</option>
                                  <option v-for="language in languages"
                                    :value="language.id"
                                    :disabled="language.id == languageParamsTranslateTo">
                                    {{ language.ref_name }}
                                  </option>
                                </select>
                            </div>
                        </div><!--

                        --><div class="col-xs-2 col-sm-2 text-center vcenter">
                          <div class="form-group">
                            <label>&nbsp;</label><br>
                            <button type="button"
                              class="btn btn-default"
                              aria-label="Switch Languages"
                              title="Switch languages"
                              @click.prevent="switchLanguages">
                              <span class="glyphicon glyphicon-retweet" aria-hidden="true"></span>
                            </button>
                          </div>
                        </div><!--

                        --><div class="col-xs-10 col-sm-5 vcenter">
                            <div class="form-group">
                                <label>Translate to</label>
                                <select class="form-control" v-model="languageParamsTranslateTo" @keyup.enter="go">
                                    <option value="" disabled>Select language</option>
                                    <option v-for="language in languages"
                                      :value="language.id"
                                      :disabled="language.id == languageParamsLanguageId">
                                      {{ language.ref_name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div class="row" v-else>
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
        name: "languages-form",
        data: function () {
            return {

            }
        },
        components: {
            LoaderAnimation
        },
        props: [
            'setMode'
        ],
        methods: {
          switchLanguages() {
            let languageId = this.languageParams.language_id;
            let translateTo = this.languageParams.translate_to;
            this.$store.commit('setLanguageParams', {
                language_id: translateTo,
                translate_to: languageId
            });
            this.$store.commit('setLettersLoaded', {lettersLoaded: false});
            this.$store.dispatch('getLettersFromApi');
            this.resolveRoute();
          },
          resolveRoute() {
            if (this.mode == 'search') {
              this.$router.push({
                  name: 'search',
                  params: {
                      language_id: this.languageParams.language_id,
                      translate_to: this.languageParams.translate_to
                  },
                  query: {
                      term: this.searchParams.term
                  }
              });
            } else if (this.mode == 'browse') {

                // Go to front page because of different letters in different lanugages
                this.setMode('start');
                this.$router.push({name: 'home'});
              /* TODO implement check for current letter existance in new language
              this.$router.push({
                  name: 'browse',
                  params: {
                      language_id: this.languageParams.language_id,
                      translate_to: this.languageParams.translate_to,
                      letter: this.browseParams.letter
                  },
                  query: {
                      page: 1
                  }
              });*/
            }
          }
        },
        computed: {
            mode() {
              return this.$store.state.mode;
            },
            languagesLoaded() {
                return this.$store.state.languagesLoaded;
            },
            languages() {
                return this.$store.state.languages;
            },
            searchParams() {
                return this.$store.state.searchParams;
            },
            browseParams() {
                return this.$store.state.browseParams;
            },
            languageParams() {
                return this.$store.state.languageParams;
            },
            languageParamsLanguageId: {
                get() {
                    return this.languageParams.language_id;
                },
                set(value) {
                  if ( ! value || this.languageParamsTranslateTo == value) {
                    return;
                  }

                  this.$store.commit('setLanguageParamsLanguageId', value);
                  this.$store.commit('setLettersLoaded', {lettersLoaded: false});
                  this.$store.dispatch('getLettersFromApi');
                  this.resolveRoute();
                }
            },
            languageParamsTranslateTo: {
                get() {
                    return this.languageParams.translate_to;
                },
                set(value) {
                  if ( ! value || this.languageParamsLanguageId == value) {
                    return;
                  }
                  this.$store.commit('setLanguageParamsTranslateTo', value);
                  this.resolveRoute();
                }
            }
        }
    }
</script>

<style scoped>
    .vcenter {
      display: inline-block;
      vertical-align: middle;
      float: none;
    }
</style>
