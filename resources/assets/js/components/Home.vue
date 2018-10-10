<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <languages-form></languages-form>
                </div>
            </div>

            <div>

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation"
                        :class="mode == 'search' ? activeTabClass : ''"
                    >
                        <a href="#search" aria-controls="search" role="tab" data-toggle="tab"
                            @click="setMode('search', $event)"
                        >Search</a>
                    </li>
                    <li role="presentation"
                        :class="mode == 'browse' ? activeTabClass : ''"
                    >
                        <a href="#browse" aria-controls="browse" role="tab" data-toggle="tab"
                           @click="setMode('browse', $event)"
                        >Browse</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel"
                         class="tab-pane fade"
                         :class="mode == 'search' ? activeTabContentClass : ''"
                         id="search">
                        <div class="row">
                            <div class="col-xs-12">
                                <search-form></search-form>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel"
                         class="tab-pane fade"
                         :class="mode == 'browse' ? activeTabContentClass : ''"
                         id="browse">
                        <browse-form></browse-form>
                    </div>
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
                activeTabClass: 'active',
                activeTabContentClass: 'in active'
            }
        },
        methods: {
            setMode(mode, event) {
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