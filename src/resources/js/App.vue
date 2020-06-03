<template>
    <div class="app-root">
        <div class="progress" v-if="isLoading">
            <v-progress-linear
                indeterminate
                color="#1976d2"
            ></v-progress-linear>
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name: 'App',

        created() {
            this.$vuetify.lang.current = 'ru';
        },

        computed: {
            isLoading() {
                return this.$store.state.auth.isLoading;
            }
        }
    }
</script>

<style>

</style>

