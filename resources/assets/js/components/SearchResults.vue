<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="row" v-if="status.resultsReturned">
                <div class="col-xs-12">
                    <div class="row" v-if="results.exactMatch">
                        <div class="col-xs-12">
                            <h2>
                                {{ results.exactMatch.term }}
                                <small>
                            <span v-for="(translation, index) in results.exactMatch.translations">
                                {{ translation.translation.term }}<span v-if="index != ( results.exactMatch.translations.length - 1)">,</span>
                            </span>
                                </small>
                            </h2>
                            <p v-for="definition in results.exactMatch.definitions">
                                {{ definition.definition }}
                                <small style="font-style: italic;">
                                    <a :href="definition.link" :title="definition.source">
                                        source
                                    </a>
                                </small>
                            </p>
                        </div>
                    </div>
                    <div v-else>
                        <p class="text-info text-center">
                            No exact match for your query...
                        </p>
                    </div>

                    <div class="row" v-if="results.similarTerms.length > 0">

                        <div class="col-xs-12">
                            <hr>
                            <h3>Similar terms</h3>
                            <h4 v-for="(term, index) in results.similarTerms">
                                {{ term.term }}
                                <small>
                                <span v-for="(translation, index) in term.translations">
                                    {{ translation.translation.term }}<span v-if="index != (term.translations.length - 1)">,</span>
                                </span>
                                </small>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-if="status.apiError">
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
        props: ['status', 'results']

    }
</script>

<style scoped>

</style>