<template>
    <v-container fluid fill-height class="auth-container">
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md4>
                <router-view></router-view>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: "Auth"
    }
</script>

<style scoped>
    .auth-container {
        background: url("/storage/images/auth-bg.jpg") center;
        background-size: cover;
        background-attachment: fixed;
    }
</style>
