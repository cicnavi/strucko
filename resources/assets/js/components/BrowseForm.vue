<template>
    <div class="row">
        <div class="col-xs-12" id="browseForm">
            <div class="row" v-if="lettersLoaded">
                <div class="col-xs-12">
                    <button type="button"
                        v-for="currentLetter in letters"
                        class="btn btn-info btn-sm"
                        :class="$route.name == 'browse' && currentLetter.letter == $route.params.letter ? 'active' : ''"
                        :value="currentLetter.letter"
                        @click.prevent="getTermsByLetter"
                    >
                        {{ currentLetter.letter }}
                    </button>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

    export default {
        name: "browse-form",
        data: function () {
            return {
                name: 'browse-form',
                letter: '',
                page: 1,
                status: {
                    xhrInProgress: false
                }
            }
        },
        props: ['setMode'],
        methods: {
            setBrowseParams() {
                this.$store.commit('setBrowseParams', {
                    letter: this.letter,
                    page: this.page
                });
            },
            getTermsByLetter(event) {
                this.letter = event.target.value;
                this.page = 1;
                this.setBrowseParams();
                this.setMode('browse');
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


            }
        },
        components: {

        },
        computed: {
            languageParams() {
                return this.$store.state.languageParams;
            },
            browseParams() {
                return this.$store.state.browseParams;

            },
            lettersLoaded() {
              return this.$store.state.lettersLoaded;
            },
            letters() {
              return this.$store.state.letters;
            }
        },
        created() {

        },
    }
</script>

<style scoped>

</style>
