<template>
    <div :id="name">
        <form v-if="languagesLoaded">
            <languages-select-input defaultLanguage="eng">
                <label slot="label">Search in</label>
            </languages-select-input>

            <languages-select-input defaultLanguage="hrv">
                <label slot="label">Translate to</label>
            </languages-select-input>

            <div class="form-group">
                <input type="text"
                       id="term"
                       name="term"
                       class="form-control input-lg"
                       placeholder="Enter term"
                       aria-describedby="termHelp"
                        v-model="term">
                <small id="termHelp" class="form-text text-muted">Enter the term to search for.</small>
            </div>

            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <div v-else>
            <p class="text-center">Please wait as we prepare data...</p>
            <div class="loader"></div>
        </div>
    </div>
</template>

<script>
    import LanguagesSelectInput from './form-elements/LanguagesSelectInput';
    export default {
        name: "search-form",
        data: function () {
            return {
                name: 'search-form',
                term: '',
                languageId: null
            }
        },

        components: {
            LanguagesSelectInput
        },
        computed: {
            languagesLoaded() {
                return this.$store.state.languagesLoaded;
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