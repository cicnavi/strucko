<template>
    <div class="row">
        <div class="col-xs-12">

            <div class="row">
                <div class="col-xs-12">
                    <languages-form :setMode="setMode"></languages-form>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <browse-form :setMode="setMode"></browse-form>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <br>
                    <search-form :setMode="setMode"></search-form>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12" v-if="status.xhrInProgress">
                    <loader-animation></loader-animation>
                </div>
                <div class="col-xs-12" v-else>
                    <router-view :setMode="setMode"></router-view>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import LanguagesForm from './LanguagesForm';
    import SearchForm from './SearchForm';
    import BrowseForm from './BrowseForm';
    import LoaderAnimation from './LoaderAnimation';
    export default {
        name: "Home",
        data: function () {
            return {
                status: {
                  xhrInProgress: false
                }
            }
        },
        metaInfo () {
          return {
                // if no subcomponents specify a metaInfo.title, this title will be used
                title: 'Home',
                // all titles will be injected into this template
                titleTemplate: '%s | Strucko IT Dictionary',
                meta: [
                    {
                        name: 'description',
                        content: 'Multilingual Information Technology Dictionary.',
                        vmid: 'description'
                    },
                    {
                        'property': 'og:title',
                        'content': 'Home',
                        'template': chunk => `${chunk} | Strucko IT Dictionary`, //or as string template: '%s - My page',
                        'vmid': 'og:title'
                    },
                    {
                        'property': 'og:description',
                        'content': 'Multilingual Information Technology Dictionary.',
                        'vmid': 'og:description'
                    }
                ]
          }
        },
        methods: {
            setMode(mode) {
                this.$store.commit('setMode', mode);
            }
        },
        components: {
            LanguagesForm, SearchForm, BrowseForm, LoaderAnimation
        },
        computed: {
            mode() {
                return this.$store.state.mode;
            }
        },
        mounted() {
            this.setMode('start');
        }
    }
</script>

<style scoped>

</style>
