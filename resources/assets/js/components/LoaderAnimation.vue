<template>
    <div class="row">
        <div class="col-xs-12">

          <p class="text-center">{{ message }}</p>
          <div class="loader"></div>

        </div>
    </div>
</template>

<script>

    export default {
        name: "loader-animation",
        data: function () {
            return {
                message: 'Loading...',

            }
        },
        methods: {

        },
        components: {

        },
        computed: {

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
