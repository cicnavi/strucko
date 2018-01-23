<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="row" v-if="resultsReturned">
                <div class="col-xs-12">
                    Results
                </div>
            </div>
            <div class="row" v-if="apiError">
                <div class="col-xs-12">
                    <p class="text-danger text-center">
                        There was an error fetching results from the API...
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "search-results",
        props: ['resultsReturned', 'apiError', 'results']

    }
</script>

<style scoped>

</style>