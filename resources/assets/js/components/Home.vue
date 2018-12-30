<template>
    <div class="row">
        <div class="col-xs-12">

            <div class="row">
                <div class="col-xs-12">
                    <languages-form></languages-form>
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
                    <p class="text-center">Loading...</p>
                    <div class="loader"></div>
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
            LanguagesForm, SearchForm, BrowseForm
        },
        computed: {
            mode() {
                return this.$store.state.mode;
            }
        }
    }
</script>

<style scoped>

</style>
