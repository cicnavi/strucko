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
