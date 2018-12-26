<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <button type="button" 
                        class="btn btn-primary btn-sm"
                        v-for="letter in letters"
                        @click.prevent="getTermsByLetter"
                    >
                        {{ letter.letter }}
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
                letters: []
            }
        },
        props: ['language_id', 'translate_to', 'letter'],
        methods: {
            getLetters() {
                let app = this;

                axios.get('api/v1/languages/' + this.languageParams.language_id + '/letters')
                    .then(function (response) {
                        app.letters = response.data;
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            },
            getTermsByLetter(event) {
                console.log(event);
                if (this.letter && this.letters.includes(this.letter)) {
                    console.log('možeš tražit po ' + this.letter);
                }
            }
        },
        components: {

        },
        computed: {
            languageParams() {
                return this.$store.state.languageParams;
            }
        },
        created() {
            this.getLetters();
        },
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