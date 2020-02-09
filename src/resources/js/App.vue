<template>
    <v-app id="inspire">
        <div class="progress-overlay" v-if="isLoading">
            <v-progress-linear
                indeterminate
                color="primary"
            ></v-progress-linear>
        </div>
        <v-content>
            <router-view></router-view>
        </v-content>
    </v-app>
</template>

<script>
    export default {
        name: 'App',

        computed: {
            isLoading() {
                return this.$store.state.auth.isLoading;
            }
        }
    }
</script>

<style>

</style>

