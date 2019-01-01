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
                           :class="{'text-danger': ! status.goodToGo}">Select letter or enter term to search for.</small>
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
        props: ['setMode'],
        methods: {
            go() {
                if( this.goodToGo ) {
                    this.setMode('search');
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

</style>
