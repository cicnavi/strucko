<template>
    <div :id="name">
        <form>

            <languages-select-input selected="eng"></languages-select-input>

            <languages-select-input selected="hrv"></languages-select-input>

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
        }
    }
</script>

<style scoped>

</style>