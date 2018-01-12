<template>
    <div class="form-group">

        <select class="form-control" v-model="selected">

            <option value="" disabled>Select language</option>
            <option v-for="language in languages" :value="language.id">{{ language.ref_name }}</option>
        </select>
    </div>
</template>

<script>
    import _ from 'lodash';

    export default {
        name: "languages-select-input",
        data: function () {
            return {
                selectedLanguage: 'eng',
                languages: [{id: 0, ref_name: 'Please wait as we load languages...'}],
                answer: ''
            }
        },
        props: ['selected'],
        computed: {
            // _.debounce is a function provided by lodash to limit how
            // often a particularly expensive operation can be run.
            // In this case, we want to limit how often we access
            // yesno.wtf/api, waiting until the user has completely
            // finished typing before making the ajax request. To learn
            // more about the _.debounce function (and its cousin
            // _.throttle), visit: https://lodash.com/docs#debounce
            langs: _.debounce(
                function () {
                    if (this.question.indexOf('?') === -1) {
                        this.answer = 'Questions usually contain a question mark. ;-)'
                        return
                    }
                    this.answer = 'Thinking...'
                    var vm = this
                    axios.get('https://yesno.wtf/api')
                        .then(function (response) {
                            vm.answer = _.capitalize(response.data.answer)
                        })
                        .catch(function (error) {
                            vm.answer = 'Error! Could not reach the API. ' + error
                        })
                },
                // This is the number of milliseconds we wait for the
                // user to stop typing.
                500
            )
        },


        created: function () {

            let languageSelectInput = this;

            axios.get('/strucko2/api/v1/languages')
                .then(function (response) {
                    languageSelectInput.languages = response.data;
                })
                .catch(function (error) {
                    languageSelectInput.languages = [{id: 0, ref_name: 'Could not get lanugages...'}];
                })
        }


    }
</script>

<style scoped>

</style>