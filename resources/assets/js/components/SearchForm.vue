<template>
    <div class="row">
        <div class="col-xs-12">

            <div class="row">

                <div class="form-group col-xs-12">
                    <div class="input-group">
                        <input type="text"
                               class="form-control input-lg"
                               placeholder="Enter term"
                               aria-describedby="termHelp"
                               v-model.lazy.trim="searchParamsTerm"
                               @keyup.enter="go" >

                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary btn-lg" @click.prevent="go">Go</button>
                        </span>
                    </div><!-- /input-group -->

                    <small id="termHelp"
                           class="form-text text-muted"
                           :class="{'text-danger': ! status.goodToGo}">Enter the term to search for.</small>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12" v-if="status.searchInProgress">
                    <p class="text-center">Searching...</p>
                    <div class="loader"></div>
                </div>
                <div class="col-xs-12" v-else>
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SearchResults from './SearchResults';
    export default {
        name: "search-form",
        data: function () {
            return {
                name: 'search-form',
                status: {
                    goodToGo: true,
                    termNotEntered: false
                }
            }
        },
        methods: {
            go() {
                if( this.goodToGo ) {
                    this.status.goodToGo = true;
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
                } else {
                    this.status.goodToGo = false;
                }
            }
        },
        components: {
            SearchResults
        },
        computed: {
            goodToGo() {
                return this.$store.getters.searchIsGoodToGo;
            },
            searchParams() {
                return this.$store.state.searchParams;
            },
            languageParams() {
                return this.$store.state.languageParams;
            },
            searchParamsTerm: {
                get() {
                    return this.searchParams.term;
                },
                set(value) {
                    this.$store.commit('setSearchParamsTerm', value);
                }
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