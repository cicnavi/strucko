<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="row" v-if="languagesLoaded">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">

                            <div class="form-group">
                                <label>Language</label>
                                <select class="form-control" v-model="languageParamsLanguageId" @keyup.enter="go">
                                    <option value="" disabled>Select language</option>
                                    <option v-for="language in languages" :value="language.id">{{ language.ref_name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label>Translate to</label>
                                <select class="form-control" v-model="languageParamsTranslateTo" @keyup.enter="go">
                                    <option value="" disabled>Select language</option>
                                    <option v-for="language in languages" :value="language.id">{{ language.ref_name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div class="row" v-else>
                <div class="col-xs-12">
                    <p class="text-center">Please wait while we prepare data...</p>
                    <div class="loader"></div>
                </div>
            </div>


        </div>
    </div>
</template>

<script>
    import LanguagesSelectInput from './form-elements/LanguagesSelectInput';

    export default {
        name: "languages-form",
        data: function () {
            return {
                name: 'languages-form'
            }
        },
        methods: {
        },
        components: {
            LanguagesSelectInput
        },
        computed: {
            languagesLoaded() {
                return this.$store.state.languagesLoaded;
            },
            languages() {
                return this.$store.state.languages;
            },
            searchParams() {
                return this.$store.state.searchParams;
            },
            languageParams() {
                return this.$store.state.languageParams;
            },
            languageParamsLanguageId: {
                get() {
                    return this.languageParams.language_id;
                },
                set(value) {
                    this.$store.commit('setLanguageParamsLanguageId', value);
                }
            },
            languageParamsTranslateTo: {
                get() {
                    return this.languageParams.translate_to;
                },
                set(value) {
                    this.$store.commit('setLanguageParamsTranslateTo', value);
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